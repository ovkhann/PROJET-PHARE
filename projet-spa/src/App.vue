<script setup lang="ts">
import { RouterLink, RouterView } from 'vue-router'
import { useUserStore } from '@/stores/User';

const User = useUserStore()
</script>

<template>
      <RouterView />
</template>

<style scoped>


</style>
