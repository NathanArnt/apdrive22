<template>
  <div class="panier-Page">
    <NavbarApp />
    <div class="panier-container">
      <!-- Titre du panier et total -->
      <div class="title">
        <h1>Votre panier :</h1>
        <h1>{{ totalPanier }}€</h1>
      </div>

      <!-- Liste des détails des commandes -->
      <div class="details-container">
        <!-- Si le panier contient des éléments -->
        <div v-if="detailscommandes.length">
          <div class="details" v-for="detail in detailscommandes" :key="detail.id">
            <div class="details-row">
              <!-- Image du produit -->
              <div class="img-produit">
                <img :src="'/uploads/images/' + detail.leProduit.image" alt="Produit" />
              </div>
              <!-- Description du produit -->
              <div class="desc-produit">
                <a class="libelle">{{ detail.leProduit.libelle }}</a>
                <a>{{ detail.leProduit.description }}</a>
              </div>
              <!-- Prix et actions sur le produit -->
              <div class="prix-produit">
                <span>{{ (detail.quantite * detail.leProduit.prix).toFixed(2) }} €</span>
                <div class="panier-absolute">
                  <!-- Bouton pour décrémenter -->
                  <button @click="decrementProduit(detail.leProduit.id)">
                    <i class="fa-solid fa-minus"></i>
                  </button>
                  <!-- Quantité -->
                  <a>{{ detail.quantite }}</a>
                  <!-- Bouton pour incrémenter -->
                  <button @click="incrementProduit(detail.leProduit.id)">
                    <i class="fa-solid fa-plus"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- Si le panier est vide -->
        <div v-else>
          <p>Votre panier est vide.</p>
        </div>
      </div>

      <!-- Boutons d'action -->
      <div class="buttonPanier" v-if="detailscommandes.length">
        <button id="suppPanier" @click="clearPanier">Supprimer le panier</button>
        <span></span>
        <button id="validerPanier" @click="updateStatutCommande">Payer le panier</button>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, onMounted } from "vue";
import NavbarApp from "../NavbarApp.vue";

export default {
  name: "Panier",
  components: { NavbarApp },
  setup() {
    const detailscommandes = ref([]); // Contient les produits du panier
    const totalPanier = ref(0); // Total du panier

    // Fonction pour valider la commande
    const updateStatutCommande = async () => {
      try {
        // Récupérer l'ID de la commande depuis le premier produit du panier
        const commandeId = detailscommandes.value[0]?.laCommande?.id;

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

    // Fonction pour vider le panier
    const clearPanier = async () => {
      try {
        const response = await fetch("/api/panier/clear", {
          method: "POST",
        });
        if (response.ok) {
          detailscommandes.value = []; // Vider la liste localement
          totalPanier.value = 0; // Réinitialiser le total
        }
      } catch (error) {
        console.error("Erreur lors de la suppression du panier :", error);
      }
    };

    // Fonction pour calculer le total du panier
    const calculerTotalPanier = () => {
      const total = detailscommandes.value.reduce((total, detail) => {
        return total + detail.quantite * detail.leProduit.prix;
      }, 0);
      totalPanier.value = parseFloat(total.toFixed(2)); // Arrondi à deux décimales
    };

    // Fonction pour récupérer les détails du panier
    const fetchDetailsCommandes = async () => {
      try {
        const response = await fetch("/api/detailscommandes");
        if (!response.ok) throw new Error(`Erreur HTTP : ${response.status}`);
        detailscommandes.value = await response.json(); // Charger les données
        calculerTotalPanier(); // Mettre à jour le total
      } catch (error) {
        console.error("Erreur lors du chargement des détails du panier :", error);
      }
    };

    // Ajouter un produit au panier
    const incrementProduit = async (produitId) => {
      try {
        const response = await fetch("/api/panier/add", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ produit_id: produitId }),
        });
        if (response.ok) await fetchDetailsCommandes(); // Recharger les données après l'ajout
      } catch (error) {
        console.error("Erreur lors de l'ajout du produit :", error);
      }
    };

    // Enlever un produit du panier
    const decrementProduit = async (produitId) => {
      try {
        const response = await fetch("/api/panier/decrement", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ produit_id: produitId }),
        });
        if (response.ok) await fetchDetailsCommandes(); // Recharger les données après la suppression
      } catch (error) {
        console.error("Erreur lors de la décrémentation du produit :", error);
      }
    };

    // Charger les données au montage
    onMounted(() => {
      fetchDetailsCommandes();
    });

    return {
      detailscommandes,
      totalPanier,
      incrementProduit,
      decrementProduit,
      clearPanier,
      updateStatutCommande,
    };
  },
};
</script>

<style scoped>
  .panier-Page {
    max-width: 850px;
    margin: auto;
  }
  .panier-container .buttonPanier {
    display: flex;
    align-items: center;
    justify-content: center;
  }
  .panier-container .buttonPanier button {
    margin: 15px;
    padding: 10px 20px;
    background: none;
    border: 2px solid black;
    border-radius: 5px;
    transition: 0.3s ease-in-out;
  }
  .panier-container .buttonPanier #suppPanier:hover {
    background-color: rgb(224, 16, 16);
  }
  .panier-container .buttonPanier #validerPanier:hover {
    background-color: rgb(17, 223, 17);
  }
  .panier-container .buttonPanier span {
    padding: 0 5px;
  }
  .panier-container .title {
    display: flex;
    align-items: center;
    justify-content: space-between;
  }
  .panier-container .details-container {
    padding: 10px;
    border: 2px #ee0653 solid;
    border-radius: 10px;
  }
  .panier-container .details .details-row {
    height: 90px;
    display: flex;
    align-items: center;
    justify-content: space-between;
  }
  .panier-container .details .details-row .img-produit img{
    width: 80px;
  }
  .panier-container .details .details-row .desc-produit {
    display: flex;
    align-items: center;
    justify-content: center;
  }
  .panier-container .details .details-row .desc-produit a {
    font-size: 1.1rem;
  }
  .panier-container .details .details-row .desc-produit .libelle {
    font-size: 1.5rem;
    font-weight: bold;
    text-decoration: none;
    color: #ee0653;
    padding-right: 15px;
  }
  .panier-container .details .details-row .prix-produit {
    display: flex;
    flex-direction: column;
    align-items: center;
    font-weight: 500;
    font-size: 1.2rem;
  }
  .panier-container .details .details-row .prix-produit span {
    color: #ee0653;
  }
  .panier-container .details .details-row .prix-produit .panier-absolute {
    display: flex;
    justify-content: center;
    align-items: center;
  } 
  .panier-container .details .details-row .prix-produit .panier-absolute a {
    padding: 0 7px 0 7px;
    color: black;
  }
  .panier-container .details .details-row .panier-absolute {
    position: relative;
    background-color: #ee0653;
    width: 100px; 
    height: 40px;
    border-radius: 50px;
  }
  .panier-container .details .details-row .prix-produit button {
    position: relative;
    border-radius: 50%;
    border: none;
    width: 30px;
    height: 30px;
    background-color: white;
  }
  .panier-container .details .details-row .prix-produit i {
    font-size: 1rem;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
  }
</style>