<template>
  <div class="homePage-container">
    <NavbarApp />
    <div class="homePage-panier">
      <div class="panier">
        <div class="total">
          <span><i class="fa-solid fa-cart-shopping"></i> Panier : </span>
          <a>{{ totalPanier }}€</a>
        </div>
        <div class="details" v-for="detail in detailscommandes" :key="detail.id">
          <div class="details-row">
            <a>
              {{ detail.leProduit.libelle }} - 
              <span>{{ (detail.quantite * detail.leProduit.prix).toFixed(2) }}€</span>
            </a>
            <div class="panier-button">
              <div class="panier-absolute">
                <button @click="decrementProduit(detail.leProduit.id)">
                  <i class="fa-solid fa-minus"></i>
                </button>
                <a>{{ detail.quantite }}</a>
                <button @click="incrementProduit(detail.leProduit.id)">
                  <i class="fa-solid fa-plus"></i>
                </button>
              </div>
            </div>
          </div>
        </div>
        <div class="btnPanier">
          <button class="suppPanier" v-if="detailscommandes.length" @click="clearPanier">
            Vider
          </button>
          <button class="validerPanier" v-if="detailscommandes.length">
            <a href="/client/panier">Commander</a>
          </button>
          <button class="validerPanier" v-if="detailscommandes.length">
            <a href="/api/commandes/parcours/{id}">Chemin</a>
          </button>
        </div>
      </div>
    </div>
    <div class="products">
      <div class="products-container">
        <div class="card" v-for="produit in produits" :key="produit.id" style="width: 18rem;">
          <img class="card-img-top" :src="'/uploads/images/' + produit.image" alt="Image du produit" />
          <div class="card-body">
            <h5 class="card-title">{{ produit.libelle }}</h5>
            <div class="card-line">
              <p class="card-link-prix">{{ produit.prix }} €</p>
              <button id="addBtn" @click="incrementProduit(produit.id)">
                <i class="fa-solid fa-plus"></i>
              </button>
            </div>
            <p class="card-text">{{ produit.description }}</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, onMounted } from "vue";
import NavbarApp from '../NavbarApp.vue'; // Assurez-vous que le chemin est correct

export default {
  name: "HomeApp",
  components: { NavbarApp },
  setup() {
    const commandeEnCours = ref(null);
    // Fonction pour valider la commande
    const updateStatutCommande = async () => {
      try {
        const response = await fetch(`/api/commandes/update/${commandeId}`, {
          method: "PUT",
          headers: { "Content-Type": "application/json" },
        });

        if (!response.ok) {
          const errorData = await response.json();
          alert(`Erreur API : ${errorData.error || "Erreur inconnue"}`);
          return;
        }

        alert("Commande validée avec succès !");
        detailscommandes.value = []; // Vider le panier localement
        totalPanier.value = 0; // Réinitialiser le total
      } catch (error) {
        console.error("Erreur lors de la validation :", error);
        alert("Une erreur s'est produite lors de la validation de la commande.");
      }
    };

    return {
      
    };
  },
};
</script>
    
<style scoped>
  
  .homePage-container {
    position: relative;
    height: 200vh;
  }
  .commande button{
    border: 2px solid #ee0653;
    padding: 15px 20px;
    background: none;
    border-radius: 9px;
    transition: 0.2s ease-in-out;
  }
  .commande button:hover {
    background: #ee0653;
    color: white;
  }
</style>