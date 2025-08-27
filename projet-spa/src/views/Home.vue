<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useCartStore } from '@/stores/cart'
import { useUserStore } from '@/stores/User'
import Caller from '@/_services/CallerService'

const cartStore = useCartStore()
const User = useUserStore()

interface Product {
  id: number
  name: string
  picture: string | null
  price: number
}

const products = ref<Product[]>([])

// Charger les produits et le panier si connecté
onMounted(async () => {
  try {
    const res = await Caller.get('/api/products')

    // Assurer le type
    const fetchedProducts: Product[] = res.data.map((p: any) => ({
      id: Number(p.id),
      name: String(p.name),
      picture: p.picture ?? null,
      price: Number(p.price)
    }))

    // Trier et prendre les 4 derniers
    products.value = fetchedProducts
      .sort((a, b) => b.id - a.id)
      .slice(0, 4)

    if (User.isLogged) {
      await cartStore.fetchCart()
    }
  } catch (error) {
    console.error(error)
  }
})



// Ajouter un produit au panier
async function addToCart(product: Product) {
  if (!User.isLogged) {
    alert('Veuillez vous connecter pour ajouter au panier !')
    return
  }

  try {
    await cartStore.addToCart(product.id, 1)
    alert(`${product.name} ajouté au panier !`)
  } catch (error: any) {
    console.error(error)
    alert(error.response?.data?.message || 'Impossible d’ajouter au panier')
  }
}

function handleImageError(event: Event) {
  const target = event.target as HTMLImageElement
  target.src = '/images/products/fallback.jpg'
}

</script>

<template>
  <section class="home-page">
    <!-- 1. Image de couverture -->
    <div class="cover-image-section">
      <img src="@/assets/images/image-couverture.jpg" alt="Couverture" class="cover-image" />
    </div>

    <!-- 2. NEW COLLECTION -->
    <div class="new-collection-section">
      <h2 class="section-title">NEW COLLECTION</h2>

      <div class="products-container" :class="{ 'centered': products.length < 4 }">
        <div v-if="products.length === 0">Aucun produit disponible</div>
        <div v-else v-for="product in products" :key="product.id" class="product-card">
          <div class="container-product-image">
            <img :src="product.picture ?? '/images/products/fallback.jpg'" :alt="product.name" class="product-image"
              @error="handleImageError" />
          </div>
          <p class="product-name">{{ product.name }}</p>
          <p class="product-price">{{ product.price.toFixed(2) }}€</p>
          <button class="add-to-cart-btn" @click="addToCart(product)">
            Ajouter au panier
          </button>
        </div>
      </div>
    </div>

    <!-- 3. WHO WE ARE -->
    <div class="who-we-are-section">
      <div class="who-wrapper">
        <div class="who-image">
          <img src="@/assets/images/RRlogowhite.png" alt="About us" class="who-image" />
        </div>
        <div class="who-text">
          <h2>WHO WE ARE ?</h2>
          <p>
            We are a passionate team driven by a love for fashion and a commitment to authenticity. Our goal is to offer
            unique, responsible pieces that reflect individual style and timeless elegance. Every collection we create
            is infused with purpose, creativity, and a desire to inspire confidence in those who wear it.
          </p>
        </div>
      </div>
    </div>
  </section>
</template>

<!-- <style scoped>
.products-container {
  display: flex;
  gap: 20px;
  flex-wrap: wrap;
}
.product-card {
  width: calc(25% - 15px);
  display: flex;
  flex-direction: column;
  align-items: center;
}
.product-image {
  width: 100%;
  object-fit: cover;
}
.add-to-cart-btn {
  margin-top: 10px;
  padding: 8px 12px;
  background-color: #a0522d;
  color: white;
  border: none;
  cursor: pointer;
}
.add-to-cart-btn:hover {
  background-color: #8b4513;
}
.centered {
  justify-content: center;
}
</style> -->



<style scoped>
.add-to-cart-btn {
  margin-top: 8px;
  padding: 6px 12px;
  background-color: #1a73e8;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.add-to-cart-btn:hover {
  background-color: #155ab6;
}

.cover-image {
  width: 100%;
  height: 80vh;
  object-fit: cover;
}

.cover-image-section {
  margin-top: 8vw;
}

.section-title {
  text-align: center;
  margin: 2rem 0rem;
  font-size: 2rem;
  color: var(--color-brown);
  font-weight: bold;
}

.products-container {
  display: flex;
  gap: 5vw;
  justify-content: center;
  align-items: center;
}

.products-container.centered {
  justify-content: center;
}

.product-card {
  width: 20vw;
  text-align: center;
}

.container-product-image {
  width: 100%;
  height: auto;
  position: relative;
  display: inline-flex;
}

.product-image {
  width: 100%;
  height: 22vw;
  object-fit: cover;
  position: relative;
}

.product-name {
  margin: 0.5rem 0vw;
  font-weight: bold;
  color: var(--color-brown);
}

.product-price {
  margin: 0.5rem 0vw;
  font-weight: bold;
  color: var(--color-brown);
}

.who-we-are-section {
  margin-top: 4rem;
  background-color: var(--color-brown);
}

.who-wrapper {
  display: flex;
  flex-direction: row;
  align-items: center;
  justify-content: space-evenly;
}

.who-image {
  width: 25%;
  height: auto;
  position: relative;
  display: inline-flex;
}

.who-image img {
  width: 100%;
  height: auto;
  position: relative;
  object-fit: cover;
}

.who-text {
  max-width: 40%;
}

.who-text h2 {
  text-align: center;
  font-size: 2rem;
  margin: 1vw 0vw;
  font-weight: bold;
}

.who-text p {
  line-height: 1.6;
  color: var(--color-beige);
  text-align: justify;
  text-align-last: left;
}

.home-page {
  width: 100%;
  height: auto;
  position: relative;
}

h2 {
  color: var(--color-beige);
}
</style>
