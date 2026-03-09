<template>
  <div class="db-page">
    <!-- top bar -->
    <header class="db-top">
      <div class="db-brand">
        <div class="db-title">Piljarnica <span>NOVA</span></div>
      </div>

      <div class="user-box" @click="handleLogout" style="cursor: pointer">
        <v-icon color="white">mdi-account</v-icon>
        <span v-if="userName" class="user-name">{{ userName }}</span>
        <v-tooltip activator="parent" location="bottom" content-class="green-tooltip">Odjavi se</v-tooltip>
      </div>
    </header>

    <!-- main panel -->
    <div class="db-shell">
      <div class="db-panel">
        <!-- LEFT: buttons -->
        <aside class="db-left">
          <button class="db-btn" @click="active = 'add-product'">DODAJ ARTIKAL</button>
          <button class="db-btn" @click="openAddQty">DODAJ KOLIČINE</button>
          <div class="db-spacer" />

          <button class="db-btn" @click="loadProducts">LISTA ARTIKALA</button>
          <button class="db-btn" @click="loadOrders">LISTA KUPNJI</button>
          <button class="db-btn" @click="loadCustomers">LISTA KUPACA</button>
          <div class="db-spacer" />

          <!-- ovo vidi samo super admin -->
          <button v-if="isSuperAdmin" class="db-btn" @click="active = 'add-admin'"> DODAJ ADMIN </button>
        </aside>

        <!-- prikaz u prozoru -->
        <section class="db-right">
          <div class="db-box db-main">
            <div class="db-box-title">PRIKAZ:</div>

            <div class="db-box-body">
              <!--dodavanje novog artikla-->
              <div v-if="active === 'add-product'">
                <div style="display: grid; gap: 10px; max-width: 520px">
                  <select v-model.number="newProduct.category_id">
                    <option :value="null">Odaberi kategoriju</option>
                    <option :value="1">Voće</option>
                    <option :value="2">Povrće</option>
                    <option :value="3">Gotovi proizvodi</option>
                  </select>

                  <input v-model="newProduct.name" placeholder="naziv proizvoda"/>
                  <input v-model="newProduct.description" placeholder="opis proizvoda"/>
                  <input v-model="newProduct.porijeklo" placeholder="porijeklo"/>
                  <input v-model="newProduct.klasa" placeholder="klasa"/>
                  <input v-model.number="newProduct.price" type="number" step="0.01" placeholder="cijena (€)"/>
                  <input v-model.number="newProduct.quantity" type="number" step="0.001" placeholder="količina (npr. kg)"/>
                  <input v-model="newProduct.image" placeholder="Slika (npr. jabuka.png)"/>

                  <button class="db-btn" style="max-width: 260px" @click="createProduct"> SPREMI ARTIKAL</button>
                </div>
              </div>
              <!--dodavanje količine -->
              <div v-else-if="active === 'add-qty'">
                <div style="display: grid; gap: 12px; max-width: 480px">
                  <select
                    v-model.number="selectedProductId"
                    @change="
                      selectedProductId && pickProductForEdit(selectedProductId)
                    "
                  >
                    <option :value="null">Odaberi proizvod</option>
                    <option v-for="p in products" :key="p.id" :value="p.id">
                      {{ p.name }}
                    </option>
                  </select>
                  <input v-model.number="editProduct.quantity" type="number" step="0.001" placeholder="Nova količina (kg)" :disabled="!editProduct.id" />
                  <input v-model.number="editProduct.price"type="number"step="0.01"placeholder="Nova cijena (€)":disabled="!editProduct.id" />
                  <button class="db-btn" style="max-width: 260px" @click="updateProductQty">SPREMI PROMJENE</button>
                </div>
              </div>

               <!--promjena cjene-->
              <div v-else-if="active === 'add-price'">
                <div style="display: grid; gap: 12px; max-width: 480px">
                  <select
                    v-model.number="selectedProductId"
                    @change="
                      selectedProductId && pickProductForEdit(selectedProductId)
                    "
                  >
                    <option :value="null">Odaberi proizvod</option>
                    <option v-for="p in products" :key="p.id" :value="p.id">
                      {{ p.name }}
                    </option>
                  </select>

                  <input v-model.number="editProduct.price"type="number"step="0.01"placeholder="Nova cijena (€)":disabled="!editProduct.id" />

                  <button class="db-btn" style="max-width: 260px" @click="updateChangePrice">SPREMI PROMJENE</button>
                </div>
              </div>

              <!-- lista kupnji -->
              <div v-if="active === 'orders'">
                <div v-if="orders.length === 0">Nema kupnji.</div>

                <div v-for="o in orders" :key="o.id">
                  <b>#{{ o.id }}</b> – {{ o.user?.name ?? "-" }} – €{{
                    o.total_price
                  }}
                </div>
              </div>

              <!-- lista artikala -->
              <div v-else-if="active === 'products'">
                <div v-if="products.length === 0">Nema artikala.</div>

                <div v-for="p in products" :key="p.id">
                  {{ p.name }} – {{ p.quantity }} kg
                </div>
              </div>
              <div v-else-if="active === 'customers'">
                <div v-if="customers.length === 0">Nema kupaca.</div>
                <div v-for="c in customers" :key="c.id">
                  <b>{{ c.name }}</b> – {{ c.email }}
                </div>
              </div>
              <!--admin--->
              <div v-else-if="active === 'add-admin'">
                <div style="display: grid; gap: 10px; max-width: 520px">
                  <input v-model="newAdmin.name" placeholder="Ime i prezime" />
                  <input v-model="newAdmin.email" placeholder="Email" />
                  <input v-model="newAdmin.password"type="password"placeholder="Lozinka" />

                  <button class="db-btn" style="max-width: 260px" @click="createAdmin">SPREMI ADMINA </button>
                </div>
              </div>
            </div>
          </div>
          <div class="db-bottom">
            <div class="db-box db-small">
              <div class="db-box-title">NAJPRODAVANIJE:</div>
              <div class="db-box-body">
                <div v-if="bestSelling.length === 0">Nema podataka.</div>
                <div v-for="p in bestSelling" :key="p.id">
                  {{ p.name }} – {{ p.total_qty }}
                </div>
              </div>
            </div>
            <div class="db-box db-small">
              <div class="db-box-title">TOP PET KUPACA:</div>
              <div class="db-box-body">
                <div v-if="topCustomers.length === 0">Nema podataka.</div>
                <div v-for="c in topCustomers" :key="c.id">
                  {{ c.name }} – €{{ Number(c.total_spent).toFixed(2) }}
                </div>
              </div>
            </div>
          </div>
        </section>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">

