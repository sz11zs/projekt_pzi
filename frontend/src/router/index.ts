import { createRouter, createWebHistory } from 'vue-router'

import HomePage from '@/pages/HomePage.vue'
import RegisterPage from '@/pages/RegisterPage.vue'
import AboutPage from '@/pages/AboutPage.vue'
import WelcomePage from '@/pages/WelcomePage.vue'
import ShopPage from '@/pages/ShopPage.vue'
import FruitPage from '@/pages/FruitPage.vue'
import VegetablePage from '@/pages/VegetablePage.vue'
import MadeProductPage from '@/pages/MadeProductPage.vue'
import AdminLogin from '@/pages/AdminLogin.vue'
import Dashboard from '@/pages/Dashboard.vue'

const routes = [
  { path: '/', name: 'home', component: HomePage },
  { path: '/registracija', name: 'register', component: RegisterPage },
  { path: '/about', name: 'about', component: AboutPage },
  { path: '/welcome', name: 'welcome', component: WelcomePage },
  { path: '/trgovina', name: 'shop', component: ShopPage },
  { path: '/trgovina/voce', name: 'voce', component: FruitPage },
  { path: '/trgovina/povrce', name: 'povrce', component: VegetablePage },
  { path: '/trgovina/gotovi', name: 'gotovi', component: MadeProductPage },
  { path: '/adminlogin', name: 'adminlogin', component: AdminLogin },
  { path: '/dashboard', name: 'dashboard', component: Dashboard },
];

// povezuje url adrese s vue stranicama
const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
});

// Workaround for https://github.com/vitejs/vite/issues/11804
router.onError((err, to) => {
  if (err?.message?.includes?.('Failed to fetch dynamically imported module')) {
    if (localStorage.getItem('vuetify:dynamic-reload')) {
      console.error('Dynamic import error, reloading page did not fix it', err)
    } else {
      localStorage.setItem('vuetify:dynamic-reload', 'true')
      location.assign(to.fullPath)
    }
  } else {
    console.error(err)
  }
})

router.isReady().then(() => {
  localStorage.removeItem('vuetify:dynamic-reload')
})

export default router
