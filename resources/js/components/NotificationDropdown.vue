<template>
  <div class="notification-wrapper position-relative">
    <button class="btn notification-link" @click="toggleDropdown">
      <i class="fas fa-bell"></i>
      <span v-if="count > 0" class="notification-badge">{{ count }}</span>
    </button>
    <div class="notification-dropdown-menu position-absolute end-0 mt-2 bg-white rounded-3 border shadow-lg" v-show="open" style="width: 400px; max-height: 500px;">
      <div class="notification-header d-flex justify-content-between align-items-center p-3 border-bottom">
        <h6 class="mb-0 fw-bold">Recent Notifications</h6>
        <div class="notification-actions">
          <button class="btn btn-sm btn-outline-primary me-2" @click="markAllRead">
            <i class="fas fa-check-double me-1"></i>
            Mark All Read
          </button>
          <router-link to="/notifications" class="btn btn-sm btn-primary">
            <i class="fas fa-list me-1"></i>
            View All
          </router-link>
        </div>
      </div>
      <div class="notification-list" style="max-height: 400px; overflow-y: auto;">
        <div v-if="loading" class="text-center p-4">
          <div class="spinner-border text-primary"></div>
        </div>
        <div v-if="!loading && notifications.length === 0" class="no-notifications">
          <i class="fas fa-bell-slash"></i>
          <div>No new notifications</div>
        </div>
        <div v-for="n in notifications" :key="n.id" class="notification-item" @click="goTo(n.url)">
          <div>
            <div class="notification-title">{{ n.title }}</div>
            <div class="notification-message">{{ n.message }}</div>
            <div class="notification-meta">{{ n.created_at }}</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
const open = ref(false)
const count = ref(0)
const notifications = ref([])
const loading = ref(false)
const router = useRouter()
function toggleDropdown() {
  open.value = !open.value
  if (open.value) loadRecentNotifications()
}
function goTo(url) { router.push(url) }
function loadNotificationCount() {
  fetch('/notifications/api/unread-count')
    .then(res => res.json())
    .then(data => { count.value = data.count })
}
function loadRecentNotifications() {
  loading.value = true
  fetch('/notifications/api/recent')
    .then(res => res.json())
    .then(data => { notifications.value = data; loading.value = false })
}
function markAllRead() {
  fetch('/notifications/read-all', { method: 'POST', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content } })
    .then(() => { loadNotificationCount(); loadRecentNotifications() })
}
onMounted(() => {
  loadNotificationCount()
  setInterval(loadNotificationCount, 30000)
})
</script>
<style scoped>
.notification-link {
  position: relative;
  padding: 0.5rem;
  border-radius: 12px;
  background: var(--ultra-light-green);
  border: 1px solid #e5e7eb;
  color: var(--primary-green);
}
.notification-badge {
  position: absolute;
  top: 5px;
  right: 5px;
  background: #dc2626;
  color: #fff;
  font-size: 0.8rem;
  padding: 0.2rem 0.6rem;
  border-radius: 50px;
}
.notification-dropdown-menu { z-index: 1050; }
.notification-item { padding: 1rem; border-bottom: 1px solid #f8f9fa; cursor: pointer; }
.notification-item:hover { background-color: #f8f9fa; }
.no-notifications { text-align: center; padding: 2rem; color: #6c757d; }
.no-notifications i { font-size: 2rem; margin-bottom: 1rem; opacity: 0.5; }
</style>