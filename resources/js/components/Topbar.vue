<template>
  <header class="navbar">
    <div class="d-flex align-items-center">
      <span class="navbar-brand">Unified Dashboard</span>
    </div>
    <div class="d-flex align-items-center gap-3">
      <NotificationDropdown />
      <div class="dropdown">
        <button class="btn user-btn dropdown-toggle" data-bs-toggle="dropdown">
          <span class="user-avatar"><i class="fas fa-user-circle"></i></span>
          <span class="d-none d-md-inline">
            <span class="greeting-container">
              <i :class="`fas ${greetingIcon}`" :style="{ color: greetingColor }"></i>
              <span>{{ greeting }}</span>
            </span>
            <span>{{ user.name }}</span>
          </span>
        </button>
        <ul class="dropdown-menu dropdown-menu-end">
          <li><button class="dropdown-item"><i class="fas fa-user-circle fa-fw"></i> Profile</button></li>
          <li><button class="dropdown-item" @click="$emit('logout')"><i class="fas fa-sign-out-alt fa-fw"></i> Logout</button></li>
          <li><hr class="dropdown-divider"></li>
          <li><button class="dropdown-item" @click="$emit('toggleTheme')"><i class="fas fa-adjust fa-fw"></i> Theme</button></li>
        </ul>
      </div>
    </div>
  </header>
</template>
<script setup>
import { computed } from 'vue'
import NotificationDropdown from './NotificationDropdown.vue'
const props = defineProps({ user: Object, isDark: Boolean })
const hour = new Date().getHours()
const greeting = computed(() => {
  if (hour < 10) return 'Good morning'
  if (hour < 16) return 'Good afternoon'
  return 'Good evening'
})
const greetingIcon = computed(() => {
  if (hour < 10) return 'fa-sun'
  if (hour < 16) return 'fa-cloud-sun'
  return 'fa-moon'
})
const greetingColor = computed(() => {
  if (hour < 10) return '#F59E0B'
  if (hour < 16) return '#10B981'
  return '#6366F1'
})
</script>
<style scoped>
.navbar {
  height: var(--navbar-height);
  background: var(--topbar-bg);
  box-shadow: 0 2px 8px rgba(0,0,0,0.06);
  border-bottom: 1px solid #e5e7eb;
  padding: 0 2rem;
  z-index: 1030;
  display: flex;
  align-items: center;
  justify-content: space-between;
}
.navbar-brand {
  font-size: 1.5rem;
  font-weight: 700;
  color: var(--primary-green) !important;
}
.user-avatar {
  width: 36px;
  height: 36px;
  border-radius: 50%;
  background: linear-gradient(135deg, var(--primary-green), var(--primary-green-light));
  color: var(--white);
  display: inline-flex;
  align-items: center;
  justify-content: center;
  font-size: 1.25rem;
}
.user-btn { background: none; border: none; }
.greeting-container { display: flex; align-items: center; gap: 0.5rem; }
</style>