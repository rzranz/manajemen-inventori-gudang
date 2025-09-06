import './bootstrap'
import { createApp } from 'vue'
import App from './layouts/App.vue'
import router from './router'

const app = createApp(App)

app.use(router)
app.mount('#app')
