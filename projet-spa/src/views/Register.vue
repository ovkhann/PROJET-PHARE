<script setup lang="ts">
import { ref } from 'vue';
import router from '@/router';
import * as AuthService from '@/_services/AuthService';
import { useHead } from '@vueuse/head';

useHead({
  title: 'Register | Revolve Realm',
  meta: [
    { name: 'description', content: "Create your Revolve Realm account to join our streetwear community, save your favorites, and enjoy a personalized shopping experience." },
    { property: 'og:title', content: 'Register | Revolve Realm' }
  ],
  link: [
    { rel: 'canonical', href: 'https://revolverealm.com/register'  }
  ]
})

const form = ref({
    first_name: '',
    last_name: '',
    email: '',
    password: '',
    password_confirmation: ''
});

const errorMsg = ref('');

async function handleRegister() {
    try {
        await AuthService.register(form.value);
        alert('Un mail de vérification vous a été envoyé.');
        router.push('/login');
    } catch (error: any) {
        errorMsg.value = error.message;
    }
}
</script>

<template>
    <div class="formulaire-inscription">
        <div class="top-container">
            <form @submit.prevent="handleRegister">
                <div class="container-bloc-titre">
                    <h2 class="form-title">CREATE AN ACCOUNT</h2>
                    <span class="texte-style">Please fill in the form to register :</span>
                </div>

                <div class="container-champs">
                    <div class="form-error" v-if="errorMsg">{{ errorMsg }}</div>
                    <div class="form-group">
                        <input v-model="form.first_name" type="text" class="input" placeholder="First name" />
                    </div>

                    <div class="form-group">
                        <input v-model="form.last_name" type="text" class="input" placeholder="Last name" />
                    </div>

                    <div class="form-group">
                        <input v-model="form.email" type="email" class="input" placeholder="Email" />
                    </div>

                    <div class="form-group">
                        <input v-model="form.password" type="password" class="input" placeholder="Password" />
                    </div>

                    <div class="form-group">
                        <input v-model="form.password_confirmation" type="password" class="input"
                            placeholder="Confirm password" />
                    </div>

                    <div class="form-group">
                        <button type="submit" class="button">Create Account</button>
                    </div>
                </div>
                <p class="register-link">
                    Have an account ?
                    <router-link to="/login">Login</router-link>
                </p>
            </form>
        </div>
    </div>
</template>

<style scoped>
.formulaire-inscription {
    display: flex;
    flex-direction: column;
    width: 100%;
    padding: 7vw;
    justify-content: center;
    align-items: center;
}

.top-container {
    width: 60%;
    box-shadow: 6px 10px 9px grey;
    background: #403933;
    padding: 2vw;
}

.form-group:nth-child(3) {
    border-radius: 0vw;
    border: unset;
    font-family: nexa-regular;
    margin-top: 0vw;
    width: unset;
}

.container-champs {
    display: flex;
    flex-direction: column;
    gap: 1vw;
    justify-content: center;
    align-items: center;
}

.form-group input {
    width: 19vw;
    padding: 0.5vw;
    font-family: nexa-regular;
}

.form-group:nth-child(6) {
    margin-top: 1.5vw;
}

.button {
    background-color: #f2eadf;
    color: #403933;
    padding: 0.5vw 1vw;
    border: none;
    font-family: 'nexa-regular';
    font-style: italic;
    cursor: pointer;
}

.form-error {
    color: red;
    font-size: 0.8rem;
}
</style>