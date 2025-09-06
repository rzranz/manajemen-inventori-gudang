<!-- resources/js/components/Toast.vue -->
<template>
  <transition name="fade">
    <div
      v-if="visible"
      :class="[
        'fixed top-5 right-5 px-6 py-3 rounded-lg shadow-lg text-white font-semibold z-50',
        type === 'success' ? 'bg-green-600' : 'bg-red-600'
      ]"
    >
      {{ message }}
    </div>
  </transition>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const props = defineProps({
  message: String,
  type: { type: String, default: 'success' }, // success | error
  duration: { type: Number, default: 3000 }
})

const visible = ref(true)

onMounted(() => {
  setTimeout(() => {
    visible.value = false
  }, props.duration)
})
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.5s;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
