<template>
  <v-dialog v-model="model" max-width="420">
    <v-card class="da-card" rounded="xl" elevation="0">
      <div class="da-wrap">
        <div class="da-title">DOSTAVA</div>

        <div class="da-form">
          <v-text-field
            v-model="street"
            variant="underlined"
            hide-details
            label="Ulica i kućni broj"
            class="da-field"
          />

          <v-text-field
            v-model="phone"
            variant="underlined"
            hide-details
            label="Kontakt telefon"
            class="da-field"
          />
        </div>

        <v-btn class="da-confirm" rounded="pill" size="large" @click="confirm">
          POTVRDI
        </v-btn>
      </div>
    </v-card>
  </v-dialog>
</template>

<script setup lang="ts">
  import { computed, ref } from "vue";

  const props = defineProps<{ modelValue: boolean }>();
  const emit = defineEmits<{
    (e: "update:modelValue", v: boolean): void;
    (e: "confirm", payload: { street: string; city: string; phone: string }): void;
  }>();

  const model = computed({
    get: () => props.modelValue,
    set: v => emit("update:modelValue", v),
  });

  const street = ref("");
  const city = ref("");
  const phone = ref("");

  function confirm() {
    emit("confirm", {
      street: street.value.trim(),
      city: city.value.trim(),
      phone: phone.value.trim(),
    });
  }
</script>

<style scoped>
.da-wrap {
  padding: 34px;
  display: grid;
  gap: 22px;
  justify-items: center;
}

.da-title {
  color: #455a1f;
  font-size: 22px;
}

.da-form {
  width: 100%;
  display: grid;
  gap: 18px;
}

.da-field :deep(.v-field__input),
.da-field :deep(input) {
  color: #455a1f;
}

.da-confirm {
  width: 100%;
  max-width: 320px;
  background: #d8d48a;
  color: #455a1f;
  font-weight: 800;
}
</style>
