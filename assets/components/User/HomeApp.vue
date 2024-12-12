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
    const produits = ref([]);
    const detailscommandes = ref([]);
    const totalPanier = ref(0);
    const commandes = ref([]);

    // Récupération des produits depuis l'API
    const fetchProduits = async () => {
      try {
        const response = await fetch("/api/produits");
        produits.value = await response.json();
      } catch (error) {
        console.error("Erreur lors du chargement des produits :", error);
      }
    };

    // Récupération des détails du panier depuis l'API
    const fetchDetailsCommandes = async () => {
      try {
        const response = await fetch("/api/detailscommandes");
        detailscommandes.value = await response.json();
        calculerTotalPanier();
      } catch (error) {
        console.error("Erreur lors du chargement des détails du panier :", error);
      }
    };
    // Récupération des détails du panier depuis l'API
    const fetchCommandes = async () => {
      try {
        const response = await fetch("/api/commandes");
        commandes.value = await response.json();
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
        if (response.ok) {
          await fetchDetailsCommandes();
        }
      } catch (error) {
        console.error("Erreur lors de l'ajout du produit :", error);
      }
    };

    // Décrémenter un produit dans le panier
    const decrementProduit = async (produitId) => {
      try {
        const response = await fetch("/api/panier/decrement", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ produit_id: produitId }),
        });
        if (response.ok) {
          await fetchDetailsCommandes();
        }
      } catch (error) {
        console.error("Erreur lors de la décrémentation du produit :", error);
      }
    };

    // Vider le panier
    const clearPanier = async () => {
      try {
        const response = await fetch("/api/panier/clear", {
          method: "POST",
        });
        if (response.ok) {
          detailscommandes.value = [];
          totalPanier.value = 0;
        }
      } catch (error) {
        console.error("Erreur lors de la suppression du panier :", error);
      }
    };

    // Calculer le total du panier avec un arrondi à deux décimales
    const calculerTotalPanier = () => {
      const total = detailscommandes.value.reduce((total, detail) => {
        return total + detail.quantite * detail.leProduit.prix;
      }, 0);
      totalPanier.value = parseFloat(total.toFixed(2));
    };

    // Charger les données au montage
    onMounted(() => {
      fetchProduits();
      fetchCommandes();
      fetchDetailsCommandes();
    });

    return {
      produits,
      detailscommandes,
      totalPanier,
      commandes,
      incrementProduit,
      decrementProduit,
      clearPanier,
    };
  },
};
</script>
    
<style scoped>
  
  .homePage-container {
    position: relative;
    height: 200vh;
  }
  .homePage-panier {
    position: absolute;
    right: 10px;
    top: 0px;
    z-index: 1;
    box-shadow: 2px 2px 10px 1px #000000;
  }
  .homePage-panier .panier {
    min-height: 300px;
    min-width: 220px;
    padding: 10px 20px;
    background-color: #ee0653;
    color: black;
    font-size: 1.2rem;
    display: flex;
    flex-direction: column;  /* Aligner les éléments verticalement *//* Fixer la hauteur en fonction de la fenêtre moins la hauteur de la barre de navigation */
    overflow-y: auto;
  }
  .homePage-panier .panier .btnPanier {
    margin: 30px 0 0 0;
    width: 100%;
    align-items: center;
    justify-content: center;
    display: flex;
    flex-direction: column
  }
  .homePage-panier .panier .btnPanier button {
    background: white;
    border: 2px solid black;
    width: 150px;
    height: 45px;
    color: #ee0653;
    font-weight: bold;
    text-transform: uppercase;
    border-radius: 8px;
    font-size: 0.9rem;
    letter-spacing: 3px;
    transition: 0.3s ease;
  }
  .homePage-panier .panier .btnPanier button:hover {
    transform: scale(1.1)
  }
  .homePage-panier .panier .btnPanier .validerPanier{
    margin-top: 15px;
  }
  .homePage-panier .panier .btnPanier button a{
    text-decoration: none;
    color: #ee0653;
    text-transform: uppercase;
    font-weight: bold;
    letter-spacing: 3px;
  }
  .homePage-panier .panier .panier-button {
    position: relative;
    background-color: #fff;
    width: 100px; 
    height: 40px;
    border: 1px black;
    border-radius: 50px;
  }
  .homePage-panier .panier .panier-button .panier-absolute {
    display: flex;
    justify-content: center;
    align-items: center;
  }
  .homePage-panier .panier .panier-button {
    position: relative;
  }
  .homePage-panier .panier .panier-button .panier-absolute {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
  }
  .homePage-panier .panier .panier-button .panier-absolute a {
    padding: 0 5px 0 5px;
    color: black;
  }
  .homePage-panier .panier .panier-button i {
    font-size: 1rem;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
  }
  .homePage-panier .panier .panier-button button {
    position: relative;
    border-radius: 50%;
    border: none;
    width: 30px;
    height: 30px;
    background-color: #ee0653;
  }

  /* Panier et détails sous le total */
  .panier .total {
    display: flex;
    justify-content: space-between;
    font-weight: bold;
    color: white;
    margin-bottom: 15px;  /* Espace entre le total et les détails */
  }
  .panier .details {
    font-size: 1.2rem;
    color: white;
    margin-bottom: 10px; /* Un petit espace entre chaque détail */
  }
  .panier .details p {
    margin: 5px 0;  /* Un petit espace entre les détails des produits */
  }
  .products-container {
    display: grid;
    grid-template-columns: repeat(5, 1fr); /* Limite à trois colonnes */
    gap: 10px; /* Espace entre les cartes */
    max-width: 1000px;
    margin: 40px 60px;
  }
  .products-container .card {
    font-size: 70%;
    max-width: 200px;
    max-height: 300px;
  }
  .products-container .card .card-body {
    padding: 0 10px 10px 10px;
  }
  .products-container .card .card-img-top {
    width: 130px;
    margin: auto;
  }
  .products-container .card .card-title {
      font-size: 1.5rem;
  }
  .products-container .card .card-link-prix {
    color: #ee0653;
    cursor: text;
    font-size: 1.5rem;
    font-weight: bold;
    margin: 0;
  }
  .products-container .card .card-body .card-line {
    display: flex;
    align-items: center;
    justify-content: space-between; 
    padding-bottom: 8px;
  }
  .products-container .card .card-body .card-line button {
    position: relative;
    border-radius: 50%;
    border: none;
    width: 30px;
    height: 30px;
    background-color: #ee0653;
  }
  .products-container .card .card-body .card-line button i {
    font-size: 1.1rem;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
  }
</style>