<template>
  <ShopLayout>
    <ProductGrid :products="products" @select="onSelect" />
    <ProductCardDialog v-model="dialog" :product="selected" @add="onAddToCart " />
    <!--prozorcic s porukom -->
    <v-dialog v-model="loginDialog" max-width="360">
      <v-card rounded="xl" class="pa-6 text-center">
        <div class="mb-4">
          Molimo registrirajte se prije kupnje.
        </div>

        <v-btn rounded="pill" class="auth-btn" @click="$router.push('/registracija')">
          REGISTRACIJA
        </v-btn>
        <CartCard v-model="cartDialog" />

      </v-card>
    </v-dialog>

  </ShopLayout>
</template>

<script setup lang="ts">

  import { onMounted, ref } from "vue"
  import { useCartStore } from "@/stores/cart"
  import ProductGrid, { type ProductGridItem } from "@/components/ProductGrid.vue"
  import ProductCardDialog from "@/components/ProductCardDialog.vue"
  import ShopLayout from "@/components/ShopLayout.vue"

  type ApiProduct = {
    id: number | string
    name?: string
    description?: string | null
    image?: string | null
    price?: number | string | null
    quantity?: number | string | null
    porijeklo?: string | null
    klasa?: string | null
    unit?: string | null
  }

  const cart = useCartStore()
  const loginDialog = ref(false);

  const dialog = ref(false)
  const selected = ref<ProductGridItem | null>(null)
  const products = ref<ProductGridItem[]>([])

  async function load () {
    const res = await fetch('http://127.0.0.1:8000/api/products?category_id=2')
    const data: ApiProduct[] = await res.json()

    products.value = data.map(p => ({
      id: p.id,
      name: p.name ?? "",
      description: p.description ?? "",
      image: p.image ?? "",
      unit: p.unit ?? "kg",
      price: Number(p.price ?? 0),
      quantity: Number(p.quantity ?? 0),
      porijeklo: p.porijeklo ?? null,
      klasa: p.klasa ?? null,
    }))
  }
  /*poruka da se treba registrirat*/
  function onSelect (p: ProductGridItem) {
    const token = localStorage.getItem("token");

    if (!token) {
      loginDialog.value = true;
      return;
    }

    selected.value = p;
    dialog.value = true;
  }

  onMounted(load)

  function onAddToCart (payload: { product: ProductGridItem; amountKg: number }) {
    cart.addItem({
      productId: payload.product.id,
      name: payload.product.name,
      price: Number(payload.product.price ?? 0),
      qtyKg: payload.amountKg,
    });
  }
</script>
