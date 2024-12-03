<template>
  <div class="produits-app">
    <h1>Liste des produits</h1>
    <div class="table-container">
      <div class="table-header">
        <div>ID</div>
        <div>Produits</div>
        <div>Prix</div>
        <div>Quantité</div>
      </div>
      <div class="table-row" v-for="produit in produits" :key="produit.id">
        <div>{{ produit.id }}</div>
        <div>{{ produit.libelle }}</div>
        <div>{{ produit.prix }}</div>
        <div>{{ produit.quantite }}</div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue';

export default {
  name: 'ProductsApp',
  setup() {
    const produits = ref([]);

    // Fonction pour récupérer les données des produits
    const fetchProduits = async () => {
      try {
        const response = await fetch('/api/produits');
        if (!response.ok) {
          throw new Error('Erreur lors du chargement des villes');
        }
        produits.value = await response.json();
      } catch (error) {
        console.error(error);
      }
    };

   

    // Charger les villes au montage du composant
    onMounted(() => {
      fetchProduits();
    });

    return {
      produits,
    };
  },
};
</script>

<style scoped>
.produits-app {
  max-width: 800px;
  margin: 0 auto;
  padding: 20px;
}

h1 {
  text-align: center;
  margin-bottom: 20px;
}

.table-container {
  display: flex;
  flex-direction: column;
}

.table-header,
.table-row {
  display: flex;
  justify-content: space-between;
  padding: 10px;
  border: 1px solid #ccc;
  background-color: #f9f9f9;
}

.table-header {
  font-weight: bold;
  background-color: #e9e9e9;
}

.table-row:nth-child(even) {
  background-color: #f4f4f4;
}

</style>
