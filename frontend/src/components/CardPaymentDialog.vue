<template>
  <v-dialog v-model="model" max-width="420">
    <v-card class="cp-card" rounded="xl" elevation="0">
      <div class="cp-wrap">
        <div class="cp-title">KARTICOM</div>

        <div class="cp-form">
          <v-text-field v-model="fullName" variant="underlined" hide-details label="ime i prezime" prepend-inner-icon="mdi-account-outline" class="cp-field"/>
          <v-text-field v-model="cardNumber" maxlength="16" variant="underlined" hide-details label="broj kartice"  prepend-inner-icon="mdi-credit-card-outline" class="cp-field" />
          <v-text-field v-model="exp" maxlength="5" variant="underlined" hide-details label="MM/GG" prepend-inner-icon="mdi-calendar-month-outline" class="cp-field" />
          <div v-if="error" class="cp-error">{{ error }}</div>
        </div>

        <v-btn class="cp-buy" rounded="pill" size="large" @click="onBuy">
          KUPI
        </v-btn>
      </div>
    </v-card>
  </v-dialog>
</template>

<script setup lang="ts">
  import { computed, ref } from "vue";

  const error = ref("");
  const props = defineProps<{ modelValue: boolean }>();
  const emit = defineEmits<{
    (e: "update:modelValue", v: boolean): void;
    (e: "buy", payload: { fullName: string; cardNumber: string; exp: string }): void;
  }>();

  const model = computed({
    get: () => props.modelValue,
    set: (v: boolean) => emit("update:modelValue", v),
  });

  const fullName = ref("");
  const cardNumber = ref("");
  const exp = ref("");

  function onBuy() {
    error.value = "";

    const card = cardNumber.value.replaceAll(" ", "");
    const expVal = exp.value.replace("/", "");

    if (card.length !== 16 || isNaN(Number(card))) {
      error.value = "Broj kartice mora imati točno 16 znamenki.";
      return;
    }
    if (expVal.length !== 4 || isNaN(Number(expVal))) {
      error.value = "Datum mora biti u formatu MM/GG .";
      return;
    }

    emit("buy", {
      fullName: fullName.value.trim(),
      cardNumber: card,
      exp: expVal,
    });
  }
</script>

<style scoped>
.cp-error {
  margin-top: 8px;
  color: #c62828;
  font-size: 13px;
  text-align: center;
}

.cp-card {
  background: #fff;
}

.cp-wrap {
  padding: 34px 34px 28px;
  display: grid;
  gap: 22px;
  justify-items: center;
  text-align: center;
}

.cp-title {
  color: #455a1f;
  font-size: 22px;
  letter-spacing: 0.5px;
}

.cp-form {
  width: 100%;
  display: grid;
  gap: 18px;
}

.cp-field :deep(.v-field__input),
.cp-field :deep(input) {
  color: #455a1f;
}

.cp-field :deep(.v-label) {
  color: rgba(69, 90, 31, 0.6);
}

.cp-buy {
  width: 100%;
  max-width: 320px;
  background: #d8d48a;
  color: #455a1f;
  font-weight: 800;
  box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
}
</style>
