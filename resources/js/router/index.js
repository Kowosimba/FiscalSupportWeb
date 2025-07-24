import { createRouter, createWebHistory } from 'vue-router'
// Import your page components as needed
const routes = [
  { path: '/dashboard', component: () => import('../views/Dashboard.vue') },
  // add routes for tickets, blogs, services, etc.
]
const router = createRouter({
  history: createWebHistory(),
  routes
})
export default router