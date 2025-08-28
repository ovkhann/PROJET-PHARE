import { ref, computed } from 'vue';
import { defineStore } from 'pinia';
import router from '@/router';
import Caller from '@/_services/CallerService';

interface ConnectedUser {
  email: string | null;
  role: string | null;
}

export const useUserStore = defineStore('user', () => {
  const user = ref<ConnectedUser>({
    email: localStorage.getItem('email') || null,
    role: localStorage.getItem('role') || null
  });

  const isLogged = computed(() => !!user.value.email);

  function setUser(data: ConnectedUser) {
    user.value.email = data.email;
    user.value.role = data.role;

    localStorage.setItem('email', data.email ?? '');
    localStorage.setItem('role', data.role ?? '');
  }

  function clearUser() {
    user.value.email = null;
    user.value.role = null;

    localStorage.removeItem('email');
    localStorage.removeItem('role');
  }

  async function logout() {
    try {
      await Caller.post('/logout');
    } catch (error) {
      console.error('Erreur logout backend :', error);
    }

    clearUser();
    router.push('/login');
  }

  return {
    user,
    isLogged,
    setUser,
    clearUser,
    logout
  };
});
