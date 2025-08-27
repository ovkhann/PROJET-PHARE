<script setup lang="ts">
import { ref } from 'vue'

const name = ref('')
const price = ref(0)
const description = ref('')
const stock = ref(0)
const picture = ref<File | null>(null)

const handleFileChange = (e: Event) => {
  const target = e.target as HTMLInputElement
  if (target.files && target.files.length > 0) {
    picture.value = target.files[0]
  }
}

const submitProduct = async () => {
  const formData = new FormData()
  formData.append('name', name.value)
  formData.append('price', price.value.toString())
  formData.append('description', description.value)
  formData.append('stock', stock.value.toString())
  if (picture.value) {
    formData.append('picture', picture.value)
  }

  try {
    const res = await fetch('http://localhost:8000/api/products', {
      method: 'POST',
      body: formData,
      headers: {
        Accept: 'application/json',
      },
      credentials: 'include',
    })

    if (!res.ok) throw new Error('Erreur lors de l’envoi')

    alert('Produit ajouté avec succès !')
    // Optionnel : reset des champs
    name.value = ''
    price.value = 0
    description.value = ''
    stock.value = 0
    picture.value = null
  } catch (err) {
    console.error(err)
    alert('Erreur lors de la création du produit.')
  }
}
</script>

<template>
  <form @submit.prevent="submitProduct" class="add-product-form">
    <h2>Ajouter un produit</h2>

    <label>Nom</label>
    <input v-model="name" type="text" required />

    <label>Prix</label>
    <input v-model.number="price" type="number" required />

    <label>Description</label>
    <textarea v-model="description" required></textarea>

    <label>Stock</label>
    <input v-model.number="stock" type="number" required />

    <label>Image</label>
    <input type="file" @change="handleFileChange" accept="image/*" />

    <button type="submit">Ajouter</button>
  </form>
</template>

<style scoped>
.add-product-form {
  max-width: 500px;
  margin: auto;
  display: flex;
  flex-direction: column;
  gap: 1rem;
}
</style>
