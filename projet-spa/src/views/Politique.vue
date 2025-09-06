<script setup lang="ts">
import { useHead } from '@vueuse/head'

useHead({
  title: 'Privacy Policy | Revolve Realm',
  meta: [
    {
      name: 'description',
      content: 'Read the privacy policy of Revolve Realm. Learn how we collect, use, and protect your personal information while you shop our streetwear collection.'
    },
    {
      property: 'og:title',
      content: 'Privacy Policy | Revolve Realm'
    },
    {
      property: 'og:description',
      content: 'Read the privacy policy of Revolve Realm. Learn how we collect, use, and protect your personal information while you shop our streetwear collection.'
    }
  ],
  link: [
    { rel: 'canonical', href: 'https://revolverealm.com/privacy-policy' }
  ]
})
</script>

<template>
  <section class="privacy-policy">
    <h1>Privacy Policy</h1>
    <p>At Revolve Realm, your privacy is important to us. This Privacy Policy explains how we collect, use, and protect your personal information when you use our website.</p>

    <h2>Information We Collect</h2>
    <ul>
      <li>Personal details such as name, email, billing and shipping address when you register or make a purchase.</li>
      <li>Payment information necessary to process transactions securely.</li>
      <li>Browsing data, such as pages visited and interactions, to improve your shopping experience.</li>
    </ul>

    <h2>How We Use Your Information</h2>
    <ul>
      <li>To process and fulfill your orders.</li>
      <li>To communicate with you about your account, purchases, and promotions.</li>
      <li>To improve our website and services based on your usage and feedback.</li>
    </ul>

    <h2>Data Protection</h2>
    <p>We implement appropriate technical and organizational measures to protect your data from unauthorized access, alteration, disclosure, or destruction.</p>

    <h2>Third-Party Services</h2>
    <p>We may use trusted third-party services for payment processing, analytics, and marketing. These services are bound by strict confidentiality agreements.</p>

    <h2>Your Rights</h2>
    <p>You have the right to access, correct, or delete your personal information. You may also opt out of marketing communications at any time.</p>

    <h2>Changes to This Policy</h2>
    <p>We may update this Privacy Policy from time to time. Changes will be posted on this page with the date of the latest revision.</p>

    <h2>Contact Us</h2>
    <p>If you have questions or concerns about your privacy, please contact us at <a href="mailto:admin@revolverealm.com">admin@revolverealm.com</a>.</p>
  </section>
</template>

<style scoped>
.privacy-policy {
  max-width: 900px;
  margin: 2rem auto;
  padding: 2rem;
  font-family: 'Nexa Book', sans-serif;
  color: var(--color-brown);
}

.privacy-policy h1 {
  font-size: 2rem;
  margin-bottom: 1rem;
}

.privacy-policy h2 {
  font-size: 1.5rem;
  margin-top: 1.5rem;
  margin-bottom: 0.5rem;
}

.privacy-policy p,
.privacy-policy ul {
  font-size: 1rem;
  line-height: 1.6;
}

.privacy-policy ul {
  padding-left: 1.5rem;
  list-style-type: disc;
}

.privacy-policy a {
  color: var(--color-beige);
  text-decoration: underline;
}
</style>
