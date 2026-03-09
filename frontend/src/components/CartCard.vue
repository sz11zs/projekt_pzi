<template>
  <v-dialog v-model="model" max-width="760">
    <v-card rounded="xl" class="cd-card" elevation="0">
      <div class="cd-wrap">
        <div class="cd-title">KOŠARICA</div>

        <v-table class="cd-table" density="compact">
          <thead>
            <tr>
              <th>ARTIKAL:</th>
              <th class="text-center">KOLIČINA (kg):</th>
              <th class="text-center">CIJENA (€ / kg):</th>
              <th class="text-right">IZNOS (€):</th>
              <th></th>
            </tr>
          </thead>

          <tbody>
            <tr v-if="cart.items.length === 0">
              <td colspan="5" class="cd-empty">Košarica je prazna.</td>
            </tr>

            <tr v-for="i in cart.items" :key="i.productId">
              <td class="cd-name" data-label="ARTIKAL:">
                {{ i.name }}
              </td>

              <td class="text-center" data-label="KOLIČINA:">
                {{ formatQty(i.qtyKg) }}
              </td>

              <td class="text-center" data-label="CIJENA:">
                {{ formatEur(i.price) }}
              </td>

              <td class="text-right" data-label="IZNOS:">
                {{ formatEur(i.price * i.qtyKg) }}
              </td>

              <td class="cd-remove-cell">
                <v-btn
                  icon="mdi-close"
                  variant="text"
                  size="small"
                  @click="cart.removeItem(i.productId)"
                />
              </td>
            </tr>
          </tbody>
        </v-table>

        <div class="cd-totalRow">
          <span>UKUPNO:</span>
          <strong>{{ formatEur(cart.totalEur) }} €</strong>
        </div>

        <div class="cd-actions">
          <v-btn class="cd-pay" rounded="pill" size="large" @click="emit('checkout')">
            PLAĆANJE
          </v-btn>

          <v-btn class="cd-continue" rounded="pill" size="large" @click="model = false">
            NASTAVI KUPOVINU
          </v-btn>
        </div>
      </div>
    </v-card>
  </v-dialog>
</template>

<script setup lang="ts">
  import { computed } from "vue";
  import { useCartStore } from "@/stores/cart";

  const cart = useCartStore();

  const props = defineProps<{ modelValue: boolean }>();
  const emit = defineEmits<{
    (e: "update:modelValue", v: boolean): void;
    (e: "checkout"): void;
  }>();

  const model = computed({
    get: () => props.modelValue,
    set: (v: boolean) => emit("update:modelValue", v),
  });

  function formatEur(n: number) {
    return Number(n ?? 0).toFixed(2).replace(".", ",");
  }

  function formatQty(n: number) {
    return Number(n ?? 0).toFixed(2).replace(".", ",");
  }
</script>

<style scoped>
.cd-card {
  background: #ffffff;
}

.cd-wrap {
  padding: 28px 34px;
}

.cd-title {
  color: #455a1f;
  font-size: 18px;
  margin-bottom: 14px;
  letter-spacing: 0.5px;
}

.cd-table :deep(th) {
  color: #455a1f;
  font-size: 12px;
  font-weight: 700;
  text-transform: uppercase;
  opacity: 0.9;
}

.cd-table :deep(td) {
  font-size: 14px;
  color: #455A1F;
}

.cd-name {
  color: #455a1f;
  font-weight: 600;
}

.cd-empty {
  padding: 18px 0;
  text-align: center;
  color: #455a1f;
  opacity: 0.8;
}

.cd-totalRow {
  margin-top: 14px;
  display: flex;
  justify-content: flex-end;
  gap: 14px;
  align-items: baseline;
  color: #455a1f;
}

.cd-totalValue {
  color: #455a1f;
  font-weight: 800;
  font-size: 16px;
}

.cd-actions {
  margin-top: 18px;
  display: flex;
  justify-content: flex-end;
  gap: 12px;
}

.cd-pay,
.cd-continue {
  background: #d8d48a;
  color: #455a1f;
  font-weight: 800;
  box-shadow: 0 6px 16px rgba(0,0,0,0.12);
}
.cd-totalLabel {
  color: #455a1f;
  font-weight: 700;
  text-transform: uppercase;
  font-size: 12px;
}


/* RESPONSIVE DIZAJN ZA MOBITELE */
@media (max-width: 600px) {
  .cd-wrap {
    padding: 20px 15px;
  }

  /* Sakrij zaglavlje tablice */
  .cd-table :deep(thead) {
    display: none;
  }

  /* Redovi postaju kartice */
  .cd-table :deep(tr) {
    display: block;
    border-bottom: 2px solid #e9eef6 !important;
    padding: 12px 0;
    position: relative;
  }

  /* Ćelije se slažu jedna ispod druge s labelom na lijevoj strani */
  .cd-table :deep(td) {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border: none !important;
    padding: 6px 0 !important;
    text-align: right !important;
    font-size: 14px;
    width: 100%;
  }

  /* Ubacivanje teksta iz data-label atributa */
  .cd-table :deep(td::before) {
    content: attr(data-label);
    font-weight: 700;
    font-size: 10px;
    text-transform: uppercase;
    color: #455a1f;
    opacity: 0.7;
  }
  .cd-name {
    font-size: 16px !important;
    margin-bottom: 4px;
    font-weight: 800;
  }
  .cd-name::before {
    display: none !important;
  }

  /* Pozicioniranje gumba za brisanje u kut */
  .cd-remove-cell {
    position: absolute;
    top: 5px;
    right: -10px;
    width: auto !important;
  }
  .cd-remove-cell::before {
    display: none !important;
  }

  .cd-totalRow {
    justify-content: center;
    font-size: 20px;
    margin: 20px 0;
    border-top: 2px solid #455a1f;
    padding-top: 10px;
  }

  /* Gumbi zauzimaju punu širinu */
  .cd-actions {
    flex-direction: column;
    gap: 10px;
  }

  .cd-pay, .cd-continue {
    width: 100%;
    margin: 0 !important;
  }
}
</style>
