<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import Caller from '@/_services/CallerService'
import { useCartStore } from '@/stores/cart'
import { useUserStore } from '@/stores/User'

const route = useRoute()
const User = useUserStore()
const cartStore = useCartStore()

interface Product {
    id: number
    name: string
    price: number
    picture: string[]
    description: string
    category: any
    options: any[]
}

const product = ref<Product | null>(null)
const selectedImage = ref<string | null>(null)

onMounted(async () => {
    try {
        const id = route.params.id
        const res = await Caller.get(`/api/products/${id}`)
        product.value = {
            id: Number(res.data.id),
            name: String(res.data.name),
            price: Number(res.data.price),
            picture: Array.isArray(res.data.picture) ? res.data.picture : (res.data.picture ? [res.data.picture] : []),
            description: String(res.data.description),
            category: res.data.category,
            options: res.data.options || []
        }

        selectedImage.value = product.value.picture[0] ?? null
    } catch (error) {
        console.error(error)
    }
})

function addProductToCart() {
    if (!User.isLogged || !product.value) return
    cartStore.addToCart({
        id: product.value.id,
        name: product.value.name,
        price: product.value.price,
        picture: product.value.picture[0] ?? null
    })
    alert(`${product.value.name} ajouté au panier !`)
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
                <img v-if="selectedImage" :src="selectedImage" :alt="product.name" class="main-image"
                    @error="handleImageError" />

                <div class="thumbnails">
                    <img v-for="(img, index) in product.picture" :key="index" :src="img" :alt="product.name"
                        class="thumbnail" @click="selectImage(img)" @error="handleImageError" />
                </div>
            </div>

            <!-- Infos produit à droite -->
            <div class="info-right">
                <h1>{{ product.name }}</h1>
                <p class="price">{{ product.price.toFixed(2) }}€</p>
                <p class="description">{{ product.description }}</p>

                <div v-if="product.options.length">
                    <h3>Options :</h3>
                    <ul>
                        <li v-for="option in product.options" :key="option.id">{{ option.name }}</li>
                    </ul>
                </div>

                <button v-if="User.isLogged" @click="addProductToCart" class="add-to-cart-btn">
                    Ajouter au panier
                </button>
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
    gap: 2rem;
    flex-wrap: wrap;
    width: 100%;
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem 1rem;
}

.gallery-left {
    flex: 1;
    max-width: 500px;
}

.main-image {
    width: 100%;
    height: auto;
    object-fit: contain;
    border: 1px solid #ddd;
    margin-bottom: 1rem;
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
    flex: 1;
    min-width: 300px;
}

.price {
    font-weight: bold;
    margin: 0.5rem 0;
}

.add-to-cart-btn {
    margin-top: 1rem;
    padding: 0.5rem 1rem;
    background-color: #222;
    color: white;
    border: none;
    cursor: pointer;
}
</style>