import { computed, ref, reactive } from "vue";
import { useRouter } from "vue-router";
import { onMounted } from "vue";

  onMounted(() => {
    loadStats();
  });

  type ActiveView =
    | "add-product"
    | "add-qty"
    | "products"
    | "orders"
    | "customers"
    | "add-admin";

  const bestSelling = ref<any[]>([]);
  const topCustomers = ref<any[]>([]);

  async function loadStats() {
    const r1 = await fetch(`${API}/stats/best-selling`, { headers: authHeaders() });
    bestSelling.value = r1.ok ? await r1.json() : [];

    const r2 = await fetch(`${API}/stats/top-customers`, { headers: authHeaders() });
    topCustomers.value = r2.ok ? await r2.json() : [];
  }

  const router = useRouter();
  const active = ref<ActiveView | null>(null);

  const API = "http://127.0.0.1:8000/api";

  const user = computed(() => {
    try {
      return JSON.parse(localStorage.getItem("user") || "null");
    } catch {
      return null;
    }
  });

  const userName = computed(() => user.value?.name || "");

  const isAdmin = computed(() => {
    const role = Number(user.value?.role_id);
    return role === 3 || role === 4;
  });

  const isSuperAdmin = computed(() => Number(user.value?.role_id) === 4);

  function authHeaders() {
    const token = localStorage.getItem("token");
    return {
      Accept: "application/json",
      ...(token ? { Authorization: `Bearer ${token}` } : {}),
    };
  }

  /* LOGOUT */
  async function handleLogout() {
    try {
      await fetch(`${API}/logout`, { method: "POST", headers: authHeaders() });
    } catch (e) {
      console.log("Logout request failed:", e);
    } finally {
      localStorage.removeItem("token");
      localStorage.removeItem("user");
      router.replace("/adminlogin"); // tvoja login ruta
    }
  }

  /* DATA */
  const products = ref<any[]>([]);
  const orders = ref<any[]>([]);
  const customers = ref<any[]>([]);

  /* FORME */
  const newProduct = reactive({
    category_id: null as number | null,
    name: "",
    description: null as string | null,
    porijeklo: null as string | null,
    klasa: null as string | null,
    price: null as number | null,
    image: null as string | null,
    quantity: null as number | null,
    unit:"kg",

    is_active: true,
  });

  const editProduct = reactive({
    id: null as number | null,
    category_id: null as number | null,
    name: "",
    is_active: true,
    price: null as number | null,
    quantity: null as number | null,
  });

  const selectedProductId = ref<number | null>(null);

  const newAdmin = reactive({
    name: "",
    email: "",
    password: "",
  });

  /* HELPERS */
  async function fetchProductsOnly() {
    const res = await fetch(`${API}/products`, {
      headers: { Accept: "application/json" },
    });

    if (!res.ok) {
      console.log("PRODUCTS STATUS:", res.status);
      console.log("PRODUCTS BODY:", await res.text());
      products.value = [];
      return;
    }

    products.value = await res.json();
  }

  /* LISTE */
  async function loadProducts() {
    active.value = "products";
    await fetchProductsOnly();
  }

  async function loadOrders() {
    active.value = "orders";

    const res = await fetch(`${API}/orders`, {
      headers: authHeaders(),
    });

    const text = await res.text();
    console.log("ORDERS STATUS:", res.status);
    console.log("ORDERS BODY:", text);

    if (!res.ok) {
      orders.value = [];
      return;
    }

    orders.value = JSON.parse(text);
  }

  async function loadCustomers() {
    active.value = "customers";

    const res = await fetch(`${API}/customers`, {
      headers: authHeaders(),
    });

    const text = await res.text();
    console.log("CUSTOMERS STATUS:", res.status);
    console.log("CUSTOMERS BODY:", text);

    if (!res.ok) {
      customers.value = [];
      return;
    }

    customers.value = JSON.parse(text);
  }

  /* AKCIJE */
  async function createProduct() {
    const res = await fetch(`${API}/products`, {
      method: "POST",
      headers: { ...authHeaders(), "Content-Type": "application/json" },
      body: JSON.stringify(newProduct),
    });

    const text = await res.text();
    console.log("CREATE PRODUCT STATUS:", res.status);
    console.log("CREATE PRODUCT BODY:", text);

    if (!res.ok) return;

    newProduct.category_id = null;
    newProduct.name = "";
    newProduct.description = null;
    newProduct.porijeklo = null;
    newProduct.klasa = null;
    newProduct.price = null;
    newProduct.image = null;
    newProduct.quantity = null;
    newProduct.is_active = true;

    await loadProducts();
    active.value = "products";
  }

  async function openAddQty() {
    active.value = "add-qty";

    if (products.value.length === 0) {
      await fetchProductsOnly();
    }

    selectedProductId.value = null;

    editProduct.id = null;
    editProduct.category_id = null;
    editProduct.name = "";
    editProduct.is_active = true;
    editProduct.quantity = null;
  }

  /*PROMJENI CJENU*/

  function pickProductForEdit(productId: number) {
    const p = products.value.find((x) => x.id === productId);
    if (!p) return;

    editProduct.id = Number(p.id);
    editProduct.category_id = Number(p.category_id) || null;
    editProduct.name = String(p.name ?? "");
    editProduct.is_active = p.is_active ?? true;

    editProduct.price = p.price != null ? Number(p.price) : null;
    editProduct.quantity = p.quantity != null ? Number(p.quantity) : null;
  }

  async function updateProductQtyPrice() {
    if (!editProduct.id) return;

    const res = await fetch(`${API}/products/${editProduct.id}`, {
      method: "PUT",
      headers: { ...authHeaders(), "Content-Type": "application/json" },
      body: JSON.stringify({
        category_id: editProduct.category_id,
        name: editProduct.name,
        is_active: editProduct.is_active,
        price: editProduct.price,
        quantity: editProduct.quantity,
      }),
    });

    const text = await res.text();
    console.log("UPDATE PRODUCT STATUS:", res.status);
    console.log("UPDATE PRODUCT BODY:", text);

    if (!res.ok) return;

    await fetchProductsOnly();
  }

  async function createAdmin() {
    const res = await fetch(`${API}/admins`, {
      method: "POST",
      headers: { ...authHeaders(), "Content-Type": "application/json" },
      body: JSON.stringify(newAdmin),
    });

    const text = await res.text();
    console.log("CREATE ADMIN STATUS:", res.status);
    console.log("CREATE ADMIN BODY:", text);

    if (!res.ok) return;

    newAdmin.name = "";
    newAdmin.email = "";
    newAdmin.password = "";
  }
