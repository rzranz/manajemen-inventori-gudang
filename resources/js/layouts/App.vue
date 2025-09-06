<template>
  <div class="min-h-screen transition-colors duration-300 bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100">
    <Navbar :is-dark="isDark" @toggle-dark="toggleDark" />
    <main class="p-6">
      <router-view />
    </main>
  </div>
</template>

<script setup>
import Navbar from '../components/Navbar.vue'
import { ref, onMounted } from 'vue'

const isDark = ref(false)

function applyTheme() {
  if (isDark.value) {
    document.documentElement.classList.add('dark')
  } else {
    document.documentElement.classList.remove('dark')
  }
}

onMounted(() => {
  isDark.value = localStorage.getItem('theme') === 'dark'
  applyTheme()
})

function toggleDark() {
  isDark.value = !isDark.value
  localStorage.setItem('theme', isDark.value ? 'dark' : 'light')
  applyTheme()
}
</script>
