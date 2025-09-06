<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

// State produk
const allProducts = ref([])
const loading = ref(true)

// State kategori
const allCategories = ref([])
const selectedCategory = ref(null)

// Form tambah produk
const form = ref({
  nama_produk: '',
  deskripsi: '',
  stok: 0
})
const saving = ref(false)

// State edit produk
const editingId = ref(null)
const editForm = ref({
  nama_produk: '',
  deskripsi: '',
  stok: 0,
  kategori_id: null
})
const savingEdit = ref(false)

// Toast notification
const toast = ref({ show: false, message: '', type: 'success' })
const showToast = (message, type = 'success') => {
  toast.value = { show: true, message, type }
  setTimeout(() => { toast.value.show = false }, 3000)
}

// Fetch produk
const fetchProducts = async () => {
  loading.value = true
  try {
    const response = await axios.get('/api/produks')
    allProducts.value = response.data.data
  } catch (error) {
    console.error(error)
    showToast('Gagal memuat produk', 'error')
  } finally {
    loading.value = false
  }
}

// Fetch kategori
const fetchCategories = async () => {
  try {
    const response = await axios.get('/api/kategoris')
    allCategories.value = response.data.data
    selectedCategory.value = allCategories.value[0]?.id || null
  } catch (error) {
    console.error(error)
    showToast('Gagal memuat kategori', 'error')
  }
}

// Submit produk baru
const submitProduct = async () => {
  if (!selectedCategory.value) {
    showToast('Pilih kategori terlebih dahulu', 'error')
    return
  }

  saving.value = true
  try {
    await axios.post('/api/produks', {
      ...form.value,
      kategori_id: selectedCategory.value
    })
    showToast('Produk berhasil ditambahkan!', 'success')
    form.value = { nama_produk: '', deskripsi: '', stok: 0 }
    fetchProducts()
  } catch (error) {
    console.error(error)
    showToast(error.response?.data?.message || 'Gagal menambahkan produk', 'error')
  } finally {
    saving.value = false
  }
}

// Mulai edit produk
const startEdit = (prod) => {
  editingId.value = prod.id
  editForm.value = {
    nama_produk: prod.nama_produk,
    deskripsi: prod.deskripsi,
    stok: prod.stok,
    kategori_id: prod.kategori?.id || null
  }
}

// Cancel edit
const cancelEdit = () => {
  editingId.value = null
}

// Submit edit produk
const submitEdit = async (id) => {
  if (!editForm.value.kategori_id) {
    showToast('Pilih kategori terlebih dahulu', 'error')
    return
  }

  savingEdit.value = true
  try {
    await axios.put(`/api/produks/${id}`, editForm.value)
    showToast('Produk berhasil diperbarui!', 'success')
    editingId.value = null
    fetchProducts()
  } catch (error) {
    console.error(error)
    showToast(error.response?.data?.message || 'Gagal memperbarui produk', 'error')
  } finally {
    savingEdit.value = false
  }
}

onMounted(() => {
  fetchProducts()
  fetchCategories()
})
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

      <!-- FORM PRODUK -->
      <div class="mb-8 bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md transition-colors">
        <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200 mb-4">Tambah Produk</h2>
        <form @submit.prevent="submitProduct" class="grid grid-cols-1 md:grid-cols-4 gap-4">
          <input type="text" v-model="form.nama_produk" required
            class="border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-4 py-2 shadow-sm focus:ring-2 focus:ring-blue-500"
            placeholder="Nama produk" />

          <input type="text" v-model="form.deskripsi"
            class="border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-4 py-2 shadow-sm focus:ring-2 focus:ring-blue-500"
            placeholder="Deskripsi produk (opsional)" />

          <input type="number" v-model="form.stok" min="0" required
            class="border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-4 py-2 shadow-sm focus:ring-2 focus:ring-blue-500"
            placeholder="Jumlah stok" />

          <select v-model="selectedCategory" required
            class="border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-4 py-2 shadow-sm focus:ring-2 focus:ring-blue-500">
            <option v-for="cat in allCategories" :key="cat.id" :value="cat.id">
              {{ cat.nama }}
            </option>
          </select>

          <button type="submit" :disabled="saving"
            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition-colors col-span-1 md:col-span-4">
            {{ saving ? 'Menyimpan...' : 'Tambah Produk' }}
          </button>
        </form>
      </div>

      <!-- TABEL PRODUK -->
      <div v-if="loading" class="text-center text-gray-500 dark:text-gray-400 py-10">
        <p>Memuat data produk...</p>
      </div>
      <div v-else class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-x-auto transition-colors">
        <table class="w-full">
          <thead class="bg-gray-50 dark:bg-gray-700 border-b-2 border-gray-200 dark:border-gray-600">
            <tr>
              <th class="p-4 text-left text-sm font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Nama Produk</th>
              <th class="p-4 text-left text-sm font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Deskripsi</th>
              <th class="p-4 text-center text-sm font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Stok</th>
              <th class="p-4 text-left text-sm font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Kategori</th>
              <th class="p-4 text-center text-sm font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <tr v-if="allProducts.length === 0">
              <td colspan="5" class="p-8 text-center text-gray-500 dark:text-gray-400">Belum ada produk.</td>
            </tr>

            <tr v-for="prod in allProducts" :key="prod.id"
                class="hover:bg-gray-50 dark:hover:bg-gray-700 border-b border-gray-200 dark:border-gray-600 transition-colors">

              <template v-if="editingId === prod.id">
                <td class="p-2">
                  <input type="text" v-model="editForm.nama_produk"
                    class="border rounded px-2 py-1 w-full" />
                </td>
                <td class="p-2">
                  <input type="text" v-model="editForm.deskripsi"
                    class="border rounded px-2 py-1 w-full" />
                </td>
                <td class="p-2">
                  <input type="number" v-model="editForm.stok" min="0"
                    class="border rounded px-2 py-1 w-full text-center" />
                </td>
                <td class="p-2">
                  <select v-model="editForm.kategori_id" class="border rounded px-2 py-1 w-full">
                    <option v-for="cat in allCategories" :key="cat.id" :value="cat.id">
                      {{ cat.nama }}
                    </option>
                  </select>
                </td>
                <td class="p-2 text-center">
                  <button @click="submitEdit(prod.id)" :disabled="savingEdit"
                    class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded mr-2">
                    {{ savingEdit ? 'Menyimpan...' : 'Simpan' }}
                  </button>
                  <button @click="cancelEdit"
                    class="bg-gray-400 hover:bg-gray-500 text-white px-3 py-1 rounded">
                    Batal
                  </button>
                </td>
              </template>

              <template v-else>
                <td class="p-4 text-gray-900 dark:text-gray-100 font-medium">{{ prod.nama_produk }}</td>
                <td class="p-4 text-gray-700 dark:text-gray-300">{{ prod.deskripsi || '-' }}</td>
                <td class="p-4 text-center text-gray-900 dark:text-gray-100 font-bold">{{ prod.stok }}</td>
                <td class="p-4 text-gray-900 dark:text-gray-100 font-medium">{{ prod.kategori?.nama || '-' }}</td>
                <td class="p-4 text-center">
                  <button @click="startEdit(prod)"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded">
                    Edit
                  </button>
                </td>
              </template>

            </tr>
          </tbody>
        </table>
      </div>

    </div>
  </div>
</template>
