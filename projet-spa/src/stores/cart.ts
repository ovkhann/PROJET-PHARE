// stores/cart.ts
import { defineStore } from 'pinia'
import Caller from '@/_services/CallerService'

interface Product {
  id: number
  name: string
  picture: string | null
  price: number
}

interface CartItem {
  product_id: number
  quantity: number
  product: Product   // <-- ajouter ici
}

export const useCartStore = defineStore('product_users', {
  state: () => ({
    items: [] as CartItem[], // Contenu du panier
  }),
  actions: {
    // Récupérer le panier depuis l'API
    async fetchCart() {
      try {
        const res = await Caller.get('/api/product_users')
        this.items = res.data.map((item: any) => ({
          product_id: item.product_id,
          quantity: item.quantity,
          product: item.product
        }))
      } catch (error) {
        console.error(error)
        this.items = []
      }
    }
    ,

    // Ajouter un produit au panier
    async addToCart(product_id: number, quantity = 1) {
      try {
        // Récupérer le cookie CSRF avant le POST
        await Caller.get('/sanctum/csrf-cookie')

        // Ajouter le produit
        await Caller.post('/api/product_users', { product_id, quantity })

        // Mettre à jour le panier local
        await this.fetchCart()
      } catch (error: any) {
        console.error('Erreur addToCart:', error)
        throw error // On renvoie l'erreur pour la gérer côté composant
      }
    },

    // Supprimer un produit du panier
    async removeFromCart(product_id: number) {
      try {
        await Caller.get('/sanctum/csrf-cookie')
        await Caller.delete(`/api/product_users/${product_id}`)
        await this.fetchCart()
      } catch (error: any) {
        console.error('Erreur removeFromCart:', error)
        throw error
      }
    },

    // Vider le panier
    async clearCart() {
      try {
        await Caller.get('/sanctum/csrf-cookie')
        await Caller.post('/api/product_users/clear')
        this.items = []
      } catch (error: any) {
        console.error('Erreur clearCart:', error)
        throw error
      }
    }
  }
})
