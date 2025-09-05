<script setup lang="ts">
import { useRouter } from 'vue-router';
import { useUserStore } from '@/stores/User';
import * as AuthService from '@/_services/AuthService';
import { ref } from 'vue'
import { useCartStore } from '@/stores/cart'

const cartStore = useCartStore()
const isCartOpen = ref(false)

function toggleCart() {
  isCartOpen.value = !isCartOpen.value
}

const router = useRouter();
const User = useUserStore();

async function logoutUser() {
  await AuthService.logout();
  router.push('/login');
}
</script>

<template>
  <div class="app-wrapper">
    <!-- HEADER -->
    <header>
      <div class="container-header">
        <div class="top-bloc-header">
          <div class="logo-header">
            <img alt="Revolve Realm logo" src="@/assets/images/RRlogobrown.png" />
          </div>
          <nav class="nav-auth">
            <div class="container-account" v-if="User.isLogged">
              <div class="account">
                <button class="deconnexion-button" @click="logoutUser">DÉCONNEXION</button>
                <span>{{ User.user?.email }}</span>
              </div>
              <div class="cart-icon" @click="toggleCart">
                <img src="@/assets/images/cart-icon.svg" alt="Panier" />
                <span v-if="cartStore.totalItems > 0" class="cart-badge">{{ cartStore.totalItems }}</span>
              </div>
            </div>
            <RouterLink class="connexion-header" to="/login" v-else>CONNEXION</RouterLink>
          </nav>
        </div>
        <div class="bottom-bloc-header">
          <div class="wrapper">
            <nav>
              <RouterLink to="/">HOME</RouterLink>
              <RouterLink to="/shop">SHOP</RouterLink>
              <RouterLink to="/contact">CONTACT</RouterLink>
            </nav>
          </div>
        </div>
      </div>
    </header>

    <!-- MAIN CONTENT -->
    <main class="main-content">
      <RouterView />
    </main>

    <!-- PANIER SLIDE-IN -->
    <div class="cart-panel" :class="{ open: isCartOpen }">
      <div class="container-titre-croix">
        <h3 id="h3-cart">Mon Panier</h3>
        <button class="cart-close-btn" @click="toggleCart">✕</button>
      </div>

      <!-- Panier vide -->
      <div id="panier-vide" v-if="cartStore.items.length === 0">Votre panier est vide.</div>

      <!-- Liste des produits -->
      <div v-else class="cart-items">
        <div v-for="item in cartStore.items" :key="`${item.productId}-${item.optionId ?? 'noopt'}`" class="cart-item">
          <div class="container-image-produit-panier">
            <img :src="item.picture ?? '/images/products/fallback.jpg'" :alt="item.name" />
          </div>
          <div class="cart-item-info">
            <p>{{ item.name }}</p>
            <!-- Affichage de la taille -->
            <p v-if="item.size">Taille : {{ item.size }}</p>

            <p>
              {{ item.price.toFixed(2) }}€ x
              <input type="number" min="1" v-model.number="item.quantity"
                @change="cartStore.updateQuantity(item.productId, item.optionId ?? null, item.quantity)" />
              <button class="btn-supprimer" @click="cartStore.removeItem(item.productId, item.optionId ?? null)">
                Supprimer
              </button>
            </p>
          </div>
        </div>
      </div>

      <!-- Total -->
      <div class="cart-total">
        <span>Total : {{ cartStore.totalPrice.toFixed(2) }}€</span>

        <!-- Vider le panier -->
        <div v-if="cartStore.items.length > 0" class="cart-actions">
          <button class="btn-clear-cart" @click="cartStore.clearCart()">Vider le panier</button>
        </div>
      </div>
    </div>

    <!-- FOOTER -->
    <footer>
      <div class="container-footer">
        <div class="container-logo-footer">
          <img src="@/assets/images/RRlogobrown.png" alt="Logo Revolve Realm">
        </div>
        <div class="social-media">
          <span class="titre-social">OUR SOCIAL MEDIA</span>
          <div class="container-reseaux">
            <a href="https://www.instagram.com/revolverealm/" class="container-logo-instagram" target="_blank">
              <img src="@/assets/images/instagram-logo.svg" alt="Logo insta">
            </a>
            <a href="https://www.tiktok.com/@revolverealm" class="container-logo-tiktok" target="_blank">
              <img src="@/assets/images/logo-tiktok.svg" alt="Logo TikTok">
            </a>
          </div>
        </div>
        <div class="container-subscribe">
          <h3 class="titre-subscribe">SUBSCRIBE</h3>
          <span class="text-style">
            Keep up to date with the latest releases, <br>early access passwords & exclusive deals
          </span>
          <input type="text" class="input" placeholder="Email" />
          <button type="submit" class="button">SUBSCRIBE</button>
        </div>
      </div>
      <div class="bottom-container-footer">
        <span class="politique"><a href="/privacy-policy"> PRIVACY POLICY</a> | <a href="/legal-notice">LEGAL NOTICE</a> | <a href="/refund-policy">REFUND POLICY</a></span>
        <span class="revolve-realm">REVOLVE REALM™ | 2025</span>
      </div>
    </footer>
  </div>
