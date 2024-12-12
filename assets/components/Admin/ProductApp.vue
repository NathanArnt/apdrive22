<template>
  <div>
    <NavbarAdmin/>
    <div class="produits-app">
      <h1>Gestion des Produits</h1>
      <form @submit.prevent="saveProduct">
        <input v-model="currentProduct.libelle" placeholder="Nom du produit" required>
        <input v-model.number="currentProduct.prix" placeholder="Prix" required>
        <input v-model="currentProduct.description" placeholder="Description" required>
        <input v-model.number="currentProduct.stock" placeholder="Stock" required>
        <div class="button-group">
          <button type="submit" class="btn btn-success">{{ isEditing ? 'Mettre à jour' : 'Ajouter' }}</button>
          <button type="button" class="btn btn-secondary" @click="cancelEdit" v-if="isEditing">Annuler</button>
        </div>
      </form>

      <div class="table-container">
        <div class="table-header">
          <div>Libelle</div>
          <div>Prix</div>
          <div>Description</div>
          <div>Stock</div>
          <div>Image</div>
          <div>Actions</div>
        </div>
        <div class="table-row" v-for="produit in produits" :key="produit.id">
          <div>{{ produit.libelle }}</div>
          <div>{{ produit.prix }}</div>
          <div>{{ produit.description }}</div>
          <div>{{ produit.stock }}</div>
          <div class="img-produit">
            <img :src="'/uploads/images/' + produit.image" alt="Produit" />
          </div>
          <div>
            <button @click="editProduct(produit)" class="btn btn-primary">Modifier</button>
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
    const produits = ref([]);
    const currentProduct = ref({
      libelle: '',
      prix: 0,
      description: '',
      stock: 0,
      image: '',
    });

    const isEditing = ref(false);

    const fetchProduits = async () => {
      try {
        const response = await fetch('/api/produits');
        if (!response.ok) {
          throw new Error('Erreur lors du chargement des produits');
        }
        produits.value = await response.json();
      } catch (error) {
        console.error(error);
      }
    };

    const saveProduct = async () => {
      try {
        let response;
        if (currentProduct.value.id) {
          response = await fetch(`/api/produits/update/${currentProduct.value.id}`, {
            method: 'PUT',
            headers: {
              'Content-Type': 'application/json',
            },
            body: JSON.stringify(currentProduct.value),
          });

          if (!response.ok) {
            throw new Error('Erreur lors de la mise à jour du produit');
          }
        } else {
          response = await fetch('/api/produits/add', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
            },
            body: JSON.stringify(currentProduct.value),
          });

          if (!response.ok) {
            throw new Error('Erreur lors de l\'ajout du produit');
          }
        }

        await fetchProduits();
        resetForm();
      } catch (error) {
        console.error(error);
      }
    };

    const deleteProduct = async (id) => {
      try {
        const response = await fetch(`/api/produits/delete/${id}`, {
          method: 'DELETE',
        });

        if (!response.ok) {
          throw new Error('Erreur lors de la suppression du produit');
        }

        await fetchProduits();
      } catch (error) {
        console.error(error);
      }
    };

    const editProduct = (produit) => {
      currentProduct.value = { ...produit };
      isEditing.value = true;
    };

    const resetForm = () => {
      currentProduct.value = {
        libelle: '',
        prix: 0,
        description: '',
        stock: 0,
      };
      isEditing.value = false;
    };

    const cancelEdit = () => {
      resetForm();
    };

    onMounted(() => {
      fetchProduits();
    });

    return {
      produits,
      currentProduct,
      isEditing,
      saveProduct,
      deleteProduct,
      editProduct,
      cancelEdit,
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
