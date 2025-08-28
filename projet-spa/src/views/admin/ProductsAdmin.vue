<script setup lang="ts">
import { ref, onMounted } from 'vue'
import Caller from '@/_services/CallerService'

/** --- Types --- **/
interface Product {
  id: number
  name: string
  description: string
  price: number
  picture: string | null
}

interface ProductForm {
  name: string
  description: string
  price: number
  picture: File | null
}

/** --- State --- **/
const products = ref<Product[]>([])
const form = ref<ProductForm>({
  name: '',
  description: '',
  price: 0,
  picture: null
})
const editingProduct = ref<Product | null>(null)

/** --- Load products --- **/
async function fetchProducts() {
  try {
    const res = await Caller.get('/api/products')
    products.value = res.data.map((p: any) => ({
      id: Number(p.id),
      name: String(p.name),
      description: String(p.description ?? ''),
      price: Number(p.price),
      picture: p.picture ?? null
    }))
  } catch (error) {
    console.error('Erreur lors du chargement des produits', error)
  }
}

/** --- Create or Update --- **/
async function saveProduct() {
  try {
    const formData = new FormData()
    formData.append('name', form.value.name)
    formData.append('description', form.value.description)
    formData.append('price', form.value.price.toString())

    if (form.value.picture) {
      formData.append('picture', form.value.picture)
    }

    if (editingProduct.value) {
      await Caller.post(`/api/products/${editingProduct.value.id}?_method=PUT`, formData, {
        headers: { 'Content-Type': 'multipart/form-data' }
      })
    } else {
      await Caller.post('/api/products', formData, {
        headers: { 'Content-Type': 'multipart/form-data' }
      })
    }

    await fetchProducts()
    resetForm()
  } catch (error) {
    console.error('Erreur lors de la sauvegarde du produit', error)
  }
}

/** --- Delete --- **/
async function deleteProduct(id: number) {
  if (!confirm('Supprimer ce produit ?')) return
  try {
    await Caller.delete(`/api/products/${id}`)
    await fetchProducts()
  } catch (error) {
    console.error('Erreur lors de la suppression du produit', error)
  }
}

/** --- Edit --- **/
function editProduct(product: Product) {
  editingProduct.value = product
  form.value = {
    name: product.name,
    description: product.description,
    price: product.price,
    picture: null // l'image doit être rechargée si on veut la changer
  }
}

/** --- Reset form --- **/
function resetForm() {
  editingProduct.value = null
  form.value = {
    name: '',
    description: '',
    price: 0,
    picture: null
  }
}

onMounted(fetchProducts)
</script>

<template>
  <div class="products-admin">
    <h2>Gestion des Produits</h2>

    <!-- Liste des produits -->
    <table class="products-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Nom</th>
          <th>Description</th>
          <th>Prix</th>
          <th>Image</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="product in products" :key="product.id">
          <td>{{ product.id }}</td>
          <td>{{ product.name }}</td>
          <td>{{ product.description }}</td>
          <td>{{ product.price.toFixed(2) }} €</td>
          <td>
            <img
              v-if="product.picture"
              :src="product.picture"
              alt="Produit"
              width="60"
            />
          </td>
          <td>
            <button @click="editProduct(product)">✏️ Modifier</button>
            <button @click="deleteProduct(product.id)">🗑️ Supprimer</button>
          </td>
        </tr>
      </tbody>
    </table>

    <!-- Formulaire -->
    <h3>{{ editingProduct ? 'Modifier le produit' : 'Créer un produit' }}</h3>
    <form @submit.prevent="saveProduct" class="product-form">
      <input v-model="form.name" placeholder="Nom du produit" required />
      <textarea v-model="form.description" placeholder="Description"></textarea>
      <input v-model.number="form.price" type="number" step="0.01" placeholder="Prix" required />

      <!-- Upload image -->
      <input type="file" @change="e => form.picture = (e.target as HTMLInputElement).files?.[0] || null" />

      <button type="submit">{{ editingProduct ? 'Mettre à jour' : 'Créer' }}</button>
      <button type="button" @click="resetForm" v-if="editingProduct">Annuler</button>
    </form>
  </div>
</template>

<style scoped>
.products-admin {
  padding: 20px;
}

.products-table {
  width: 100%;
  border-collapse: collapse;
  margin-bottom: 20px;
}

.products-table th,
.products-table td {
  border: 1px solid #ccc;
  padding: 8px;
  text-align: left;
}

.products-table img {
  border-radius: 6px;
}

.product-form {
  display: flex;
  flex-direction: column;
  gap: 10px;
  max-width: 400px;
}

.product-form input,
.product-form textarea {
  padding: 8px;
  border: 1px solid #ccc;
  border-radius: 4px;
}

.product-form button {
  padding: 8px 12px;
  border: none;
  background: #333;
  color: #fff;
  border-radius: 4px;
  cursor: pointer;
}
</style>