</template>


<style scoped>
footer {
  display: flex;
  width: 100%;
  height: auto;
  position: relative;
  flex-direction: column;
  justify-content: center;
  padding: 1vw;
  background: var(--color-beige);
  align-items: center;
}

.revolve-realm {
  font-family: nexa-bold;
}

.politique a {
  color: var(--color-brown) !important;
  font-family: 'nexa-bold';
}

.container-image-produit-panier {
  width: fit-content;
  height: auto;
  position: relative;
  display: flex;
}

#panier-vide {
  margin: 1vw;
  font-family: nexa-light;
  color: var(--color-beige);
}

.cart-item-info {
  display: flex;
  flex-direction: column;
  justify-content: center;
  width: 60%;
  gap: 1vw;
  height: auto;
  position: relative;
}

.cart-item-info p {
  margin: 0vw;
  display: flex;
  gap: 1vw;
  font-family: sans-serif;
  align-items: center;
}

.container-titre-croix {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.cart-total {
  margin: 1vw;
  font-size: var(--font-size-texte);
  font-family: sans-serif;
  color: var(--color-beige);
  display: flex;
  justify-content: space-evenly;
  align-items: center;
}

.cart-total span {
  font-weight: bold;
}


.container-image-produit-panier img {
  width: 6vw;
  height: 6vw;
  border: solid 3px var(--color-creme);
  position: relative;
  border-radius: 0.7vw;
  object-fit: cover;
}

#h3-cart {
  margin: 1vw;
  font-family: 'nexa-book';
  font-size: 2vw;
}

.container-footer {
  display: flex;
  justify-content: space-around;
  position: relative;
  width: 100%;
  align-items: center;
  flex-direction: row;
}

.container-logo-footer {
  width: 8%;
  display: flex;
  height: auto;
  position: relative;
}

.app-wrapper {
  display: flex;
  flex-direction: column;
  width: 100%;
  min-height: 100vh;
}

.main-content {
  flex: 1;
  display: flex;
  margin-top: 10.7vw;
  flex-direction: column;
  align-items: center;
  justify-content: center;
}

.container-logo-footer img {
  width: 100%;
  object-fit: cover;
  height: 5.5vw;
  object-position: center;
}

.container-logo-instagram img:hover,
.container-logo-tiktok img:hover {
  transform: scale(1.2);
  filter: drop-shadow(0px 2px grey);
}

.social-media {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  width: 15%;
  height: auto;
}

.titre-social {
  color: var(--color-brown);
}

.titre-subscribe {
  margin-bottom: 0vw;
}

.container-subscribe {
  color: var(--color-brown);
  display: flex;
  flex-direction: column;
  text-align: center;
  justify-content: center;
  gap: 0.5vw;
  align-items: center;
}

.container-logo-instagram {
  width: 54px;
  height: 50px;
  display: flex;
  justify-content: center;
  align-items: center;
  overflow: hidden;
  transition: all 0.3s ease-in-out;
  position: relative;
}

.container-logo-tiktok {
  width: 40px;
  height: 50px;
  display: flex;
  justify-content: center;
  align-items: center;
  overflow: hidden;
  transition: all 0.3s ease-in-out;
  position: relative;
}

.container-logo-instagram img {
  width: 100%;
  height: auto;
  object-fit: cover;
  transition: transform 0.3s ease-in-out;
}

.container-logo-tiktok img {
  width: 100%;
  height: auto;
  object-fit: cover;
  transition: transform 0.3s ease-in-out;
}

.container-reseaux {
  display: flex;
  width: 100%;
  justify-content: center;
  align-items: center;
}

.bottom-container-footer {
  width: 100%;
  color: var(--color-brown);
  height: auto;
  display: flex;
  margin-top: 2.5vw;
  padding: 0vw 2vw;
  justify-content: space-between;
}

header {
  max-height: 100vh;
  width: 100%;
  position: fixed;
  z-index: 999;
  padding-top: 1vw;
  padding-bottom: 1vw;
  background-color: #F2EADF;
  justify-content: center;
  align-items: center;
  display: flex;
  box-shadow: 0px 0px 8px black;
}

.deconnexion-button {
  width: fit-content;
  text-decoration: none;
  background: none;
  color: var(--color-brown);
  transition: 0.4s;
  font-family: nexa-bold;
  padding: 0 0.5vw;
  border: none;
  border-left: 1px solid var(--color-brown);
  border-right: 1px solid var(--color-brown);
}

.deconnexion-button:hover {
  background-color: #D9CCC1;
}

.container-account {
  position: absolute;
  top: 0vw;
  right: 1vw;
  gap: 1vw;
  display: flex;
  flex-direction: row-reverse;
  justify-content: flex-start;
  align-items: center;
}

