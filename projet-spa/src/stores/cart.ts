import { defineStore } from 'pinia'

interface CartItem {
  productId: number
  name: string
  price: number
  picture?: string | null
  quantity: number
}

export const useCartStore = defineStore('cart', {
  state: () => ({
    items: [] as CartItem[]
  }),
  actions: {
    addToCart(product: { id: number; name: string; price: number; picture?: string | null }) {
      const existing = this.items.find(i => i.productId === product.id)
      if (existing) {
        existing.quantity++
      } else {
        this.items.push({
          productId: product.id,
          name: product.name,
          price: product.price,
          picture: product.picture ?? null,
          quantity: 1
        })
      }
    },
    clearCart() {
      this.items = []
    }
  },
  getters: {
    totalItems: (state) => state.items.reduce((acc, i) => acc + i.quantity, 0),
    totalPrice: (state) => state.items.reduce((acc, i) => acc + i.price * i.quantity, 0)
  }
})
