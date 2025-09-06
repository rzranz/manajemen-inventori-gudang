<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { Bar, Line } from 'vue-chartjs'
import {
  Chart as ChartJS,
  Title,
  Tooltip,
  Legend,
  BarElement,
  LineElement,
  CategoryScale,
  LinearScale,
  PointElement
} from 'chart.js'

ChartJS.register(Title, Tooltip, Legend, BarElement, LineElement, CategoryScale, LinearScale, PointElement)

const totalProduk = ref(0)
const totalStok = ref(0)
const totalTransaksi = ref(0)
const produkLabels = ref([])
const produkStok = ref([])
const transaksiLabels = ref([])
const transaksiCounts = ref([])
const lowStockProducts = ref([])

const fetchDashboardData = async () => {
  try {
    const produkRes = await axios.get('/api/produks')
    const transaksiRes = await axios.get('/api/transaksis')

    totalProduk.value = produkRes.data.data.length
    totalStok.value = produkRes.data.data.reduce((sum, p) => sum + p.stok, 0)
    totalTransaksi.value = transaksiRes.data.length

    produkLabels.value = produkRes.data.data.map(p => p.nama_produk)
    produkStok.value = produkRes.data.data.map(p => p.stok)

    lowStockProducts.value = produkRes.data.data.filter(p => p.stok < 10)

    const transaksiByDate = {}
    transaksiRes.data.forEach(tx => {
      const date = new Date(tx.created_at).toLocaleDateString('id-ID')
      transaksiByDate[date] = (transaksiByDate[date] || 0) + 1
    })

    transaksiLabels.value = Object.keys(transaksiByDate)
    transaksiCounts.value = Object.values(transaksiByDate)
  } catch (error) {
    console.error('Gagal memuat data dashboard:', error)
  }
}

onMounted(fetchDashboardData)

const barOptions = { responsive: true, plugins: { legend: { display: false } } }
const lineOptions = { responsive: true, plugins: { legend: { display: true, position: 'bottom' } } }
</script>

<template>
  <div class="min-h-screen transition-colors duration-300 bg-gray-100 dark:bg-gray-900 dark:text-gray-100 p-6">
    <h1 class="text-2xl font-bold mb-6">📊 Dashboard Inventori</h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
      <div class="shadow rounded-lg p-6 bg-white dark:bg-gray-800">
        <h2 class="text-gray-600 dark:text-gray-300">Total Produk</h2>
        <p class="text-3xl font-bold text-blue-700 dark:text-blue-400">{{ totalProduk }}</p>
      </div>
      <div class="shadow rounded-lg p-6 bg-white dark:bg-gray-800">
        <h2 class="text-gray-600 dark:text-gray-300">Total Stok</h2>
        <p class="text-3xl font-bold text-green-600 dark:text-green-400">{{ totalStok }}</p>
      </div>
      <div class="shadow rounded-lg p-6 bg-white dark:bg-gray-800">
        <h2 class="text-gray-600 dark:text-gray-300">Total Transaksi</h2>
        <p class="text-3xl font-bold text-purple-600 dark:text-purple-400">{{ totalTransaksi }}</p>
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
      <div class="shadow rounded-lg p-6 bg-white dark:bg-gray-800">
        <h2 class="text-lg font-semibold mb-4">📦 Stok Produk</h2>
        <Bar :data="{ labels: produkLabels, datasets: [{ data: produkStok, backgroundColor: '#3b82f6' }] }" :options="barOptions"/>
      </div>
      <div class="shadow rounded-lg p-6 bg-white dark:bg-gray-800">
        <h2 class="text-lg font-semibold mb-4">📈 Tren Transaksi</h2>
        <Line :data="{ labels: transaksiLabels, datasets: [{ label: 'Jumlah Transaksi', data: transaksiCounts, borderColor: '#8b5cf6', fill: false }] }" :options="lineOptions"/>
      </div>
    </div>

    <div class="shadow rounded-lg p-6 bg-white dark:bg-gray-800">
      <h2 class="text-lg font-semibold mb-4">⚠️ Produk dengan Stok Rendah</h2>
      <table class="w-full border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
        <thead class="bg-gray-50 dark:bg-gray-700">
          <tr>
            <th class="p-3 text-left">Produk</th>
            <th class="p-3 text-center">Stok</th>
          </tr>
        </thead>
        <tbody>
          <tr v-if="lowStockProducts.length === 0">
            <td colspan="2" class="p-4 text-center text-gray-500 dark:text-gray-400">Semua stok aman ✅</td>
          </tr>
          <tr v-for="prod in lowStockProducts" :key="prod.id" class="border-t dark:border-gray-700">
            <td class="p-3">{{ prod.nama_produk }}</td>
            <td class="p-3 text-center text-red-600 font-bold">{{ prod.stok }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
