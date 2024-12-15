<template>
  <div class="homePage-container">
    <NavbarApp />
    <div class="homePage-panier">
      <div class="commande">
        <button>Commander</button>
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