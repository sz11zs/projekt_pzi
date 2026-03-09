<template>
  <div class="shop-layout">
    <!--RESPONSIVNOST-->
    <v-navigation-drawer v-model="drawer" temporary location="left" width="280" color="#455A1F">
      <v-list nav class="pa-2">
        <!-- MOBITEL: search u draweru -->
        <div class="d-flex d-sm-none mb-3">
          <v-text-field
            v-model="searchText"
            density="compact"
            variant="underlined"
            placeholder="Pretraži..."
            hide-details
            class="flex-grow-1"
            @keyup.enter="onDrawerSearch"
          />
          <v-btn icon variant="text" @click="onDrawerSearch">
            <v-icon>mdi-magnify</v-icon>
          </v-btn>
        </div>

        <v-list-item to="/" @click="drawer = false">
          POČETNA STRANICA
        </v-list-item>

        <v-list-item to="/trgovina/voce" @click="drawer = false">
          VOĆE
        </v-list-item>

        <v-list-item to="/trgovina/povrce" @click="drawer = false">
          POVRĆE
        </v-list-item>

        <v-list-item to="/trgovina/gotovi" @click="drawer = false">
          GOTOVI PROIZVODI
        </v-list-item>

        <v-divider class="my-3" />

        <v-list-item title="Registracija / Prijava" prepend-icon="mdi-account" to="/registracija" @click="drawer = false" />
      </v-list>
    </v-navigation-drawer>

    <header class="navbar">
      <!-- hamburger: tablet + mob -->
      <v-btn icon variant="text" class="d-flex d-lg-none" @click="drawer = true">
        <v-icon color="white">mdi-menu</v-icon>
      </v-btn>

      <nav class="nav-links d-none d-lg-flex">
        <!--LINKOVI U NAVBARU VIDE SE SAMO NA DESKTOP-->
        <v-btn variant="text" class="nav-btn" to="/">POČETNA STRANICA</v-btn>
        <v-btn variant="text" class="nav-btn" to="/trgovina/voce">VOĆE</v-btn>
        <v-btn variant="text" class="nav-btn" to="/trgovina/povrce" >POVRĆE</v-btn>
        <v-btn variant="text" class="nav-btn" to="/trgovina/gotovi">GOTOVI PROIZVODI</v-btn>
      </nav>

      <div class="actions">
        <div class="d-none d-sm-flex actions-desktop">
          <v-text-field
            v-model="searchText"
            density="compact"
            variant="underlined"
            placeholder="Pretraži..."
            hide-details
            style="width: 220px"
            @keyup.enter="doSearch"
          />

          <v-btn icon variant="text" @click="doSearch">
            <v-icon>mdi-magnify</v-icon>
          </v-btn>

          <div class="user-box" @click="handleLogout" style="cursor: pointer">
            <v-icon>mdi-account</v-icon>
            <span v-if="userName" class="user-name">{{ userName }}</span>
            <v-tooltip activator="parent" location="bottom" content-class="green-tooltip">Odjavi se</v-tooltip>
          </div>
        </div>

        <!-- košarica se uvijek vidi -->
        <v-btn variant="text" icon @click="cartDialog = true">
          <v-icon>mdi-cart</v-icon>
        </v-btn>
        <ProductCardDialog v-model="searchDialog" :product="searchSelected" />
      </div>
    </header>

    <!-- CONTENT -->
    <main class="content">
      <slot />
    </main>
    <CartDialog v-model="cartDialog" @checkout="onCheckout" />
    <PaymentDialog v-model="paymentDialog" @select="onPaymentSelect" />
    <CardPaymentDialog v-model="cardDialog" @buy="onCardBuy" />
    <DeliveryAddressDialog v-model="addressDialog" @confirm="onAddressConfirm"/>
    <ThankYouDialog v-model="thankYouDialog" />
  </div>
</template>

