<template>
  <div class="welcome-page">
    <v-container fluid class="welcome-container pa-0" >
      <!-- Slika + naslov gore -->
      <div class="top">
        <v-img :src="pocetnaSlika" max-width="520" class="mx-auto" contain />
        <div class="brand-title mt-2">Piljarnica kod Šime</div>
      </div>

      <!-- Zeleni panel -->
      <v-card class="panel" rounded="xl" elevation="0">
        <div class="panel-text">
          Drago nam je što koristite našu aplikaciju za kupnju. Za obavljanje kupnje potrebno je imati korisnički račun. Ako želite prvo istražiti ponudu, možete nastaviti kao gost.
          <br /><br />
          Uživajte u kupnji !!
        </div>

        <div class="panel-actions">
          <v-btn class="btn-guest" rounded="pill" variant="outlined" to="/trgovina">
            GOST
          </v-btn>
          <v-btn class="btn-register" rounded="pill" to="/registracija">
            REGISTRIRAJ SE
          </v-btn>
        </div>
      </v-card>
    </v-container>
  </div>
</template>

<script setup lang="ts">
  import pocetnaSlika from '@/assets/pocetnaSlika.png'
</script>

<style scoped>
/* Wrapper */
.welcome-page {
  background: #ffffff;
  min-height: 100vh;
}

/* Centriranje sadržaja */
.welcome-container {
  height: 100vh;
  display: flex;
  flex-direction: column;
  justify-content: flex-start;
  align-items: center;
  padding: 60px 16px 0px;
}

/* Gornji dio (slika + naslov) */
.top {
  position: relative;
  text-align: center;
  margin-bottom: 8px; /* panel bliže naslovu */
}
.brand-title {
  color: #455a1f;
  font-family: "Architects Daughter", cursive;
  font-size: 54px;
  font-weight: 500;
  letter-spacing: 0.4px;
  line-height: 1.05;

  margin-top: -44px;     /* overlap preko donjeg dijela slike */
  position: relative;
  z-index: 2;
}

/* Zeleni panel */
.panel {
  margin-top: auto;
  width: 100vw;
  height: 60vh;

  background: #455a1f !important;
  border-radius: 360px 360px 0 0 !important;

  padding: 80px 56px;

  display: flex;
  flex-direction: column;
  justify-content: center;   /* vertikalno centriranje */
  align-items: center;       /* horizontalno centriranje */
  text-align: center;
}

/* Tekst u panelu */
.panel-text {
  margin-top: 20px;
  color: #ffffff;
  font-size: 22px;
  line-height: 1.35;
  opacity: 0.95;
  max-width: 900px;
}

/* Akcije */
.panel-actions {
  margin-top: 100px;
  display: flex;
  justify-content:center;
  align-items: center;
  gap: 500px;
  padding: 0 32px;
}

.btn-guest {
  min-width: 240px;
  height: 54px;
  border-radius: 999px !important;
  border: 2px solid rgba(255, 255, 255, 0.65) !important;
  color: #ffffff !important;
  font-weight: 800;
  letter-spacing: 0.6px;
  background: transparent !important;
}

.btn-register {
  min-width: 320px;
  height: 54px;
  border-radius: 999px !important;
  background: #d8d48a !important;
  color: #455a1f !important;
  font-weight: 800;
  letter-spacing: 0.6px;
  box-shadow: 0 10px 22px rgba(0, 0, 0, 0.22) !important;
}

:deep(.v-btn) {
  text-transform: none;
}

/* Mobile */
@media (max-width: 600px) {
  .welcome-container {
    padding: 22px 12px;
  }

  .brand-title {
    font-size: 34px;
  }

  .panel {
    padding: 22px 18px;
    border-radius: 50px !important;
  }

  .panel-text {
    font-size: 16px;
    line-height: 1.45;
  }

  .panel-actions {
    margin-top: 18px;
    flex-direction: column;
    gap: 12px;
  }

  .btn-guest,
  .btn-register {
    width: 100%;
    min-width: 0;
  }
}
</style>
