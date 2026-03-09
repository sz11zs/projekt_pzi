<template>
  <v-btn icon variant="text"style="position:absolute; top:20px; left:20px"@click="goHome">
    <v-icon size="36" color="white">mdi-arrow-left</v-icon>
  </v-btn>
  <div class="admin-login-container">
    <v-card class="login-card" rounded="xl" elevation="10">
      <div class="text-center mb-6">
        <div class="d-flex align-center justify-center mb-2">
          <v-icon size="40" color="#455a1f" class="mr-2">mdi-view-dashboard-outline</v-icon>
          <h1 class="brand-title">Piljarnica NOVA</h1>
        </div>
      </div>

      <v-form @submit.prevent="handleLogin">
        <v-text-field
          v-model="name"
          label="Korisničko ime"
          prepend-inner-icon="mdi-account"
          variant="outlined"
          color="#455a1f"
          rounded="lg"
          required
        ></v-text-field>

        <v-text-field
          v-model="password"
          label="Lozinka"
          prepend-inner-icon="mdi-lock"
          type="password"
          variant="outlined"
          color="#455a1f"
          rounded="lg"
          required
          class="mt-2"
        ></v-text-field>

        <v-btn
          type="submit"
          block
          size="x-large"
          color="#455a1f"
          class="mt-6 login-btn"
          rounded="pill"
          :loading="loading"
        >
          PRIJAVI SE
        </v-btn>
      </v-form>

      <v-alert
        v-if="errorMsg"
        type="error"
        variant="tonal"
        class="mt-4"
        closable
      >
        {{ errorMsg }}
      </v-alert>
    </v-card>
  </div>
</template>

<script setup lang="ts">
  import { ref } from "vue";
  import { useRouter } from "vue-router";

  const name = ref("");
  const password = ref("");
  const loading = ref(false);
  const errorMsg = ref("");
  const router = useRouter();

  function goHome() {
    router.push("/");
  }
  async function handleLogin() {
    loading.value = true;
    errorMsg.value = "";

    try {
      const res = await fetch("http://127.0.0.1:8000/api/login", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ name: name.value, password: password.value }),
      });

      const data = await res.json();

      if (res.ok) {
        // PROVJERA ULOGE (3 = Admin, 4 = Super Admin)
        if (data.user.role_id === 3 || data.user.role_id === 4) {
          localStorage.setItem("token", data.token);
          localStorage.setItem("user", JSON.stringify(data.user));

          // Uspješna prijava -> idi na dashboard
          router.push("/dashboard");
        } else {
          errorMsg.value = "Pristup odbijen. Nemate administratorske ovlasti!";
        }
      } else {
        errorMsg.value = data.message || "Neispravno ime ili lozinka.";
      }
    } catch (e) {
      errorMsg.value = "Greška u komunikaciji s poslužiteljem.";
    } finally {
      loading.value = false;
    }
  }
</script>

<style scoped>
.admin-login-container {
  height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: #455a1f;
}

.login-card {
  width: 100%;
  max-width: 450px;
  padding: 40px;
  background: white;
}

.brand-title {
  font-family: "Architects Daughter", cursive;
  color: #455a1f;
  font-size: 32px;
}

.subtitle {
  color: #666;
  letter-spacing: 2px;
  font-size: 14px;
}

.login-btn {
  color: white !important;
  font-size: 18px;
}
</style>
