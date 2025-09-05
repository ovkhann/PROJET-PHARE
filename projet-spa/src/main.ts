import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap';

import './assets/main.css'

import { createHead } from '@vueuse/head'
import { createApp } from 'vue'
import { createPinia } from 'pinia'

import App from './App.vue'
import router from './router'
import { useUserStore } from '@/stores/User'

const pinia = createPinia()
const app = createApp(App)
const head = createHead()

app.use(router)
app.use(pinia)
app.use(head)

app.mount('#app')

const userStore = useUserStore()
