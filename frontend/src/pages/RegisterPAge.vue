<template>
  <GreenImagePanelLayout title="Registracija" :image-src="pocetnaSlika" close-to="/">
    <v-form @submit.prevent="handleSubmit">
      <v-text-field
        v-model="name"
        label="unesi ime"
        variant="underlined"
        prepend-inner-icon="mdi-account-outline"
        class="auth-field"
      />

      <v-text-field
        v-if="!isLogin"
        v-model="email"
        label="unesi e-mail"
        variant="underlined"
        prepend-inner-icon="mdi-email-outline"
        class="auth-field mt-4"
      />

      <v-text-field
        v-model="password"
        label="unesi lozinku"
        type="password"
        variant="underlined"
        prepend-inner-icon="mdi-lock-outline"
        class="auth-field mt-4"
      />

      <div class="mt-10 d-flex  flex-column align-center">
        <v-btn class="auth-btn" rounded="pill" size="large" type="submit">
          {{ isLogin ? 'PRIJAVI SE' : 'REGISTRIRAJ SE' }}
        </v-btn>

        <p class="mt-4 toggle-text" @click="isLogin = !isLogin">
          {{ isLogin ? 'Nemaš račun? Registriraj se' : 'Imaš račun? PRIJAVI SE' }}
        </p>

      </div>
    </v-form>
  </GreenImagePanelLayout>
</template>

<script setup lang="ts">
  import { ref } from 'vue'
  import GreenImagePanelLayout from '@/components/GreenImagePanelLayout.vue'
  import pocetnaSlika from '@/assets/pocetnaSlika.png'

  const isLogin = ref(false) // Kontrolira jesmo li na prijavi ili registraciji
  const name = ref('')
  const email = ref('')
  const password = ref('')

  async function handleSubmit () {
    // Određujemo URL i body ovisno o stanju isLogin
    const url = isLogin.value
      ? "http://127.0.0.1:8000/api/login"
      : "http://127.0.0.1:8000/api/register";

    const payload = isLogin.value
      ? { name: name.value, password: password.value } // Login šalje samo ime i pass
      : { name: name.value, email: email.value, password: password.value, role_id: 2 }; // Register šalje sve

    const res = await fetch(url, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(payload),
    });

    if (!res.ok) {
      const err = await res.json().catch(() => ({}));
      console.error("AUTH ERROR:", err);
      alert(isLogin.value ? "Prijava neuspješna!" : "Registracija neuspješna!");
      return;
    }

    const data = await res.json();

    localStorage.setItem("token", data.token);
    localStorage.setItem("user", JSON.stringify(data.user));

    // opcionalno: vrati na home
    window.location.href = "/";
  }
</script>

<style scoped>
.auth-field :deep(.v-icon),
.auth-field :deep(.v-label),
.auth-field :deep(.v-field__underline) {
  color: #455a1f;
}

.toggle-text {
  color: #455a1f;
  font-size: 14px;
  cursor: pointer;
  font-weight: 500;
  transition: opacity 0.2s;
}

.toggle-text:hover {
  opacity: 0.8;
}

.auth-btn {
  background: #d8d48a;
  color: #455a1f;
  font-weight: 600;
  letter-spacing: 0.5px;
}
</style>