.connexion-header {
  position: absolute;
  top: 0vw;
  right: 1vw;
}

.top-bloc-header {
  display: flex;
  width: 100%;
  height: auto;
  justify-content: center;
  align-items: center;
  position: relative;
}

.bottom-bloc-header {
  display: flex;
  position: relative;
  justify-content: center;
  align-items: center;
}

.container-header {
  width: 100%;
  height: auto;
  display: flex;
  position: relative;
  gap: 2vw;
  flex-direction: column;
}

.logo-header {
  display: block;
  width: 8%;
  height: auto;
  position: relative;
}

.logo-header img {
  width: 100%;
  height: 5.5vw;
  object-fit: cover;
  object-position: center;
}

nav {
  width: fit-content;
  font-size: 0.8vw;
  height: auto;
  display: block;
  text-align: center;
}

nav a.router-link-exact-active:hover {
  background-color: transparent;
}

nav a {
  display: inline-block;
  padding: 0 1rem;
  color: #403933;
  font-family: nexa-bold;
  border-left: 1px solid var(--color-brown);
}

.bottom-bloc-header .wrapper nav a:first-child {
  border-left: unset;
}

.bottom-bloc-header .wrapper nav a:hover {
  background-color: #D9CCC1;
}

.account a {
  padding: 0 0.5vw;
  border-left: unset;
}

.account a:hover {
  background-color: #D9CCC1;
}

.account span {
  padding: 0 0.5vw;
  font-family: nexa-bold;
  color: var(--color-brown);
  font-style: italic;
  text-transform: uppercase;
}

nav a:first-of-type {
  color: #403933;
}

.nav-auth .connexion-header:hover {
  background-color: #D9CCC1;
}






/* Icône panier */
.cart-icon {
  position: relative;
  cursor: pointer;
  width: 4%;
  display: flex;
  height: auto;
}

.cart-icon img {
  width: 100%;
  height: auto;
  object-fit: cover;
  position: relative;
}

.cart-badge {
  position: absolute;
  top: 0px;
  right: -5px;
  background: red;
  color: white;
  font-family: system-ui;
  font-size: 0.6rem;
  font-weight: bold;
  border-radius: 60%;
  padding: 0px 4px;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
}







/* Panneau panier */
.cart-panel {
  position: fixed;
  top: 0;
  right: -30vw;
  width: 30vw;
  height: 100%;
  background-color: var(--color-brown);
  box-shadow: -2px 0 5px rgba(0, 0, 0, 0.3);
  transition: right 0.3s ease;
  z-index: 1000;
  display: flex;
  flex-direction: column;
}

.cart-panel.open {
  right: 0;
}

.cart-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1rem;
  border-bottom: 1px solid #ccc;
}

.close-cart {
  background: none;
  border: none;
  font-size: 1.2rem;
  cursor: pointer;
}

.cart-items {
  padding: 1rem;
  overflow-y: auto;
}

.cart-item {
  padding: 1.5rem 0;
  display: flex;
  gap: 3vw;
  border-bottom: 1px solid var(--color-beige);
  align-items: center;
  justify-content: flex-start;
}

.cart-close-btn {
  position: relative;
  right: 15px;
  color: var(--color-beige);
  border: none;
  background: transparent;
  font-size: 24px;
  cursor: pointer;
  line-height: 1;
}




/**************** CSS MODIFIER / SUPPRIMER / VIDER LE PANIER ******************/

/* Input quantité */
.cart-item-info input[type="number"] {
  width: 50px;
  display: flex;
  padding: 0px 3px;
  font-family: sans-serif;
  border: 1px solid #ccc;
  border-radius: 4px;
  font-size: 15px;
}

/* Bouton supprimer un produit */
.btn-supprimer {
  display: flex;
  padding: 4px 8px;
  background-color: #ff4d4d;
  color: #fff;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-size: 12px;
  transition: background-color 0.2s ease;
}

.btn-supprimer:hover {
  background-color: #e60000;
}

/* Bouton vider le panier */
.btn-clear-cart {
  display: flex;
  padding: 10px 15px;
  font-family: sans-serif;
  width: fit-content;
  background-color: var(--color-beige);
  color: var(--color-brown);
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-weight: bold;
  transition: background-color 0.2s ease;
}

.btn-clear-cart:hover {
  background-color: #ff4d4d;
  color: white;
}

/* Ajustement de la section info pour que tout soit aligné */
.cart-item-info {
  display: flex;
  flex-direction: column;
  justify-content: space-between;
}












/* @media (max-width: 764px) {
  header {
    display: flex;
    place-items: center;
  }

  header .wrapper {
    display: flex;
    place-items: flex-start;
    flex-direction: column;
    justify-content: center;
    align-items: center;
  }

  nav {
    text-align: center;
    font-size: 1rem;
    padding: 1rem 0;
    margin-top: 1rem;
  }
} */
</style>