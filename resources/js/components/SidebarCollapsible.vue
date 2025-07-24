<template>
  <div class="nav-item">
    <div class="nav-link" @click="toggle">
      <i :class="`fas fa-${icon}`"></i>
      <span>{{ label }}</span>
      <i class="fas fa-chevron-down ms-auto"></i>
    </div>
    <div v-show="open" class="collapse-menu">
      <slot />
    </div>
  </div>
</template>
<script setup>
import { ref } from 'vue'
const props = defineProps({ icon: String, label: String })
const open = ref(false)
function toggle() { open.value = !open.value }
</script>
<style scoped>
.collapse-menu { padding-left: 2rem; }
</style>