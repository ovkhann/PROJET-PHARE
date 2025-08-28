<script setup lang="ts">
import { ref, onMounted } from 'vue'
import Caller from '@/_services/CallerService'

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

const products = ref<Product[]>([])
const form = ref<ProductForm>({
  name: '',
  description: '',
  price: 0,
  picture: null
})
const editingProduct = ref<Product | null>(null)

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

async function saveProduct() {
  try {
    const formData = new FormData()
    formData.append('name', form.value.name)
    formData.append('description', form.value.description)
    formData.append('price', form.value.price.toString())
    if (form.value.picture) formData.append('picture', form.value.picture)

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

async function deleteProduct(id: number) {
  if (!confirm('Supprimer ce produit ?')) return
  try {
    await Caller.delete(`/api/products/${id}`)
    await fetchProducts()
  } catch (error) {
    console.error('Erreur lors de la suppression du produit', error)
  }
}

function editProduct(product: Product) {
  editingProduct.value = product
  form.value = {
    name: product.name,
    description: product.description,
    price: product.price,
    picture: null
  }
}

function resetForm() {
  editingProduct.value = null
  form.value = { name: '', description: '', price: 0, picture: null }
}

onMounted(fetchProducts)
</script>

<template>
  <div class="products-admin">
    <header class="admin-header">
      <h2>Gestion des Produits</h2>
      <button class="btn-new" @click="resetForm">+ Nouveau Produit</button>
    </header>

    <!-- Tableau des produits -->
    <div class="table-wrapper">
      <table class="products-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Image</th>
            <th>Nom</th>
            <th>Description</th>
            <th>Prix</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="product in products" :key="product.id">
            <td>{{ product.id }}</td>
            <td>
              <div class="img-wrapper">
                <img :src="product.picture ?? '/images/products/fallback.jpg'" alt="Produit" />
              </div>
            </td>
            <td>{{ product.name }}</td>
            <td>{{ product.description }}</td>
            <td>{{ product.price.toFixed(2) }} €</td>
            <td>
              <button class="btn-edit" @click="editProduct(product)">✏️</button>
              <button class="btn-delete" @click="deleteProduct(product.id)">🗑️</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Formulaire Création / Edition -->
    <div class="form-panel">
      <h3>{{ editingProduct ? 'Modifier le produit' : 'Créer un produit' }}</h3>
      <form @submit.prevent="saveProduct">
        <input v-model="form.name" placeholder="Nom du produit" required />
        <textarea v-model="form.description" placeholder="Description"></textarea>
        <input v-model.number="form.price" type="number" step="0.01" placeholder="Prix" required />
        <input type="file" @change="e => form.picture = (e.target as HTMLInputElement).files?.[0] || null" />
        <div class="form-buttons">
          <button type="submit">{{ editingProduct ? 'Mettre à jour' : 'Créer' }}</button>
          <button type="button" @click="resetForm" v-if="editingProduct">Annuler</button>
        </div>
      </form>
    </div>
  </div>
</template>

<style scoped>
.products-admin {
  padding: 30px;
  font-family: 'Arial', sans-serif;
}

.admin-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
}

.btn-new {
  background: #0073aa;
  color: #fff;
  padding: 8px 16px;
  border-radius: 4px;
  border: none;
  cursor: pointer;
}

.btn-new:hover {
  background: #005f8d;
}

.table-wrapper {
  overflow-x: auto;
  margin-bottom: 30px;
}

.products-table {
  width: 100%;
  border-collapse: collapse;
  min-width: 700px;
}

.products-table th,
.products-table td {
  padding: 12px 10px;
  text-align: left;
  border-bottom: 1px solid #ccc;
}

.products-table th {
  background: #f0f0f0;
  font-weight: 600;
}

.img-wrapper {
  width: 60px;
  height: 60px;
  overflow: hidden;
  border-radius: 6px;
}

.img-wrapper img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.btn-edit,
.btn-delete {
  padding: 6px 10px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  margin-right: 5px;
}

.btn-edit {
  background: #f0ad4e;
  color: #fff;
}

.btn-edit:hover {
  background: #ec971f;
}

.btn-delete {
  background: #d9534f;
  color: #fff;
}

.btn-delete:hover {
  background: #c9302c;
}

.form-panel {
  background: #fff;
  padding: 20px;
  border-radius: 8px;
  max-width: 450px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.form-panel input,
.form-panel textarea {
  padding: 10px;
  border-radius: 4px;
  border: 1px solid #ccc;
  width: 100%;
}

textarea {
  resize: vertical;
}

.form-buttons {
  display: flex;
  gap: 10px;
  margin-top: 10px;
}

.form-buttons button {
  flex: 1;
  padding: 8px 12px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.form-buttons button:first-child {
  background: #0073aa;
  color: #fff;
}

.form-buttons button:first-child:hover {
  background: #005f8d;
}

.form-buttons button:last-child {
  background: #ccc;
}

.form-buttons button:last-child:hover {
  background: #999;
}
</style>
