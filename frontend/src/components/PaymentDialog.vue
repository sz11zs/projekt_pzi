<!-- src/components/PaymentDialog.vue -->
<template>
  <v-dialog v-model="model" max-width="760">
    <v-card class="pd-card" rounded="xl" elevation="0">
      <div class="pd-wrap">
        <div class="pd-title">NAČIN PLAĆANJA</div>

        <div class="pd-options">
          <div class="pd-options-inner">
            <!-- KARTICOM -->
            <label class="pd-option" @click="select('card')">
              <span class="pd-check" :class="{ active: selected === 'card' }" />
              <div class="pd-card-block">
                <div class="pd-label">KARTICOM</div>

                <div class="pd-cards" aria-hidden="true">
                  <span class="pd-chip">VISA</span>
                  <span class="pd-chip">AMEX</span>
                  <span class="pd-chip pd-chip-icon">
                    <v-icon icon="mdi-credit-card-outline" />
                  </span>
                </div>
              </div>
            </label>

            <!-- POUZEĆEM -->
            <label class="pd-option" @click="select('cod')">
              <span class="pd-check" :class="{ active: selected === 'cod' }" />
              <div class="pd-card-block">
                <div class="pd-label">POUZEĆEM</div>
              </div>
            </label>
          </div>
        </div>

        <div class="pd-actions">
          <v-btn class="pd-pay" rounded="pill" size="large" @click="confirm">
            PLAĆANJE
          </v-btn>

          <v-btn class="pd-back" rounded="pill" size="large" @click="model = false">
            NATRAG
          </v-btn>
        </div>
      </div>
    </v-card>
  </v-dialog>
</template>

<script setup lang="ts">
  import { computed, ref } from "vue";

  const props = defineProps<{ modelValue: boolean }>();
  const emit = defineEmits<{
    (e: "update:modelValue", v: boolean): void;
    (e: "select", method: "card" | "cod"): void;
  }>();

  const model = computed({
    get: () => props.modelValue,
    set: (v: boolean) => emit("update:modelValue", v),
  });

  const selected = ref<"card" | "cod" | null>(null);

  function select(m: "card" | "cod") {
    selected.value = m;
  }

  function confirm() {
    if (!selected.value) return;
    emit("select", selected.value);
  }
</script>

<style scoped>

.pd-card {
  background: #fff;
  max-width: 520px;
  width: 100%;
  margin: auto knowing;
}

.pd-card-block {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
}

.pd-cards {
  margin-top: 10px;
  display: flex;
  gap: 8px;
}

.pd-wrap {
  padding: 34px 46px 34px; /* pading teksta od kartice*/
  text-align: center;
}

.pd-title {
  text-align: center;
  color: #455a1f;
  font-size: 26px;
  margin-bottom: 22px;
}

.pd-options {
  display: flex;
  flex-direction: column;
  align-items: center; /* centrira unutarnji wrapper */
  margin-top: 10px;
  margin-bottom: 26px;
  width: 100%;
}

/* jedan red (checkbox + tekst) */
.pd-option {
  display: flex;
  align-items: flex-start;
  justify-content: flex-start;
  gap: 16px;
  margin-bottom: 24px;
  cursor: pointer;
  width: 100%;
}

.pd-right {
  display: grid;
  gap: 10px;
}

.pd-label {
  color: #455a1f;
  font-size: 20px;
  letter-spacing: 0.2px;
}

/* custom checkbox kao u figmi */
.pd-check {
  width: 22px;
  height: 22px;
  border: 2px solid #455a1f;
  border-radius: 3px;
  display: inline-block;
  position: relative;
}

.pd-check.active::after {
  content: "";
  position: absolute;
  left: 4px;
  top: 4px;
  width: 10px;
  height: 10px;
  background: #455a1f;
  border-radius: 2px;
}

/* mala polja VISA/AMEX kao u figmi */
.pd-icons {
  display: flex;
  gap: 10px;
  align-items: center;
  margin-left: 54px;
}

.pd-chip {
  border: 2px solid #455a1f;
  border-radius: 4px;
  height: 28px;
  padding: 0 8px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  color: #455a1f;
  font-weight: 700;
  font-size: 12px;
  letter-spacing: 0.4px;
}

.pd-chip-icon {
  width: 34px;
  padding: 0;
}

.pd-chip-icon :deep(.v-icon) {
  color: #455a1f;
}

/* buttoni */
.pd-actions {
 margin-top: 10px;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 14px;
}

.pd-pay,
.pd-back {
  width: 210px;
  font-weight: 800;
  box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
}

.pd-pay {
  background: #d8d48a;
  color: #455a1f;
}

.pd-back {
  background: #d8d48a;
  color: #455a1f;
}

/* responsive */
@media (max-width: 520px) {
  .pd-wrap {
    padding: 22px 18px 22px;
  }

  .pd-option {
    width: 100%;
  }

  .pd-icons {
    margin-left: 0;
  }

  .pd-actions {
    flex-direction: column;
  }

  .pd-pay,
  .pd-back {
    width: 100%;
    max-width: 360px;
  }
}
</style>
