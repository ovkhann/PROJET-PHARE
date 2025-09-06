<script setup lang="ts">
import { ref, onMounted, computed } from 'vue'
import { RouterLink } from 'vue-router'
import Axios from '@/_services/CallerService'
import { useHead } from '@vueuse/head'

useHead({
  title: 'Shop | Revolve Realm',
  meta: [
    { name: 'description', content: "Explore the full Revolve Realm streetwear collection. Discover our latest drops, premium hoodies, t-shirts, and accessories designed for style and comfort. Shop the entire catalog now." },
    { property: 'og:title', content: 'Shop | Revolve Realm' }
  ],
  link: [
    { rel: 'canonical', href: 'https://revolverealm.com/shop'  }
  ]
})

interface Option {
  id: number
  size: string
}

interface Category {
  id: number
  name: string
}

interface Product {
  id: number
  name: string
  price: number
  picture: string[]
  description: string
  category: Category | null
  options: Option[]
}

const products = ref<Product[]>([])
const categories = ref<Category[]>([])
const sizes = ref<string[]>([]) // toutes les tailles uniques
const selectedCategory = ref<number | null>(null)
const selectedSize = ref<string | null>(null)

// Récupérer tous les produits + catégories + tailles
onMounted(async () => {
  try {
    const res = await Axios.get('/api/products')
    products.value = res.data.map((p: any) => ({
      id: Number(p.id),
      name: String(p.name),
      price: Number(p.price),
      picture: Array.isArray(p.picture) ? p.picture : (p.picture ? [p.picture] : []),
      description: String(p.description),
      category: p.category ?? null,
      options: p.options ?? []
    }))

    // Extraire catégories uniques
    const cats = products.value
      .map(p => p.category)
      .filter(c => c !== null) as Category[]
    categories.value = Array.from(new Map(cats.map(c => [c.id, c])).values())

    // Extraire tailles uniques
    const allSizes = products.value.flatMap(p => p.options.map(o => o.size))
    sizes.value = Array.from(new Set(allSizes))
  } catch (error) {
    console.error(error)
  }
})

const filteredProducts = computed(() => {
  return products.value.filter(p => {
    const matchCategory = !selectedCategory.value || p.category?.id === selectedCategory.value
    const matchSize = !selectedSize.value || p.options.some(opt => opt.size === selectedSize.value)
    return matchCategory && matchSize
  })
})

function handleImageError(event: Event) {
  const target = event.target as HTMLImageElement
  target.src = '/images/products/fallback.jpg'
}
</script>

<template>
  <section class="shop-page">
    <h1 class="shop-title">THE SHOP</h1>

    <!-- Filtres catégories -->
    <div class="filters">
      <div class="category-filters">
        <button :class="{ active: selectedCategory === null }" @click="selectedCategory = null">
          Tous
        </button>
        <button v-for="cat in categories" :key="cat.id" :class="{ active: selectedCategory === cat.id }"
          @click="selectedCategory = cat.id">
          {{ cat.name }}
        </button>
      </div>

      <!-- Filtres tailles -->
      <div class="size-filters" v-if="sizes.length">
        <button :class="{ active: selectedSize === null }" @click="selectedSize = null">
          Toutes tailles
        </button>
        <button v-for="size in sizes" :key="size" :class="{ active: selectedSize === size }"
          @click="selectedSize = size">
          {{ size }}
        </button>
      </div>
    </div>

    <!-- Produits -->
    <div v-if="filteredProducts.length" class="products-grid">
      <RouterLink v-for="product in filteredProducts" :key="product.id"
        :to="{ name: 'product-detail', params: { id: product.id } }" class="product-card">

        <img :src="product.picture[0] ?? '/images/products/fallback.jpg'" :alt="product.name"
          @error="handleImageError" />
        <p class="name">{{ product.name }}</p>
        <p class="price">{{ product.price.toFixed(2) }}€</p>
      </RouterLink>
    </div>

    <p v-else>Aucun produit trouvé avec ce filtre</p>
  </section>
</template>

<style scoped>
.shop-page {
  padding: 2rem;
  text-align: center;
}

.shop-title {
  text-align: center;
  margin: 1rem 0rem;
  font-size: 2rem;
  color: var(--color-brown);
  font-weight: bold;
}

.filters {
  display: flex;
  flex-direction: column;
  gap: 1rem;
  margin-bottom: 2rem;
}

.category-filters,
.size-filters {
  display: flex;
  gap: 1rem;
  justify-content: center;
  flex-wrap: wrap;
}

.category-filters button,
.size-filters button {
  padding: 0.5rem 1rem;
  border: none;
  background-color: var(--color-beige);
  color: var(--color-brown);
  border-radius: 5px;
  cursor: pointer;
  font-weight: bold;
  transition: all 0.3s ease-in-out;
}

.category-filters button.active,
.size-filters button.active,
.category-filters button:hover,
.size-filters button:hover {
  background-color: var(--color-brown);
  color: var(--color-beige);
}

.products-grid {
  display: flex;
  flex-wrap: wrap;
  gap: 2rem;
  justify-content: center;
}

.product-card {
  display: flex;
  flex-direction: column;
  flex: 1 1 calc(25% - 2rem);
  max-width: 20vw;
  align-items: center;
  overflow: hidden;
  text-decoration: none;
  border: 1px solid var(--color-creme);
  border-radius: 10px;
  padding: 0rem;
  transition: transform 0.2s;
}

.product-card:hover {
  transform: scale(1.05);
}

.product-card img {
  width: 100%;
  height: 20vw;
  object-fit: cover;
  border-bottom: solid 1px var(--color-creme);
  margin-bottom: 1rem;
}

.product-card .name {
  font-weight: bold;
  color: var(--color-brown);
}

.product-card .price {
  color: var(--color-brown);
}
</style>
