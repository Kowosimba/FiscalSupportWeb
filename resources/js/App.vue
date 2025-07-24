<template>
  <div :class="['app-layout', { dark: isDark }]">
    <Topbar
      :user="user"
      :isDark="isDark"
      @toggleTheme="toggleTheme"
      @logout="logout"
    />
    <Sidebar />
    <main class="main-content">
      <router-view />
    </main>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import Topbar from './components/Topbar.vue'
import Sidebar from './components/Sidebar.vue'

const isDark = ref(false);
const user = {
  name: "John Doe",
  role: "admin"
};
function toggleTheme() {
  isDark.value = !isDark.value;
  document.documentElement.classList.toggle('dark-theme', isDark.value);
}
function logout() {
  // Call your logout endpoint
}
</script>

<style>
/* Dark Green Theme (light/dark switch) */
:root {
  --primary-green: #065f46;
  --primary-green-light: #047857;
  --primary-green-dark: #064e3b;
  --secondary-green: #10b981;
  --accent-green: #34d399;
  --sidebar-width: 260px;
  --navbar-height: 64px;
  --sidebar-bg: #fff;
  --sidebar-text: #374151;
  --topbar-bg: #fff;
  --main-bg: #f0fdf4;
  --main-text: #1f2937;
}
.dark-theme {
  --sidebar-bg: #064e3b;
  --sidebar-text: #d1fae5;
  --topbar-bg: #065f46;
  --main-bg: #022c22;
  --main-text: #d1fae5;
}
.app-layout {
  min-height: 100vh;
  background: var(--main-bg);
  color: var(--main-text);
}
.main-content {
  margin-left: var(--sidebar-width);
  padding: calc(var(--navbar-height) + 2rem) 2rem 2rem 2rem;
  min-height: calc(100vh - var(--navbar-height));
  transition: margin-left 0.3s;
}
</style>