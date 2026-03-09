<template>
  <div class="page">
    <img class="illustration" :src="imageSrc" alt="" />
    <v-card class="panel" elevation="0" rounded="xl">
      <div class="panel-header">
        <div class="panel-title">{{ title }}</div>
      </div>
      <div class="panel-body">
        <slot />
      </div>
    </v-card>
  </div>
</template>

<script setup lang="ts">
  import { useRouter } from 'vue-router'

  const props = defineProps<{
    title: string
    imageSrc: string
    closeTo?: string
  }>()

  const router = useRouter()

  function close () {
    router.push(props.closeTo ?? '/')
  }
</script>

<style scoped>
.page {
  min-height: 100vh;
  background: #455a1f;
  position: relative;
  overflow: hidden;
}

.illustration {
  position: absolute;
  left: 0;
  bottom: -0.5;
  height: 120%;
  max-width: 120%;
  object-fit: contain;
  pointer-events: none;
}

.panel {
  position: absolute;
  right: 20%;
  top: 55%;
  transform: translateY(-50%);
  width: 520px;
  min-height: 460px;
  padding: 32px 40px;
  background: #ffffff;
}

.panel-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 12px;
  margin-bottom: 12px;
}

.panel-title {
  font-size: 22px;
  font-weight: 600;
  color: #455a1f;
}

.panel-footer {
  display: flex;
  justify-content: flex-end;
  margin-top: 16px;
}

@media (max-width: 900px) {
  .panel {
    left: 50%;
    right: auto;
    top: 55%;
    transform: translate(-50%, -50%);
    width: 92%;
    max-width: 420px;
    min-height: auto;
    padding: 24px 20px;
  }
}
</style>
