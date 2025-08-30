import { defineStore } from 'pinia'

interface CartItem {
    productId: number
    name: string
    price: number
    picture?: string | null
    quantity: number
    optionId?: number | null
    size?: string | null   // 👈 taille ajoutée
}

export const useCartStore = defineStore('cart', {
    state: () => ({
        items: [] as CartItem[]
    }),
    actions: {
        addToCart(product: { 
            id: number; 
            name: string; 
            price: number; 
            picture?: string | null; 
            optionId?: number | null;
            size?: string | null;   // 👈 taille récupérée
        }) {
            // Chercher un produit identique avec la même option/taille
            const existing = this.items.find(
                i => i.productId === product.id && i.optionId === product.optionId
            )

            if (existing) {
                existing.quantity++
            } else {
                this.items.push({
                    productId: product.id,
                    name: product.name,
                    price: product.price,
                    picture: product.picture ?? null,
                    quantity: 1,
                    optionId: product.optionId ?? null,
                    size: product.size ?? null   // 👈 stocke bien la taille
                })
            }
        },
        removeItem(productId: number, optionId?: number | null) {
            this.items = this.items.filter(
                i => !(i.productId === productId && i.optionId === optionId)
            )
        },
        updateQuantity(productId: number, optionId: number | null, quantity: number) {
            const item = this.items.find(
                i => i.productId === productId && i.optionId === optionId
            )
            if (item) {
                if (quantity <= 0) {
                    this.removeItem(productId, optionId)
                } else {
                    item.quantity = quantity
                }
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
