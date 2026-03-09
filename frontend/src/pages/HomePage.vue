<template>
  <div>
    <v-navigation-drawer v-model="drawer">
      <v-list class="d-flex flex-column fill-height" nav>
        <div class="pt-16">
          <v-list-item
            title="O aplikaciji"
            prepend-icon="mdi-information-outline"
            to="/about" style="color: #455a1f"
          />
          <v-list-item
            title="Registracija/Prijava"
            prepend-icon="mdi-account"
            to="/registracija" style="color: #455a1f"
          />
          <v-list-item
            title="Trgovina"
            prepend-icon="mdi-storefront-outline"
            to="/trgovina" style="color: #455a1f"
          />
        </div>

        <v-spacer />

        <v-divider />
        <v-list-item
          title="Dashboard"
          prepend-icon="mdi-view-dashboard-outline"
          to="/adminlogin" style="color: #455a1f"
        />
      </v-list>
    </v-navigation-drawer>

    <v-app-bar>
      <v-app-bar-nav-icon color="#455A1F" @click="drawer = !drawer" />
      <v-app-bar-title>
        <span class="navbar-title">Piljarnica kod Šime</span>
      </v-app-bar-title>
    </v-app-bar>

    <v-container class="d-flex justify-center pt-16">
      <v-img :src="pocetnaSlika" max-width="640" contain style="cursor:pointer"
    @click="$router.push('/trgovina')"/>
    </v-container>

    <div class="piljarnica-title mt-n1">Piljarnica kod Šime</div>
  </div>
</template>

<script setup lang="ts">
  import { ref, onMounted, onBeforeUnmount } from "vue";
  import { useRouter } from "vue-router";
  import pocetnaSlika from "@/assets/pocetnaSlika.png";

  const drawer = ref(false);

  const router = useRouter();
  let timer: number | undefined;

  onMounted(() => {
    // Provjeravamo ima li korisnik token (je li prijavljen)
    const token = localStorage.getItem("token");

    // Ako NEMA tokena, tek onda pokrećemo odbrojavanje od 5 sekundi
    if (!token) {
      timer = window.setTimeout(() => {
        router.push("/welcome");
      }, 5000);
    } else {
      console.log("Korisnik je prijavljen, preskačem welcome stranicu.");
    }
  });

  onBeforeUnmount(() => {
    if (timer) window.clearTimeout(timer);
  });
</script>

<style scoped>
.piljarnica-title {
  font-family: "Architects Daughter", cursive;
  color: #455a1f;
  line-height: 1;
  text-align: center;
  font-size: 40px;
}

@media (min-width: 600px) {
  .piljarnica-title {
    font-size: 64px;
  }
}

@media (min-width: 1264px) {
  .piljarnica-title {
    font-size: 96px;
  }
}

.navbar-title {
  font-family: "Architects Daughter", cursive;
  color: #455a1f;
  font-size: 14px;
  line-height: 1;
}

@media (min-width: 600px) {
  .navbar-title {
    font-size: 16px;
  }
}

@media (min-width: 1264px) {
  .navbar-title {
    font-size: 18px;
  }
}
</style>
