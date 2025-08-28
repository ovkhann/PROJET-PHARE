// stores/cart.ts
import { defineStore } from 'pinia'
import CartService from '@/_services/CartService'

interface Product {
  id: number
  name: string
  picture: string | null
  price: number
}

interface CartItem {
  product_id: number
  quantity: number
  product: Product
}

export const useCartStore = defineStore('product_users', {
  state: () => ({
    items: [] as CartItem[],
  }),
  actions: {
    async fetchCart() {
      try {
        const res = await CartService.fetchCart()
        this.items = res.data.map((item: any) => ({
          product_id: item.product_id,
          quantity: item.quantity,
          product: item.product
        }))
      } catch (error) {
        console.error(error)
        this.items = []
      }
    },

    async addToCart(product_id: number, quantity = 1) {
      try {
        await CartService.addToCart({ product_id, quantity })
        await this.fetchCart()
      } catch (error: any) {
        console.error('Erreur addToCart:', error.response?.data || error)
        throw error
      }
    },

    async removeFromCart(product_id: number) {
      try {
        await CartService.removeFromCart(product_id)
        await this.fetchCart()
      } catch (error: any) {
        console.error('Erreur removeFromCart:', error.response?.data || error)
        throw error
      }
    },

    async clearCart() {
      try {
        await CartService.clearCart()
        this.items = []
      } catch (error: any) {
        console.error('Erreur clearCart:', error.response?.data || error)
        throw error
      }
    }
  }
})
