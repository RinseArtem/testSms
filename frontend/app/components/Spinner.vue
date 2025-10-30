<!-- components/Spinner.vue -->
<template>
  <div class="spinner-wrap" :class="{ overlay }" role="status" aria-live="polite" aria-busy="true">
    <div class="spinner" :style="{ width: size + 'px', height: size + 'px', borderWidth: stroke + 'px', borderTopColor: color }" />
    <span class="sr-only">{{ label }}</span>
  </div>
</template>

<script setup lang="ts">
defineProps<{
  size?: number
  stroke?: number
  color?: string
  overlay?: boolean
  label?: string
}>()
</script>

<style scoped>
.spinner-wrap {
  display: inline-flex;
  align-items: center;
  justify-content: center;
}
.spinner-wrap.overlay {
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,.06);
  backdrop-filter: blur(2px);
  z-index: 9999;
}
.spinner {
  border-radius: 50%;
  border-style: solid;
  border-color: rgba(0,0,0,.15);
  border-top-color: #42b883;
  animation: spin 0.9s linear infinite;
}
@keyframes spin {
  0%   { transform: rotate(0deg);   }
  100% { transform: rotate(360deg); }
}

.sr-only {
  position: absolute;
  width: 1px; height: 1px;
  padding: 0; margin: -1px; overflow: hidden; clip: rect(0,0,0,0);
  border: 0; white-space: nowrap;
}
</style>
