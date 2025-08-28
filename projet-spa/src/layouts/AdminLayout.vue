<script setup lang="ts">
import { ref, computed } from 'vue'

// Onglet actif
const currentTab = ref('products')

// Définir les composants pour chaque onglet
import ProductsAdmin from '@/views/admin/ProductsAdmin.vue'
import CategoriesAdmin from '@/views/admin/CategoriesAdmin.vue'
import OptionsAdmin from '@/views/admin/OptionsAdmin.vue'

const currentComponent = computed(() => {
  switch (currentTab.value) {
    case 'products': return ProductsAdmin
    case 'categories': return CategoriesAdmin
    case 'options': return OptionsAdmin
    default: return ProductsAdmin
  }
})
</script>


<template>
  <div class="admin-wrapper">
    <!-- SIDEBAR -->
    <aside class="admin-sidebar">
      <div class="sidebar-header">
        <h2>Admin</h2>
      </div>
      <nav class="sidebar-nav">
        <ul>
          <li :class="{ active: currentTab === 'products' }" @click="currentTab = 'products'">Produits</li>
          <li :class="{ active: currentTab === 'categories' }" @click="currentTab = 'categories'">Catégories</li>
          <li :class="{ active: currentTab === 'options' }" @click="currentTab = 'options'">Options</li>
        </ul>
      </nav>
    </aside>

    <!-- CONTENU PRINCIPAL -->
    <main class="admin-content">
      <component :is="currentComponent" />
    </main>
  </div>
</template>



<style scoped>
.admin-wrapper {
  display: flex;
  min-height: 100vh;
  min-width: 100%;
}

/* Sidebar */
.admin-sidebar {
  width: 20vw;
  background: #222;
  color: #fff;
  padding: 20px;
}

.sidebar-header h2 {
  margin: 0 0 20px 0;
}

.sidebar-nav ul {
  list-style: none;
  padding: 0;
}

.sidebar-nav li {
  padding: 10px;
  cursor: pointer;
  border-radius: 4px;
}

.sidebar-nav li.active,
.sidebar-nav li:hover {
  background: #444;
}

/* Contenu principal */
.admin-content {
  flex: 1;
  padding: 20px;
  background: var(--color-creme);
  overflow-y: auto;
}
</style>
