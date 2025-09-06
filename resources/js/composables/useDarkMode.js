import { ref, watch } from 'vue'

export function useDarkMode(containerId = 'app') {
  const isDark = ref(localStorage.getItem('theme') === 'dark')

  const container = document.getElementById(containerId)
  if (!container) console.warn(`Container #${containerId} tidak ditemukan`)

  const applyTheme = () => {
    if (!container) return
    if (isDark.value) container.classList.add('dark')
    else container.classList.remove('dark')
  }

  // Apply saat pertama kali load
  applyTheme()

  // Watch perubahan
  watch(isDark, (val) => {
    localStorage.setItem('theme', val ? 'dark' : 'light')
    applyTheme()
  })

  const toggleDark = () => {
    isDark.value = !isDark.value
  }

  return { isDark, toggleDark }
}
