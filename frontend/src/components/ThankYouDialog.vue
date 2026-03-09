<template>
  <v-dialog v-model="model" max-width="420">
    <v-card class="ty-card" rounded="xl" elevation="0">
      <div class="ty-wrap" @click="close">
        <div class="ty-title">HVALA NA KUPNJI!</div>
      </div>
    </v-card>
  </v-dialog>
</template>

<script setup lang="ts">
  import { computed } from "vue";
  import { useRouter } from "vue-router";

  const router = useRouter();
  const props = defineProps<{ modelValue: boolean }>();
  const emit = defineEmits<{
    (e: "update:modelValue", v: boolean): void;
  }>();

  const model = computed({
    get: () => props.modelValue,
    set: v => emit("update:modelValue", v),
  });

  function close() {
    model.value = false;
    router.push("/");
  }
</script>

<style scoped>
.ty-card {
  background: #455a1f;
}

.ty-wrap {
  padding: 40px 32px;
  display: grid;
  gap: 22px;
  justify-items: center;
  text-align: center;
}

.ty-title {
  color: #ffffff;
  font-size: 26px;
}

.ty-text {
  color: #ffffff;
  font-size: 16px;
  line-height: 1.4;
}

.ty-btn {
  width: 100%;
  max-width: 300px;
  background: #d8d48a;
  color: #455a1f;
  font-weight: 800;
}
</style>
