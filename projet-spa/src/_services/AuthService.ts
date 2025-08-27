// _services/AuthService.ts
import Caller from "@/_services/CallerService";
import { useUserStore } from '@/stores/User';
import { useCartStore } from '@/stores/cart';

interface Credentials {
  email: string;
  password: string;
}

export async function login(credentials: Credentials) {
    const userStore = useUserStore();
    const cartStore = useCartStore();

    try {
        const res = await Caller.post('/authenticate', credentials);
        // Stockage infos utilisateur
        userStore.setUser({
            email: res.data.user.email,
            role: res.data.user.role
        });
        // Charger panier
        await cartStore.fetchCart?.();
    } catch (error: any) {
        console.error('Erreur login:', error);
        throw new Error(error.response?.data?.message || 'Impossible de se connecter');
    }
}

export async function logout() {
    const userStore = useUserStore();
    const cartStore = useCartStore();

    try {
        await Caller.post('/logout');
    } catch (error) {
        console.error('Erreur logout:', error);
    }

    await cartStore.clearCart?.();
    userStore.clearUser();
}

export async function register(payload: any) {
    try {
        return await Caller.post('/api/register', payload);
    } catch (error: any) {
        console.error('Erreur inscription:', error);
        throw new Error(error.response?.data?.message || 'Impossible de s\'inscrire');
    }
}

export function isLogged(): boolean {
    return useUserStore().isLogged;
}
