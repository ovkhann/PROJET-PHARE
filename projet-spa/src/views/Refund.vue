<script setup lang="ts">
import { useHead } from '@vueuse/head'

useHead({
  title: 'Refund Policy | Revolve Realm',
  meta: [
    {
      name: 'description',
      content: 'Read the refund policy of Revolve Realm. Learn how to return products, request refunds, and understand our return procedures.'
    },
    {
      property: 'og:title',
      content: 'Refund Policy | Revolve Realm'
    },
    {
      property: 'og:description',
      content: 'Read the refund policy of Revolve Realm. Learn how to return products, request refunds, and understand our return procedures.'
    }
  ],
  link: [
    { rel: 'canonical', href: 'https://revolverealm.com/refund-policy' }
  ]
})
</script>

<template>
  <section class="refund-policy">
    <h1>Refund Policy</h1>
    
    <p>At Revolve Realm, we want you to be completely satisfied with your purchase. If you are not satisfied, our refund policy explains how to return products and request a refund.</p>

    <h2>Eligibility for Refunds</h2>
    <p>Products can be returned within 14 days of delivery, provided they are unused, in their original packaging, and in the same condition as received.</p>

    <h2>How to Request a Refund</h2>
    <ol>
      <li>Contact our support team at <a href="mailto:admin@revolverealm.com">admin@revolverealm.com</a> with your order details.</li>
      <li>Include the reason for the return and whether you would like a replacement or a refund.</li>
      <li>Follow the instructions provided by our support team to ship the product back.</li>
    </ol>

    <h2>Refund Processing</h2>
    <p>Once we receive the returned product, we will inspect it and process the refund to your original payment method within 5-10 business days.</p>

    <h2>Exclusions</h2>
    <p>Products that have been used, damaged, or altered after delivery are not eligible for a refund.</p>

    <h2>Contact</h2>
    <p>If you have any questions about our refund policy, please contact us at <a href="mailto:admin@revolverealm.com">admin@revolverealm.com</a>.</p>
  </section>
</template>

<style scoped>
.refund-policy {
  max-width: 900px;
  margin: 2rem auto;
  padding: 2rem;
  font-family: 'Nexa Book', sans-serif;
  color: var(--color-brown);
}

.refund-policy h1 {
  font-size: 2rem;
  margin-bottom: 1rem;
}

.refund-policy h2 {
  font-size: 1.5rem;
  margin-top: 1.5rem;
  margin-bottom: 0.5rem;
}

.refund-policy p,
.refund-policy ol,
.refund-policy ul {
  font-size: 1rem;
  line-height: 1.6;
}

.refund-policy a {
  color: var(--color-beige);
  text-decoration: underline;
}

.refund-policy ol {
  padding-left: 1.5rem;
  list-style-type: decimal;
}
</style>
