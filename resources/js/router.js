import { createRouter, createWebHistory } from 'vue-router'
import ProductList from './components/ProductList.vue'
import TransaksiList from './components/TransaksiList.vue'
import Dashboard from './components/Dashboard.vue'

const routes = [
  { path: '/', name: 'Dashboard', component: Dashboard },
  { path: '/produk', name: 'Produk', component: ProductList },
  { path: '/transaksi', name: 'Transaksi', component: TransaksiList },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

export default router
