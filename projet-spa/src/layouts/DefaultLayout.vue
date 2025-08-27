<script setup lang="ts">
import { ref, computed, onMounted, watch } from 'vue';
import { RouterLink, RouterView } from 'vue-router';
import { useUserStore } from '@/stores/User';
import { useCartStore } from '@/stores/cart';

const User = useUserStore();
const cartStore = useCartStore();
const isCartOpen = ref(false);

// Ouvrir/fermer le panier
const toggleCart = () => {
  isCartOpen.value = !isCartOpen.value;
};

// Nombre total d'articles
const totalItems = computed(() =>
  cartStore.items.reduce((acc, i) => acc + i.quantity, 0)
);

// Total du panier
const totalPrice = computed(() =>
  cartStore.items.reduce((acc, i) => acc + (i.product?.price || 0) * i.quantity, 0)
);

// Déconnexion
async function logoutUser() {
  await User.logout();
  await cartStore.clearCart();
}

// Charger le panier si utilisateur connecté
onMounted(async () => {
  if (User.isLogged) {
    await cartStore.fetchCart();
  }
});

// Rafraîchir le panier quand l'utilisateur se connecte/déconnecte
watch(
  () => User.isLogged,
  async (logged) => {
    if (logged) {
      await cartStore.fetchCart();
    } else {
      cartStore.items = [];
    }
  }
);
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
                <span v-if="totalItems > 0" class="cart-badge">{{ totalItems }}</span>
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

    <!-- PANNEAU PANIER -->
    <div class="cart-panel" :class="{ 'open': isCartOpen }">
      <div class="cart-header">
        <h3>Votre panier</h3>
        <button class="close-cart" @click="toggleCart">X</button>
      </div>
      <div class="cart-items">
        <div v-if="cartStore.items.length === 0">Aucun article dans le panier</div>
        <div v-else>
          <div v-for="item in cartStore.items" :key="item.product_id" class="cart-item">
            <div class="cart-item-info">
              <img :src="item.product?.picture ?? '/images/products/fallback.jpg'" alt="Produit" class="cart-item-img"/>
              <span>{{ item.product?.name }}</span>
            </div>
            <div class="cart-item-actions">
              <span>{{ item.quantity }} x {{ item.product?.price.toFixed(2) }}€</span>
              <button @click="cartStore.removeFromCart(item.product_id)">Supprimer</button>
            </div>
          </div>
          <div class="cart-total">
            <strong>Total :</strong> {{ totalPrice.toFixed(2) }}€
          </div>
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
          <span class="text-style">Keep up to date with the latest releases, <br>early access passwords & exclusive deals</span>
          <input type="text" class="input" placeholder="Email" />
          <button type="submit" class="button">SUBSCRIBE</button>
        </div>
      </div>
      <div class="bottom-container-footer">
        <span class="politique">Politique de confidentialité | Mentions légales | Politique de remboursement</span>
        <span class="revolve-realm">REVOLVE REALM™ | 2025</span>
      </div>
    </footer>
  </div>
</template>

<!-- <style scoped>
/* Ajoute ici ton CSS pour header, footer, panier, badges, etc. */
.cart-panel {
  position: fixed;
  right: -400px;
  top: 0;
  width: 400px;
  height: 100%;
  background: white;
  box-shadow: -2px 0 10px rgba(0,0,0,0.3);
  transition: right 0.3s;
  z-index: 1000;
  padding: 1rem;
  overflow-y: auto;
}
.cart-panel.open {
  right: 0;
}
.cart-badge {
  background: red;
  color: white;
  border-radius: 50%;
  padding: 0 6px;
  font-size: 0.8rem;
  position: absolute;
  top: -5px;
  right: -5px;
}
.cart-item {
  display: flex;
  justify-content: space-between;
  margin-bottom: 1rem;
}
.cart-item-info {
  display: flex;
  gap: 0.5rem;
  align-items: center;
}
.cart-item-img {
  width: 50px;
  height: 50px;
  object-fit: cover;
}
.cart-item-actions button {
  background: #e74c3c;
  color: white;
  border: none;
  padding: 2px 6px;
  border-radius: 4px;
  cursor: pointer;
}
.cart-total {
  margin-top: 1rem;
  font-weight: bold;
  text-align: right;
}
</style> -->



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
  font-size: 0.5rem;
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
  right: -400px;
  width: 400px;
  height: 100%;
  background-color: #fff;
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
  flex: 1;
  overflow-y: auto;
}

.cart-item {
  padding: 0.5rem 0;
  border-bottom: 1px solid #eee;
}
</style>