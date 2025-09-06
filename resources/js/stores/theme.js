import { ref, watch } from 'vue'

const isDark = ref(localStorage.getItem('darkMode') === 'true')

watch(isDark, (val) => {
  if (val) {
    document.documentElement.classList.add('dark')
  } else {
    document.documentElement.classList.remove('dark')
  }
  localStorage.setItem('darkMode', val)
}, { immediate: true })

export const useTheme = () => {
  const toggleDarkMode = () => {
    isDark.value = !isDark.value
  }

  return { isDark, toggleDarkMode }
}
