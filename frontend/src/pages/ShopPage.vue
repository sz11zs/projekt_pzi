<template>
  <ShopLayout>
    <div class="shop-menu">
      <div class="menu-grid">
        <RouterLink class="cat-link" to="/trgovina/voce">
          <div class="cat-content">
            <v-card class="cat-card" rounded="xl" elevation="0">
              <v-img :src="voceImg" class="cat-img" contain />
            </v-card>
            <div class="cat-label">VOĆE</div>
          </div>
        </RouterLink>

        <RouterLink class="cat-link" to="/trgovina/povrce">
          <div class="cat-content">
            <v-card class="cat-card" rounded="xl" elevation="0">
              <v-img :src="povrceImg" class="cat-img" contain />
            </v-card>

            <div class="cat-label">POVRĆE</div>
          </div>
        </RouterLink>

        <RouterLink class="cat-link" to="/trgovina/gotovi">
          <div class="cat-content">
            <v-card class="cat-card" rounded="xl" elevation="0">
              <v-img :src="gotoviImg" class="cat-img" contain />
            </v-card>

            <div class="cat-label">GOTOVI PROIZVODI</div>
          </div>
        </RouterLink>
      </div>
    </div>
  </ShopLayout>
</template>

<script setup lang="ts">
  import ShopLayout from "@/components/ShopLayout.vue";
  import voceImg from "@/assets/voce.png";
  import povrceImg from "@/assets/povrce.png";
  import gotoviImg from "@/assets/gotoviProizvodi.png";
</script>

<style scoped>
.shop-menu {
  /* visina sadržaja ispod navbara */
  min-height: calc(100vh - 64px);

  /* pozicioniranje malo niže (da nije previsoko) */
  padding-top: 180px;
  padding-left: 32px;
  padding-right: 32px;
}

/* DESKTOP: 3 u jednom redu */
.menu-grid {
  display: grid;
  grid-template-columns: repeat(3, 240px);
  justify-content: center;
  gap: 48px;
}

/* TABLET: 2 u redu (2 gore + 1 dolje) */
@media (max-width: 960px) {
  .menu-grid {
    grid-template-columns: repeat(2, 220px);
    gap: 32px;
  }
}

/* MOBITEL: 1 ispod druge */
@media (max-width: 600px) {
  .shop-menu {
    padding-top: 48px;
    padding-left: 16px;
    padding-right: 16px;
  }

  .menu-grid {
    grid-template-columns: 1fr;
    justify-items: center;
    gap: 24px;
  }
}
.cat-link {
  display: block;
  text-decoration: none;
}

.cat-card {
  width: 240px;
  height: 240px;
  background: white;
  overflow: hidden;

  display: flex;
  align-items: center;
  justify-content: center;

  padding: 24px;
}

.cat-img {
  width: 85%;
  height: 85%;
}
.cat-content {
  display: flex;
  flex-direction: column;
  align-items: center;
}

.cat-label {
  margin-top: 12px;
  font-size: 20px;
  color: #f8faf5;
  text-align: center;
  letter-spacing: 0.5px;
}

@media (max-width: 600px) {
  .cat-label {
    font-size: 16px;
  }
}
</style>
