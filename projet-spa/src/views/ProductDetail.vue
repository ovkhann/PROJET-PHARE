<script setup lang="ts">
import { ref, onMounted, watch } from 'vue'
import { useRoute } from 'vue-router'
import { useCartStore } from '@/stores/cart'
import { useUserStore } from '@/stores/User'
import Axios from '@/_services/CallerService'
import { useHead } from '@vueuse/head'

const route = useRoute()
const User = useUserStore()
const cartStore = useCartStore()

interface Option {
  id: number
  size: string
}

interface Product {
  id: number
  name: string
  slug: string
  price: number
  picture: string[]
  description: string
  category: any
  options: Option[]
}

const product = ref<Product | null>(null)
const selectedImage = ref<string | null>(null)
const selectedOptionId = ref<number | null>(null)
const relatedProducts = ref<Product[]>([])

// Fonction pour transformer le nom du produit en slug SEO-friendly (lettres uniquement, minuscules)
function generateSlug(name: string): string {
  return name
    .toLowerCase()                  // tout en minuscules
    .trim()                         // supprime espaces avant/après
    .replace(/[0-9]/g, '')          // supprime tous les chiffres
    .replace(/[^\w\s-]/g, '')       // supprime caractères spéciaux
    .replace(/\s+/g, '-')           // remplace espaces par tirets
    .replace(/-+/g, '-')            // remplace tirets consécutifs par un seul
}


onMounted(async () => {
  try {
    const id = route.params.id
    const res = await Axios.get(`/api/products/${id}`)
    product.value = {
      id: Number(res.data.id),
      name: String(res.data.name),
      slug: generateSlug(String(res.data.name)), // Génération du slug
      price: Number(res.data.price),
      picture: Array.isArray(res.data.picture) ? res.data.picture : (res.data.picture ? [res.data.picture] : []),
      description: String(res.data.description),
      category: res.data.category,
      options: res.data.options ?? []
    }

    selectedImage.value = product.value.picture[0] ?? null
    selectedOptionId.value = null

    // Produits similaires
    const relatedRes = await Axios.get(`/api/products`)
    relatedProducts.value = relatedRes.data
      .filter((p: any) => p.id !== product.value?.id)
      .slice(0, 4)
  } catch (error) {
    console.error(error)
  }
})

// Génération dynamique du <head> dès que le produit est chargé
watch(product, (newProduct) => {
  if (newProduct) {
    useHead({
      title: `${newProduct.name} | Revolve Realm`,
      meta: [
        {
          name: 'description',
          content: `${newProduct.name} — ${newProduct.description.substring(0, 140)}`
        },
        {
          property: 'og:title',
          content: `${newProduct.name} | Revolve Realm`
        },
        {
          property: 'og:description',
          content: `${newProduct.description.substring(0, 140)}`
        },
        {
          property: 'og:image',
          content: newProduct.picture[0] ?? '/images/products/fallback.jpg'
        }
      ],
      link: [
        {
          rel: 'canonical',
          href: `https://revolverealm.com/products/${newProduct.slug}`
        }
      ]
    })
  }
})

function addProductToCart() {
  if (!User.isLogged || !product.value) return

  if (!selectedOptionId.value) {
    alert("Veuillez choisir une taille avant d’ajouter au panier !")
    return
  }

  const selectedOption = product.value.options.find(opt => opt.id === selectedOptionId.value)
  if (!selectedOption) return

  cartStore.addToCart({
    id: product.value.id,
    name: product.value.name,
    price: product.value.price,
    picture: product.value.picture[0] ?? null,
    optionId: selectedOption.id,
    size: selectedOption.size
  })

  alert(`${product.value.name} (${selectedOption.size}) ajouté au panier !`)
}

function selectImage(img: string) {
  selectedImage.value = img
}

function handleImageError(event: Event) {
  const target = event.target as HTMLImageElement
  target.src = '/images/products/fallback.jpg'
}
</script>

<template>
  <section class="product-detail-page" v-if="product">
    <div class="product-detail-container">
      <!-- Galerie images à gauche -->
      <div class="gallery-left">
        <div class="main-image">
          <img v-if="selectedImage" :src="selectedImage" :alt="product.name" @error="handleImageError" />
        </div>
        <div class="thumbnails">
          <img v-for="(img, index) in product.picture" :key="index" :src="img" :alt="product.name"
               class="thumbnail" @click="selectImage(img)" @error="handleImageError" />
        </div>
      </div>

      <!-- Infos produit à droite -->
      <div class="info-right">
        <h1 class="style-h1-product">{{ product.name }}</h1>
        <p class="price">{{ product.price.toFixed(2) }}€</p>

        <!-- Sélecteur de taille -->
        <div v-if="product.options.length">
          <label for="option-select">Taille :</label>
          <select id="option-select" v-model="selectedOptionId" class="option-select">
            <option :value="null" disabled>Choisir une taille</option>
            <option v-for="opt in product.options" :key="opt.id" :value="opt.id">
              {{ opt.size }}
            </option>
          </select>
        </div>

        <button v-if="User.isLogged" @click="addProductToCart" class="add-to-cart-btn">
          Ajouter au panier
        </button>

        <p class="description">{{ product.description }}</p>
      </div>
    </div>
  </section>

  <section v-else>
    Chargement du produit...
  </section>
</template>

<style scoped>
.product-detail-container {
    display: flex;
    width: 100%;
    height: auto;
    padding: 2rem 2rem;
    position: relative;
}

.gallery-left {
    width: 100%;
    height: auto;
    position: relative;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

.main-image {
    width: 70%;
    height: auto;
    display: flex;
    object-fit: contain;
    margin-bottom: 1rem;
    align-items: center;
    justify-content: center;
}

.main-image img {
    width: 30vw;
    height: 30vw;
    border: 3px solid var(--color-beige);
    object-fit: cover;
    position: relative;
    border-radius: 2vw;
}

.style-h1-product {
    color: var(--color-brown);
    font-family: nexa-book;
    margin: 0vw;
}

.thumbnails {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.thumbnail {
    width: 80px;
    height: 80px;
    object-fit: cover;
    cursor: pointer;
    border: 1px solid #ccc;
}

.info-right {
    width: 100%;
    height: auto;
    position: relative;
    display: flex;
    flex-direction: column;
}

.info-right p {
    font-weight: bold;
    margin: 0.5rem 0;
    color: var(--color-brown);
    font-family: 'nexa-book';
    font-size: 1.2vw;
}

.product-detail-page {
    width: 100%;
    height: auto;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
}

.price {
    font-weight: bold;
    margin: 0.5rem 0;
}

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
</style>
