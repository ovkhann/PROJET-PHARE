// _services/CartService.ts
import Axios from './CallerService'

interface AddToCartPayload {
  product_id: number
  quantity: number
}

export default class CartService {
  static async fetchCart() {
    return await Axios.get('/api/product_users')
  }

  static async addToCart(payload: AddToCartPayload) {
    return await Axios.post('/api/product_users', payload)
  }

  static async removeFromCart(product_id: number) {
    return await Axios.delete(`/api/product_users/${product_id}`)
  }

  static async clearCart() {
    return await Axios.delete('/api/product_users')
  }
}
