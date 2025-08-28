import { createWebHistory, createRouter } from "vue-router";
import axios from "axios";

// Layouts et vues
import DefaultLayout from "@/layouts/DefaultLayout.vue";
import Contact from "@/views/Contact.vue";
import Home from "@/views/Home.vue";
import Login from "@/views/Login.vue";
import Register from "@/views/Register.vue";
import Shop from "@/views/Shop.vue";
import AddProduct from "@/views/AddProduct.vue";

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      component: DefaultLayout,
      children: [
        { path: '', name: 'home', component: Home },
        { path: 'shop', name: 'shop', component: Shop },
        { path: 'contact', name: 'contact', component: Contact },
        { path: 'login', name: 'login', component: Login },
        { path: 'register', name: 'register', component: Register },
        { path: 'add-product', name: 'addProduct', component: AddProduct }
      ]
    },
    {
      path: '/:pathMatch(.*)*',
      redirect: '/'
    },
    {
      path: '/admin',
      component: () => import('@/layouts/AdminLayout.vue'),
      meta: { requiresAdmin: true }
    }
  ]
});

export default router;
