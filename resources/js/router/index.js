import { createRouter, createWebHistory } from 'vue-router'
import ProductList from '../components/ProductList.vue'
import TransaksiList from '../components/TransaksiList.vue'

const routes = [
  { path: '/', redirect: '/dashboard' },
  { path: '/dashboard', component: ProductList, name: 'Dashboard' },
  { path: '/produk', component: ProductList, name: 'Produk' },
  { path: '/transaksi', component: TransaksiList, name: 'Transaksi' },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

export default router
