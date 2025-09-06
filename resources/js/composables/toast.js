import { ref } from 'vue'

const toasts = ref([])

export function useToast() {
  const showToast = (message, type = 'success') => {
    const id = Date.now()
    toasts.value.push({ id, message, type })

    setTimeout(() => {
      toasts.value = toasts.value.filter(t => t.id !== id)
    }, 3000)
  }

  return { toasts, showToast }
}
