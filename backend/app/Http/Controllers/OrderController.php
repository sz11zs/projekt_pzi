<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\DB;


class OrderController extends Controller
{
    public function index()
    {
        return Order::with(['user', 'orderItems.product'])
            ->orderByDesc('id')
            ->get();
    }

    public function show(Order $order)
    {
        return $order->load(['user', 'orderItems.product']);
    }
    public function bestSelling()
{
    return DB::table('order_items')
        ->join('products', 'order_items.product_id', '=', 'products.id')
        ->select('products.id', 'products.name', DB::raw('SUM(order_items.quantity) as total_qty'))
        ->groupBy('products.id', 'products.name')
        ->orderByDesc('total_qty')
        ->limit(5)
        ->get();
}

public function topCustomers()
{
    return DB::table('orders')
        ->join('users', 'orders.user_id', '=', 'users.id')
        ->select('users.id', 'users.name', DB::raw('SUM(orders.total_price) as total_spent'))
        ->groupBy('users.id', 'users.name')
        ->orderByDesc('total_spent')
        ->limit(5)
        ->get();
}

}
