<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Order_item;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'payment_method_id' => 'required|exists:payment_methods,id',
            'delivery_address'  => 'required|string|max:100',
            'phone_number'      => 'required|string|max:100',
            'items'             => 'required|array|min:1',
            'items.*.product_id'=> 'required|exists:products,id',
            'items.*.quantity'  => 'required|numeric|min:0.001',
        ]);

        $userId = $request->user()->id;

        return DB::transaction(function () use ($data, $userId) {
            $total = 0;

            // 1) Provjeri zalihe + izračun total
            foreach ($data['items'] as $it) {
                $product = Product::lockForUpdate()->find($it['product_id']);

                $qty = (float)$it['quantity'];
                $unitPrice = (float)$product->price;

                if ($qty > (float)$product->quantity) {
                    return response()->json([
                        'message' => "Nema dovoljno zaliha za: {$product->name}"
                    ], 422);
                }

                $total += $unitPrice * $qty;
            }

            // 2) Kreiraj order
            $order = Order::create([
                'user_id'           => $userId,
                'payment_method_id' => $data['payment_method_id'],
                'delivery_address'  => $data['delivery_address'],
                'phone_number'      => $data['phone_number'],
                'total_price'       => round($total, 2),
                'status'            => 'created',
            ]);

            // 3) Kreiraj stavke + umanji količine
            foreach ($data['items'] as $it) {
                $product = Product::lockForUpdate()->find($it['product_id']);

                $qty = (float)$it['quantity'];
                $unitPrice = (float)$product->price;

                Order_item::create([
                    'order_id'   => $order->id,
                    'product_id' => $product->id,
                    'quantity'   => $qty,
                    'unit_price' => $unitPrice,
                ]);

                $product->quantity = (float)$product->quantity - $qty;
                $product->save();
            }

            return response()->json([
                'order_id' => $order->id,
                'total'    => $order->total_price,
                'status'   => $order->status,
            ], 201);
        });
    }
}