</script>

<style scoped>
.db-page {
  min-height: 100vh;
  background: #e9eef6;
}

/* top bar */
.db-top {
  height: 64px;
  border-radius: 10px;
  background: #455a1f;
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0 22px;
}

.db-brand {
  display: flex;
  align-items: center;
  gap: 12px;
}

.db-title {
  color: #ffffff;
  font-family: "Architects Daughter", cursive;
  font-size: 26px;
  letter-spacing: 0.5px;
}
.db-title span {
  margin-left: 6px;
}

.db-user :deep(.v-icon) {
  color: #fff;
}

/* shell */
.db-shell {
  padding: 0;
  display: block;
}

.db-panel {
  width: 100%;
  min-height: calc(100vh - 64px);
  background: #455a1f;
  border-radius: 10px;
  padding: 16px;
  display: grid;
  grid-template-columns: 320px 1fr;
  gap: 18px;
  border: 3px solid #ffffff;
}

/* left */
.db-left {
  display: flex;
  flex-direction: column;
  gap: 14px;
  padding: 10px;
}

.db-btn {
  height: 48px;
  border-radius: 10px;
  border-width:2px ;
  border-style: solid;
  border-color: #455a1f;
  background: #ffffff;
  color: #455a1f;
  font-weight: 700;
  font-size: 20px;
  letter-spacing: 0.5px;
  cursor: pointer;
}

.db-btn:active {
  transform: translateY(1px);
}

.db-spacer {
  height: 22px;
}

/* right */
.db-right {
  display: flex;
  gap: 24px;
  align-items: flex-start;
}

.db-box {
  background: #ffffff;
  width: 500px;
  border-radius: 6px;
  border: 3px solid #455a1f;
  overflow: hidden;
}

.db-box-title {
  padding: 10px 12px;
  color: #455a1f;
  font-size: 12px;
  text-transform: uppercase;
  letter-spacing: 0.6px;
}

.db-box-body {
  padding: 12px;
  min-height: 120px;
}

.db-main .db-box-body {
  min-height: 320px;
}

.db-bottom {
  display: grid;
  grid-template-columns: 1fr;
  gap: 18px;
}

.db-small .db-box-body {
  min-height: 140px;
}

.db-placeholder {
  color: rgba(69, 90, 31, 0.65);
  font-size: 14px;
}

/* responsive */
@media (max-width: 900px) {
  .db-panel {
    grid-template-columns: 1fr;
  }
  .db-right {
    padding: 0 10px 10px;
  }
  .db-bottom {
    grid-template-columns: 1fr;
  }
}
.user-name {
  color: white;
  margin-left: 6px;
}
</style>
