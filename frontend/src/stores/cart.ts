import { defineStore } from "pinia";

export type CartItem = {
  productId: number | string;
  name: string;
  price: number;   // €/kg
  qtyKg: number;   // količina u kg
};

export const useCartStore = defineStore("cart", {
  state: () => ({
    items: [] as CartItem[],
  }),

  getters: {
    totalEur(state): number {
      return state.items.reduce(
        (sum, i) => sum + i.price * i.qtyKg,
        0
      );
    },
  },

  actions: {
    addItem(payload: CartItem) {
      const existing = this.items.find(
        i => i.productId === payload.productId
      );

      if (existing) {
        existing.qtyKg = Number(
          (existing.qtyKg + payload.qtyKg).toFixed(3)
        );
      } else {
        this.items.push({
          productId: payload.productId,
          name: payload.name,
          price: payload.price,
          qtyKg: payload.qtyKg,
        });
      }
    },

    removeItem(productId: number | string) {
      this.items = this.items.filter(
        i => i.productId !== productId
      );
    },

    clear() {
      this.items = [];
    },
  },
});

