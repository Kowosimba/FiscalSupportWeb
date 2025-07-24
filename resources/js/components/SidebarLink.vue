
<template>
  <router-link :to="to" class="nav-link" active-class="active">
    <i v-if="icon" :class="`fas fa-${icon}`"></i>
    <span>{{ label }}</span>
  </router-link>
</template>
<script setup>
defineProps({
  to: String,
  label: String,
  icon: String
})
</script>
<style scoped>
.nav-link {
  display: flex;
  align-items: center;
  gap: 1rem;
  color: var(--sidebar-text);
  padding: 0.75rem 2rem;
  border-radius: 12px;
  margin: 0.15rem 1rem;
  font-weight: 500;
  transition: background 0.3s;
}
.nav-link.active,
.nav-link:hover {
  background: var(--primary-green);
  color: var(--white) !important;
}
.nav-link i {
  font-size: 1.2rem;
  min-width: 24px;
}
</style>