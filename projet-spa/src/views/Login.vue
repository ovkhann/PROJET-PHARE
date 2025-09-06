<script setup lang="ts">
import { ref } from 'vue';
import router from '@/router';
import * as AuthService from '@/_services/AuthService';
import { useHead } from '@vueuse/head';

useHead({
  title: 'Login | Revolve Realm',
  meta: [
    { name: 'description', content: "Log in to your Revolve Realm account to access your orders, manage your profile, and enjoy a personalized streetwear shopping experience." },
    { property: 'og:title', content: 'Login | Revolve Realm' }
  ],
  link: [
    { rel: 'canonical', href: 'https://revolverealm.com/login'  }
  ]
})

const auth = ref({
  email: '',
  password: ''
});

const errorMsg = ref('');

async function login() {
  try {
    await AuthService.login(auth.value);
    router.push('/');
  } catch (error: any) {
    errorMsg.value = error.message;
  }
}
</script>

<template>
  <div class="formulaire-connexion login-page">
    <div class="top-container">
      <form @submit.prevent="login">
        <div class="container-bloc-titre-connexion">
          <h2 class="form-title">LOGIN</h2>
          <span class="texte-style">Please enter your e-mail and password :</span>
        </div>
        <div class="container-champs">
          <div v-if="errorMsg">{{ errorMsg }}</div>
          <div class="form-group">
            <input type="text" class="input" v-model="auth.email" placeholder="Email" />
            <!-- <FormError :errors="errors.email" /> -->
          </div>
          <div class="form-group">
            <input type="password" class="input" v-model="auth.password" placeholder="Password" />
            <!-- <FormError :errors="errors.password" /> -->
          </div>
          <div class="form-group">
            <button type="submit" class="button">Connexion</button>
          </div>
        </div>
        <p class="register-link">
          Don’t have an account ?
          <router-link to="/register">Create one</router-link>
        </p>
      </form>
    </div>
  </div>
</template>


<style>
form {
  text-align: center;
  display: flex;
  gap: 2vw;
  flex-direction: column;
}

.form-group input {
  border-radius: 0vw;
  border: unset;
  padding: 0.5vw;
  font-family: nexa-regular;
  width: 19vw;
}

.button {
  background-color: #f2eadf;
  color: #403933;
  padding: 0.5vw 1vw;
  border: none;
  cursor: pointer;
}

.register-link {
  font-size: 1vw;
  color: #F2EADF;
  text-align: center;
}

.register-link a {
  color: #F2EADF;
  text-decoration: underline;
  font-weight: bold;
}

.register-link a:hover {
  color: #D9CCC1;
}

.form-group:nth-child(3) {
  border-radius: 0vw;
  border: unset;
  font-family: nexa-regular;
  width: 13vw;
  margin-top: 1.5vw;
}

.login-page {
  flex: 1;
  display: flex;
  flex-direction: column;
  justify-content: center;
  min-height: 100%;
}

.container-champs {
  display: flex;
  flex-direction: column;
  position: relative;
  gap: 0.8vw;
  justify-content: center;
  align-items: center;
}

h2 {
  color: #F2EADF;
  margin-bottom: 0vw;
}

.formulaire-connexion {
  display: flex;
  flex-direction: column;
  width: 100%;
  height: auto;
  flex: 1;
  padding: 7vw;
  position: relative;
  justify-content: center;
  align-items: center;
}

.top-container {
  width: 60%;
  box-shadow: 6px 10px 9px grey;
  background: #403933;
  padding: 2vw;
}
</style>