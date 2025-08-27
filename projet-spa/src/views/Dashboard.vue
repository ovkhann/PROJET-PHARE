<script setup lang="ts">
import { ref, onMounted } from 'vue'

interface Product {
  id: number
  name: string
  picture: string | null
  price: number
  archived: number
  stock?: number
  description?: string
  created_at: string
}

interface Option {
  id: number
  name: string
}

interface Category {
  id: number
  name: string
}

const products = ref<Product[]>([])
const options = ref<Option[]>([])
const categories = ref<Category[]>([])

// Champs du formulaire
const name = ref('')
const price = ref<number | null>(null)
const stock = ref<number | null>(null)
const description = ref('')
const pictureFile = ref<File | null>(null)   // <-- ici
const archived = ref(false)
const option_ids = ref<number[]>([])
const category_id = ref<number | null>(null)

const errorMessage = ref('')

onMounted(async () => {
  try {
    // Produits
    const res = await fetch('http://localhost:8000/api/products')
    if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`)
    const data: Product[] = await res.json()
    products.value = data.filter(p => p.archived === 0)

    // Options
    const optRes = await fetch('http://localhost:8000/api/options')
    if (optRes.ok) {
      options.value = await optRes.json()
    }

    // Catégories
    const catRes = await fetch('http://localhost:8000/api/categories')
    if (catRes.ok) {
      categories.value = await catRes.json()
    }
  } catch (error) {
    console.error('Erreur lors de la récupération des données :', error)
  }
})

function handleFileChange(event: Event) {
  const target = event.target as HTMLInputElement
  if (target.files && target.files.length > 0) {
    pictureFile.value = target.files[0]
  } else {
    pictureFile.value = null
  }
}

async function addProduct() {
  errorMessage.value = ''

  if (
    !name.value ||
    price.value === null ||
    stock.value === null ||
    !description.value ||
    !pictureFile.value ||
    option_ids.value.length === 0 ||
    category_id.value === null
  ) {
    errorMessage.value = 'Merci de remplir tous les champs.'
    return
  }

  // Création FormData
  const formData = new FormData()
  formData.append('name', name.value)
  formData.append('price', price.value.toString())
  formData.append('stock', stock.value.toString())
  formData.append('description', description.value)
  formData.append('archived', archived.value ? '1' : '0')
  formData.append('category_id', category_id.value.toString())
  pictureFile.value && formData.append('picture', pictureFile.value)

  // Pour option_ids, comme c’est un tableau, on fait append pour chaque id
  option_ids.value.forEach(id => {
    formData.append('option_ids[]', id.toString())
  })

  try {
    const response = await fetch('http://localhost:8000/api/products', {
      method: 'POST',
      body: formData, // pas besoin de Content-Type, fetch s’en occupe
    })

    if (!response.ok) {
      const errorData = await response.json()
      errorMessage.value = 'Erreur API: ' + (errorData.message || response.statusText)
      return
    }

    const createdProduct: Product = await response.json()
    products.value.push(createdProduct)

    // Reset formulaire
    name.value = ''
    price.value = null
    stock.value = null
    description.value = ''
    pictureFile.value = null
    archived.value = false
    option_ids.value = []
    category_id.value = null
  } catch (error) {
    errorMessage.value = 'Erreur lors de l\'ajout du produit.'
    console.error(error)
  }
}
</script>

<template>
  <div class="admin-dashboard">
    <h1>Dashboard Produits</h1>

    <!-- Formulaire d'ajout -->
    <form @submit.prevent="addProduct" class="add-product-form">
      <h2>Ajouter un produit</h2>

      <div>
        <label for="name">Nom</label>
        <input id="name" v-model="name" type="text" />
      </div>

      <div>
        <label for="price">Prix (€)</label>
        <input id="price" v-model.number="price" type="number" min="0" step="0.01" />
      </div>

      <div>
        <label for="stock">Stock</label>
        <input id="stock" v-model.number="stock" type="number" min="0" />
      </div>

      <div>
        <label for="description">Description</label>
        <textarea id="description" v-model="description"></textarea>
      </div>

      <div>
        <label for="picture">Image du produit</label>
        <input id="picture" type="file" @change="handleFileChange" accept="image/*" />
      </div>

      <div>
        <label for="archived">Archivé</label>
        <input id="archived" v-model="archived" type="checkbox" />
      </div>

      <div>
        <label>Tailles</label>
        <div v-for="option in options" :key="option.id">
          <input type="checkbox" :value="option.id" v-model="option_ids" />
          {{ option.name }}
        </div>
      </div>

      <div>
        <label for="category_id">Catégorie</label>
        <select id="category_id" v-model.number="category_id">
          <option disabled value="">-- Sélectionner une catégorie --</option>
          <option v-for="cat in categories" :key="cat.id" :value="cat.id">
            {{ cat.name }}
          </option>
        </select>
      </div>

      <button type="submit">Ajouter</button>
      <p v-if="errorMessage" style="color: red;">{{ errorMessage }}</p>
    </form>

    <!-- Liste des produits -->
    <h2>Liste des produits</h2>
    <div v-if="products.length === 0">
      Aucun produit disponible.
    </div>
    <ul v-else>
      <li v-for="product in products" :key="product.id">
        {{ product.name }} - {{ product.price }}€
      </li>
    </ul>
  </div>
</template>

<style scoped>
.admin-dashboard {
  max-width: 700px;
  margin: auto;
  padding: 20px;
}

.add-product-form {
  margin-bottom: 2rem;
  padding: 1rem;
  border: 1px solid #ddd;
  border-radius: 8px;
}

.add-product-form div {
  margin-bottom: 0.7rem;
}

.add-product-form label {
  display: block;
  font-weight: bold;
  margin-bottom: 0.3rem;
}

.add-product-form input[type="text"],
.add-product-form input[type="number"],
.add-product-form textarea,
.add-product-form select {
  width: 100%;
  padding: 6px;
  box-sizing: border-box;
  border-radius: 4px;
  border: 1px solid #ccc;
}

.add-product-form button {
  background-color: #4caf50;
  border: none;
  color: white;
  padding: 10px 18px;
  cursor: pointer;
  font-weight: bold;
  border-radius: 5px;
  transition: background-color 0.3s ease;
}

.add-product-form button:hover {
  background-color: #45a049;
}
</style>
