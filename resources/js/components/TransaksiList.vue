<script setup>
import { ref, onMounted, computed } from 'vue'
import axios from 'axios'

// State
const allTransactions = ref([])
const allProducts = ref([])
const loading = ref(true)
const searchQuery = ref('')

// Form state
const form = ref({ produk_id: '', tipe: 'masuk', jumlah: 1, catatan: '' })
const saving = ref(false)

// Toast state
const toast = ref({ show: false, message: '', type: 'success' })

const showToast = (message, type = 'success') => {
  toast.value = { show: true, message, type }
  setTimeout(() => { toast.value.show = false }, 3000)
}

const fetchTransactions = async () => {
  loading.value = true
  try {
    const response = await axios.get('/api/transaksis')
    allTransactions.value = response.data
  } catch (error) {
    console.error('Gagal mengambil data transaksi:', error)
    showToast('Gagal memuat transaksi', 'error')
  } finally {
    loading.value = false
  }
}

const fetchProducts = async () => {
  try {
    const response = await axios.get('/api/produks')
    allProducts.value = response.data.data
  } catch (error) {
    console.error('Gagal mengambil data produk:', error)
    showToast('Gagal memuat produk', 'error')
  }
}

const submitTransaction = async () => {
  saving.value = true
  try {
    await axios.post('/api/transaksis', form.value)
    showToast('Transaksi berhasil disimpan!', 'success')
    form.value = { produk_id: '', tipe: 'masuk', jumlah: 1, catatan: '' }
    fetchTransactions()
  } catch (error) {
    console.error('Gagal menyimpan transaksi:', error.response?.data || error)
    showToast(error.response?.data?.message || 'Gagal menyimpan transaksi', 'error')
  } finally {
    saving.value = false
  }
}

onMounted(() => { fetchTransactions(); fetchProducts() })

const filteredTransactions = computed(() => {
  if (!searchQuery.value) return allTransactions.value
  return allTransactions.value.filter(txn =>
    txn.produk?.nama_produk?.toLowerCase().includes(searchQuery.value.toLowerCase())
  )
})

const formatDate = (dateString) => {
  const options = { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' }
  return new Date(dateString).toLocaleDateString('id-ID', options)
}
</script>

<template>
  <div class="bg-gray-100 dark:bg-gray-900 min-h-screen font-sans transition-colors">
    <div class="container mx-auto p-4 md:p-8">
      <!-- TOAST -->
      <div v-if="toast.show"
        :class="[ 'fixed top-4 right-4 px-4 py-2 rounded-lg shadow-lg text-white z-50',
                  toast.type === 'success' ? 'bg-green-600' : 'bg-red-600']">
        {{ toast.message }}
      </div>

      <!-- FORM TRANSAKSI -->
      <div class="mb-8 bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md transition-colors">
        <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200 mb-4">Tambah Transaksi</h2>
        <form @submit.prevent="submitTransaction" class="grid grid-cols-1 md:grid-cols-4 gap-4">
          <select v-model="form.produk_id" required
            class="border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-4 py-2 shadow-sm focus:ring-2 focus:ring-blue-500">
            <option value="">-- Pilih Produk --</option>
            <option v-for="prod in allProducts" :key="prod.id" :value="prod.id">
              {{ prod.nama_produk }} (Stok: {{ prod.stok }})
            </option>
          </select>

          <select v-model="form.tipe" required
            class="border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-4 py-2 shadow-sm focus:ring-2 focus:ring-blue-500">
            <option value="masuk">Barang Masuk</option>
            <option value="keluar">Barang Keluar</option>
          </select>

          <input type="number" v-model="form.jumlah" min="1" required
            class="border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-4 py-2 shadow-sm focus:ring-2 focus:ring-blue-500"
            placeholder="Jumlah" />

          <input type="text" v-model="form.catatan"
            class="border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-4 py-2 shadow-sm focus:ring-2 focus:ring-blue-500"
            placeholder="Catatan (opsional)" />

          <button type="submit" :disabled="saving"
            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition-colors col-span-1 md:col-span-4">
            {{ saving ? 'Menyimpan...' : 'Simpan Transaksi' }}
          </button>
        </form>
      </div>

      <!-- TABEL TRANSAKSI -->
      <div v-if="loading" class="text-center text-gray-500 dark:text-gray-400 py-10">
        <p>Memuat data transaksi...</p>
      </div>
      <div v-else class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-x-auto transition-colors">
        <table class="w-full">
          <thead class="bg-gray-50 dark:bg-gray-700 border-b-2 border-gray-200 dark:border-gray-600">
            <tr>
              <th class="p-4 text-left text-sm font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Produk</th>
              <th class="p-4 text-center text-sm font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Tipe</th>
              <th class="p-4 text-center text-sm font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Jumlah</th>
              <th class="p-4 text-left text-sm font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Catatan</th>
              <th class="p-4 text-left text-sm font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Tanggal</th>
            </tr>
          </thead>
          <tbody>
            <tr v-if="filteredTransactions.length === 0">
              <td colspan="5" class="p-8 text-center text-gray-500 dark:text-gray-400">Tidak ada transaksi yang cocok.</td>
            </tr>
            <tr v-for="txn in filteredTransactions" :key="txn.id"
                class="hover:bg-gray-50 dark:hover:bg-gray-700 border-b border-gray-200 dark:border-gray-600 transition-colors">
              <td class="p-4 text-gray-900 dark:text-gray-100 font-medium">{{ txn.produk?.nama_produk ?? '-' }}</td>
              <td class="p-4 text-center">
                <span :class="txn.tipe === 'masuk'
                    ? 'bg-green-100 dark:bg-green-800 text-green-700 dark:text-green-200 px-3 py-1 rounded-full text-sm font-semibold'
                    : 'bg-red-100 dark:bg-red-800 text-red-700 dark:text-red-200 px-3 py-1 rounded-full text-sm font-semibold'">
                  {{ txn.tipe }}
                </span>
              </td>
              <td class="p-4 text-center text-gray-900 dark:text-gray-100 font-bold">{{ txn.jumlah }}</td>
              <td class="p-4 text-gray-700 dark:text-gray-300">{{ txn.catatan || '-' }}</td>
              <td class="p-4 text-gray-600 dark:text-gray-400">{{ formatDate(txn.created_at) }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>
