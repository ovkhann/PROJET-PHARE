<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useUserStore } from '@/stores/User'
import { useCartStore } from '@/stores/cart'
import Axios from '@/_services/CallerService'
import { useHead } from '@vueuse/head'

const User = useUserStore()
const cartStore = useCartStore()

useHead({
  title: 'Home | Revolve Realm',
  meta: [
    { name: 'description', content: "Welcome to Revolve Realm — a streetwear brand blending style, comfort, and authenticity. Discover our latest collections and elevate your everyday look." },
    { property: 'og:title', content: 'Home | Revolve Realm' }
  ],
  link: [
    { rel: 'canonical', href: 'https://revolverealm.com/'  }
  ]
})

// Interfaces
interface Option {
  id: number
  size: string
}

interface Product {
  id: number
  name: string
  picture: string[] // tableau d'images
  price: number
  options: Option[]
}

const products = ref<Product[]>([])

// Sélections d’options par produit : { [product.id]: optionId|null }
const selectedOptions = ref<Record<number, number | null>>({})

// Charger les 4 derniers produits
onMounted(async () => {
  try {
    const res = await Axios.get('/api/products')

    const fetchedProducts: Product[] = res.data.map((p: any) => ({
      id: Number(p.id),
      name: String(p.name),
      picture: Array.isArray(p.picture) ? p.picture : (p.picture ? [p.picture] : []),
      price: Number(p.price),
      options: p.options ?? []
    }))

    products.value = fetchedProducts
      .sort((a, b) => b.id - a.id)
      .slice(0, 4)

    // Initialiser selectedOptions à null pour chaque produit
    products.value.forEach(p => {
      selectedOptions.value[p.id] = null
    })
  } catch (error) {
    console.error(error)
  }
})

function addProductToCart(product: Product) {
  if (!User.isLogged) return

  const selectedOptionId = selectedOptions.value[product.id]
  if (!selectedOptionId) {
    alert("Veuillez choisir une taille avant d’ajouter au panier !")
    return
  }

  // 🔎 Trouver l'option choisie pour récupérer aussi la taille
  const selectedOption = product.options.find(opt => opt.id === selectedOptionId)

  cartStore.addToCart({
    id: product.id,
    name: product.name,
    price: product.price,
    picture: product.picture[0] ?? null,
    optionId: selectedOption?.id ?? null,
    size: selectedOption?.size ?? null   // 👈 on passe la taille
  })

  alert(`${product.name} ajouté au panier avec la taille ${selectedOption?.size} !`)
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
          <!-- Produit cliquable -->
          <router-link :to="{ name: 'product-detail', params: { id: product.id } }" class="product-link">
            <div class="container-product-image">
              <img :src="product.picture[0] ?? '/images/products/fallback.jpg'" :alt="product.name"
                class="product-image" @error="handleImageError" />
            </div>
            <p class="product-name">{{ product.name }}</p>
            <p class="product-price">{{ product.price.toFixed(2) }}€</p>
          </router-link>

          <!-- Sélecteur de taille -->
          <select v-if="product.options.length" v-model="selectedOptions[product.id]" class="option-select">
            <option :value="null" disabled>Choisir une taille</option>
            <option v-for="opt in product.options" :key="opt.id" :value="opt.id">
              {{ opt.size }}
            </option>
          </select>

          <!-- Bouton Ajouter au panier -->
          <button v-if="User.isLogged" class="add-to-cart-btn" @click="addProductToCart(product)">
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
            We are a passionate team driven by a love for fashion and a commitment to authenticity.
            Our goal is to offer unique, responsible pieces that reflect individual style and timeless elegance.
            Every collection we create is infused with purpose, creativity, and a desire to inspire confidence
            in those who wear it.
          </p>
        </div>
      </div>
    </div>
  </section>
</template>



<style scoped>
.add-to-cart-btn {
  padding: 0.5rem 2rem;
  background-color: var(--color-beige);
  color: var(--color-brown);
  border: none;
  font-weight: bold;
  width: fit-content;
  border-radius: 5px;
  cursor: pointer;
  transition: all 0.3s ease-in-out;
}

.add-to-cart-btn:hover {
  background-color: var(--color-brown);
  color: var(--color-beige);
}

.product-link {
  padding: 0vw;
  color: var(--color-brown);
}

.cover-image {
  width: 100%;
  height: 80vh;
  object-fit: cover;
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
  display: flex;
  transition: all 0.3s ease-in-out;
  flex-direction: column;
  justify-content: center;
  align-items: center;
}

.container-product-image {
  width: 100%;
  height: auto;
  position: relative;
  display: flex;
  justify-content: center;
  align-items: center;
  flex-direction: column;
}

.option-select {
  font-size: 1vw;
  font-family: 'nexa-light';
  margin-bottom: 1vw;
  padding: 5px;
  border-radius: 5px;
  border: solid 2px var(--color-beige);
}

.product-link:hover {
  transform: scale(0.9);
}

.product-image {
  width: 100%;
  height: 22vw;
  border: 3px solid var(--color-beige);
  object-fit: cover;
  border-radius: 2vw;
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
  max-width: 31%;
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

@media screen and (max-width: 767px) {
  .products-container {
    display: flex;
    gap: 15vw;
    justify-content: center;
    align-items: center;
    flex-wrap: wrap;
  }

  .product-card {
    width: auto;
    text-align: center;
    display: flex;
    transition: all 0.3s ease-in-out;
    flex-direction: column;
    justify-content: center;
    align-items: center;
  }

  .product-image {
    width: 60vw;
    height: 65vw;
    border: 3px solid var(--color-beige);
    object-fit: cover;
    border-radius: 2vw;
    position: relative;
}

.product-name {
    margin: 0.5rem 0vw;
    font-size: 4vw;
    font-weight: bold;
    color: var(--color-brown);
}

.product-price {
    margin: 0.5rem 0vw;
    font-weight: bold;
    font-size: 4vw;
    color: var(--color-brown);
}

.option-select {
    font-size: 4vw;
    font-family: 'nexa-light';
    margin-bottom: 1vw;
    padding: 5px;
    border-radius: 5px;
    border: solid 2px var(--color-beige);
}

.add-to-cart-btn {
    padding: 0.5rem 2rem;
    background-color: var(--color-beige);
    color: var(--color-brown);
    border: none;
    font-weight: bold;
    font-size: 4vw;
    width: fit-content;
    border-radius: 5px;
    cursor: pointer;
    transition: all 0.3s ease-in-out;
}


}
</style>