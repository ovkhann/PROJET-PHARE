import Axios from './CallerService';
import { useUserStore } from '@/stores/User';

export async function login(credentials: { email: string, password: string }) {
  // Récupérer le cookie CSRF
  await Axios.get('/sanctum/csrf-cookie')

  // Ensuite login
  const res = await Axios.post('/authenticate', credentials)

  const userStore = useUserStore()
  userStore.setUser({
    email: res.data.user.email,
    role: res.data.user.role
  })
}

export async function logout() {
    await Axios.post('/logout');

      const userStore = useUserStore();
      userStore.clearUser();

    const cookies = document.cookie.split(";");
    for (let i = 0; i < cookies.length; i++) {
        const cookie = cookies[i];
        const eqPos = cookie.indexOf('=');
        const name = eqPos > -1 ? cookie.substr(0, eqPos) : cookie;
        document.cookie = name + '=;expires=Thu, 01 Jan 1970 00:00:00 GMT;path=/';
    }
}

export async function register(payload:any) {
    await Axios.get('/sanctum/csrf-cookie', {
        baseURL: 'http://localhost:8000'
    });
    
    return await Axios.post('/api/register', payload)
}

export function isLogged(): boolean {
    const userStore = useUserStore();
    return userStore.isLogged;
}