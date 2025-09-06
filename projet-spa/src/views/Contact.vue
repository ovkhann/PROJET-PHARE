<template>
  <form @submit.prevent="sendMessage" class="space-y-4 max-w-md mx-auto">
    <input v-model="form.name" type="text" placeholder="Votre nom" required class="border p-2 w-full" />
    <input v-model="form.email" type="email" placeholder="Votre email" required class="border p-2 w-full" />
    <input v-model="form.subject" type="text" placeholder="Sujet" class="border p-2 w-full" />
    <textarea v-model="form.message" placeholder="Votre message" required class="border p-2 w-full"></textarea>
    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Envoyer</button>

    <p v-if="successMessage" class="text-green-600 mt-2">{{ successMessage }}</p>
    <p v-if="errorMessage" class="text-red-600 mt-2">{{ errorMessage }}</p>
  </form>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import Axios from '@/_services/CallerService'
import { useHead } from '@vueuse/head'

useHead({
  title: 'Contact | Revolve Realm',
  meta: [
    { name: 'description', content: "Get in touch with Revolve Realm. For questions, collaborations, or support, reach out to our team — we’re here to help you." },
    { property: 'og:title', content: 'Contact | Revolve Realm' }
  ],
  link: [
    { rel: 'canonical', href: 'https://revolverealm.com/contact'  }
  ]
})

// Formulaire
const form = ref({
  name: '',
  email: '',
  subject: '',
  message: ''
})

// Messages d'état
const successMessage = ref('')
const errorMessage = ref('')

const sendMessage = async () => {
  successMessage.value = ''
  errorMessage.value = ''

  try {
    // POST vers Laravel API publique
    const res = await Axios.post('http://localhost:8000/api/messages', form.value, {
      headers: { 'Content-Type': 'application/json' }
    })

    // Message de succès et reset du formulaire
    successMessage.value = res.data.message
    form.value = { name: '', email: '', subject: '', message: '' }

  } catch (err: any) {
    // Gestion des erreurs
    if (err.response) {
      errorMessage.value = err.response.data.message || 'Erreur lors de l\'envoi du message.'
    } else {
      errorMessage.value = 'Impossible de contacter le serveur.'
    }
    console.error(err)
  }
}
</script>
