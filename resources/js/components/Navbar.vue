<script setup>
import { RouterLink } from 'vue-router'
import { defineProps, defineEmits } from 'vue'

const props = defineProps({ isDark: Boolean })
const emit = defineEmits(['toggle-dark'])

function toggle() {
  emit('toggle-dark')
}
</script>

<template>
  <nav class="bg-gray-800 dark:bg-gray-900 text-white px-6 py-3 flex justify-between items-center shadow">
    <div class="flex items-center gap-2">
      <img src="/images/logo-pindad.png" alt="Logo" class="h-8" />
      <span class="font-bold text-lg">Inventori Pindad</span>
    </div>

    <div class="flex items-center gap-6">
      <RouterLink class="hover:underline" to="/">Dashboard</RouterLink>
      <RouterLink class="hover:underline" to="/produk">Produk</RouterLink>
      <RouterLink class="hover:underline" to="/transaksi">Transaksi</RouterLink>

      <!-- <button @click="toggle" class="p-2 rounded-md hover:bg-gray-700 dark:hover:bg-gray-600 transition">
        <svg v-if="!props.isDark" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 20 20">
          <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"/>
        </svg>
        <img v-else src="https://www.svgrepo.com/show/520984/sun.svg" alt="Sun Icon" class="h-6 w-6" />
      </button> -->
    </div>
  </nav>
</template>