<script setup lang="ts">

  import { ref } from "vue";
  import { useCartStore } from "@/stores/cart";
  import { onMounted } from "vue";
  import { ProductGridItem } from "@/components/ProductGrid.vue";
  import CartDialog from "@/components/CartCard.vue";
  import ProductCardDialog from "@/components/ProductCardDialog.vue";
  import PaymentDialog from "@/components/PaymentDialog.vue";
  import CardPaymentDialog from "@/components/CardPaymentDialog.vue";
  import DeliveryAddressDialog from "@/components/DeliveryAddressDialog.vue";
  import ThankYouDialog from "@/components/ThankYouDialog.vue";


  const cart = useCartStore();
  const thankYouDialog = ref(false);
  const addressDialog = ref(false);
  const chosenMethod = ref<"card" | "cod" | null>(null);
  const cardDialog = ref(false);
  const searchText = ref("");
  const searchDialog = ref(false);
  const searchSelected = ref<ProductGridItem | null>(null);
  const cartDialog = ref(false);
  const paymentDialog = ref(false);
  const userName = ref<string | null>(null);
  const drawer = ref(false);

  function onDrawerSearch() {
    drawer.value = false;
    doSearch();
  }

  function loadUserName() {
    const raw = localStorage.getItem("user");
    userName.value = raw ? JSON.parse(raw).name : null;
  }

  onMounted(loadUserName);

  // (opcionalno) refresh ako se user promijeni u drugom tabu
  window.addEventListener("storage", loadUserName);
  async function handleLogout() {
    const token = localStorage.getItem("token");

    if (token) {
      try {
        // Obavještavamo backend da obriše token
        await fetch("http://127.0.0.1:8000/api/logout", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            Authorization: `Bearer ${token}`,
          },
        });
      } catch (e) {
        console.error("Logout backend error:", e);
      }
    }

    // Bez obzira na backend, brišemo lokalne podatke
    localStorage.removeItem("token");
    localStorage.removeItem("user");
    userName.value = null;

    // Preusmjeri na home ili login stranicu
    window.location.href = "/";
  }
  async function onAddressConfirm(payload: {
    street: string;
    city: string;
    phone: string;
  }) {
    try {
      const token = localStorage.getItem("token");
      if (!token) {
        console.log("Nema tokena - korisnik nije prijavljen");
        return;
      }

      const payment_method_id = chosenMethod.value === "card" ? 1 : 2;

      const body = {
        payment_method_id,
        delivery_address: `${payload.street}, ${payload.city}`,
        phone_number: payload.phone,
        items: cart.items.map((i) => ({
          product_id: i.productId,
          quantity: i.qtyKg,
        })),
      };

      const res = await fetch("http://127.0.0.1:8000/api/checkout", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          Authorization: `Bearer ${token}`,
        },
        body: JSON.stringify(body),
      });

      const data = await res.json().catch(() => ({}));

      if (!res.ok) {
        console.log("CHECKOUT ERROR:", data);
        return;
      }

      console.log("CHECKOUT OK:", data);

      addressDialog.value = false;
      thankYouDialog.value = true;
      cart.clear();
    } catch (e) {
      console.log("CHECKOUT FAIL:", e);
    }
  }

  function onCheckout() {
    cartDialog.value = false;
    paymentDialog.value = true;
  }

  function onPaymentSelect(method: "card" | "cod") {
    if (method === "card") {
      paymentDialog.value = false;
      cardDialog.value = true;
    } else {
      paymentDialog.value = false;
      addressDialog.value = true;
    }
  }

  function onCardBuy(payload: {
    fullName: string;
    cardNumber: string;
    exp: string;
  }) {
    console.log("CARD BUY:", payload);

    cardDialog.value = false;
    addressDialog.value = true;
  }

  async function doSearch() {
    const q = searchText.value.trim();
    if (!q) return;

    const res = await fetch(
      `http://localhost:8000/api/products?q=${encodeURIComponent(q)}`,
    );
    const data = await res.json();

    if (!Array.isArray(data) || data.length === 0) return;

    const p = data[0];

    searchSelected.value = {
      id: p.id,
      name: p.name ?? "",
      description: p.description ?? "",
      image: p.image ?? "",
      price: Number(p.price ?? 0),
      quantity: Number(p.quantity ?? 0),
      porijeklo: p.porijeklo ?? null,
      klasa: p.klasa ?? null,
    };

    searchDialog.value = true;
    searchText.value = "";
  }
</script>

<style scoped>
.content {
  flex: 1;
  padding: 24px;
}

.shop-layout {
  min-height: 100vh;
  background: #455a1f;
  display: flex;
  flex-direction: column;
}

/* NAVBAR */
.navbar {
  height: 64px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0 24px;
  color: white;
}

.logo {
  font-weight: 600;
}

.actions {
  display: flex;
  align-items: center;
  gap: 16px;
}

.actions-desktop {
  display: flex;
  align-items: center;
  gap: 16px;
}
.user-box {
  display: flex;
  align-items: center;
  gap: 6px;
}

.user-name {
  font-size: 13px;
  color: white;
}
.nav-links {
  display: flex;
  gap: 8px;
}

.nav-btn {
  font-size: 14px;
  color: #ffffff;
}

/* Stil za drawer tekst */
:deep(.v-list-item-title) {
}

.menu-title :deep(.v-list-item-title) {
  font-size: 20px !important;
  color: #d8d48a !important; /* Bež boja za isticanje */
}
</style>
