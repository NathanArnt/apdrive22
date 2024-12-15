<template>
  <div>
    <NavbarAdmin/>
    <div class="commandes-app">
      <h1>Gestion des commandes</h1>

      <div class="table-container">
        <div class="table-header">
          <div>Id</div>
          <div>Id User</div>
          <div>Statut</div>
          <div>Details commandes</div>
          <div>Actions</div>
        </div>
        <div class="table-row" v-for="commande in commandes" :key="commande.id">
          <div>{{ commande.id }}</div>
          <div>{{ commande.leUser.email }}</div>
          <div>{{ commande.statut }}</div>
          <div>{{ commande.detailsCommandes }}</div>
          <div>
            <button @click="deleteProduct(produit.id)" class="btn btn-danger">Supprimer</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import NavbarAdmin from './NavbarAdmin.vue';

export default {
  name: 'ProduitsApp',
  components: {
    NavbarAdmin,
  },
  setup() {
    const commandes = ref([]);

    const isEditing = ref(false);

    const fetchCommandes = async () => {
      try {
        const response = await fetch('/api/commandes');
        if (!response.ok) {
          throw new Error('Erreur lors du chargement des produits');
        }
        cmmandes.value = await response.json();
      } catch (error) {
        console.error(error);
      }
    };

    const deleteCommande = async (id) => {
      try {
        const response = await fetch(`/api/commandes/delete/${id}`, {
          method: 'DELETE',
        });

        if (!response.ok) {
          throw new Error('Erreur lors de la suppression du produit');
        }

        await fetchCommandes();
      } catch (error) {
        console.error(error);
      }
    };

    onMounted(() => {
      fetchCommandes();
    });

    return {
      commandes,
      isEditing,
      deleteCommande,
    };
  },
};
</script>

<style scoped>
.produits-app {
  max-width: 1000px;
  margin: 2rem auto;
  padding: 1rem;
  background: #fff;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  border-radius: 10px;
}
.img-produit img {
  width: 70%;
}
.produits-app h1 {
  text-align: center;
  margin-bottom: 1.5rem;
  color: #34495e;
}

form {
  display: flex;
  flex-direction: column;
  margin-bottom: 20px;
}

input {
  margin-bottom: 10px;
  padding: 8px;
  border: 1px solid #ddd;
  border-radius: 5px;
}

.button-group {
  display: flex;
  gap: 10px;
}

button {
  padding: 10px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-weight: bold;
}

.btn-primary {
  background-color: #007bff;
  color: white;
}

.btn-primary:hover {
  background-color: #0056b3;
}

.btn-success {
  background-color: #28a745;
  color: white;
}

.btn-success:hover {
  background-color: #218838;
}

.btn-secondary {
  background-color: #6c757d;
  color: white;
}

.btn-secondary:hover {
  background-color: #5a6268;
}

.btn-danger {
  background-color: #dc3545;
  color: white;
}

.btn-danger:hover {
  background-color: #c82333;
}

.table-container {
  
  display: grid;
  grid-template-columns: repeat(6, 1fr);
  gap: 10px;
}

.table-header,
.table-row {
  background-color: #fff;
  border-radius: 5px;
  display: contents;
  font-weight: bold;
  text-align: center;
}

.table-header {
  background-color: #bdc3c7;
  font-weight: bold;
}

.table-row div {
  padding: 0.75rem;
  transition: background-color 0.3s ease;
}

.table-row:nth-child(even) div {
  background-color: #fff;
}

.table-row div:hover {
  background-color: #dfe6e9;
}

.table-row div {
  border-right: 1px solid #ddd;
}

.table-row div:last-child {
  border-right: none;
}
</style>
