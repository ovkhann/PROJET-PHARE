<script setup lang="ts">
import { useHead } from '@vueuse/head'

useHead({
  title: 'Legal Notice | Revolve Realm',
  meta: [
    {
      name: 'description',
      content: 'Read the legal notice of Revolve Realm. Get all information about our company, registration, and website management.'
    },
    {
      property: 'og:title',
      content: 'Legal Notice | Revolve Realm'
    },
    {
      property: 'og:description',
      content: 'Read the legal notice of Revolve Realm. Get all information about our company, registration, and website management.'
    }
  ],
  link: [
    { rel: 'canonical', href: 'https://revolverealm.com/legal-notice' }
  ]
})
</script>

<template>
  <section class="legal-notice">
    <h1>Legal Notice</h1>
    
    <h2>Company Information</h2>
    <p>Revolve Realm</p>
    <p>Address: La Roche sur Yon, France</p>
    <p>Email: <a href="mailto:admin@revolverealm.com">admin@revolverealm.com</a></p>
    <p>Phone: 06 11 83 09 15</p>
    <p>Registration Number: 123 456 789</p>
    <p>VAT Number: FR12 345678901</p>

    <h2>Website Hosting</h2>
    <p>Website hosted by O2Switch</p>
    <p>Address: Chem. des Pardiaux, 63000 Clermont-Ferrand, France</p>
    <p>Website: <a href="https://www.o2switch.fr/" target="_blank">https://www.o2switch.fr/</a></p>

    <h2>Intellectual Property</h2>
    <p>All content on this website, including text, images, logos, and designs, are the property of Revolve Realm and protected by copyright law. Any reproduction or distribution without permission is prohibited.</p>

    <h2>Limitation of Liability</h2>
    <p>Revolve Realm cannot be held liable for any damages resulting from the use of the website, including direct or indirect loss of data, profits, or other damages.</p>

    <h2>Applicable Law</h2>
    <p>These legal notices are governed by the laws of the country in which Revolve Realm is registered.</p>

    <h2>Contact</h2>
    <p>If you have any questions regarding these legal notices, please contact us at <a href="mailto:admin@revolverealm.com">admin@revolverealm.com</a>.</p>
  </section>
</template>

<style scoped>
.legal-notice {
  max-width: 900px;
  margin: 2rem auto;
  padding: 2rem;
  font-family: 'Nexa Book', sans-serif;
  color: var(--color-brown);
}

.legal-notice h1 {
  font-size: 2rem;
  margin-bottom: 1rem;
}

.legal-notice h2 {
  font-size: 1.5rem;
  margin-top: 1.5rem;
  margin-bottom: 0.5rem;
}

.legal-notice p,
.legal-notice ul {
  font-size: 1rem;
  line-height: 1.6;
}

.legal-notice a {
  color: var(--color-beige);
  text-decoration: underline;
}
</style>
