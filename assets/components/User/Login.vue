<template>
  <div class="login-form">
    <!-- Affichage conditionnel du formulaire -->
    <form v-if="!isLoggedIn" @submit.prevent="login">
      <h2>Connexion</h2>
      <div>
        <label for="email">Email :</label>
        <input v-model="email" type="email" id="email" placeholder="Entrez votre email" required />
      </div>
      <div>
        <label for="password">Mot de passe :</label>
        <input v-model="password" type="password" id="password" placeholder="Entrez votre mot de passe" required />
      </div>
      <button type="submit">Se connecter</button>
    </form>

    <!-- Message de bienvenue -->
    <div v-else>
      <h2>Bienvenue sur notre site d'enchères !</h2>
    </div>

    <!-- Message d'erreur ou succès -->
    <p v-if="message" :class="{ success: isSuccess, error: !isSuccess }">{{ message }}</p>
  </div>
</template>

<script>
import { ref, onMounted } from "vue";

export default {
  setup() {
    const email = ref("");
    const password = ref("");
    const message = ref(null);
    const isSuccess = ref(false);
    const isLoggedIn = ref(false);

    // Méthode pour la connexion
    const login = async () => {
  try {
    const response = await fetch("/api/login", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        email: email.value,
        password: password.value,
      }),
    });

    const data = await response.json();
    if (response.ok && data.success) {
      isLoggedIn.value = true; // Change correctement l'état
      message.value = "Connexion réussie !";
      isSuccess.value = true;
    } else {
      throw new Error(data.message || "Échec de la connexion");
    }
  } catch (error) {
    message.value = error.message || "Erreur lors de la connexion";
    isSuccess.value = false;
  }
};

    // Méthode pour vérifier l'authentification
    const checkAuth = async () => {
      try {
        const response = await fetch("/api/check-auth", {
          method: "GET",
          headers: {
            "Content-Type": "application/json",
          },
        });

        const data = await response.json();
        if (response.ok) {
          isLoggedIn.value = data.isAuthenticated;
        } else {
          isLoggedIn.value = false; // Non authentifié
        }
      } catch (error) {
        isLoggedIn.value = false; // En cas d'erreur
      }
    };

    // Vérification au montage
    onMounted(() => {
      checkAuth();
    });

    return {
      email,
      password,
      message,
      isSuccess,
      isLoggedIn,
      login,
      checkAuth,
    };
  },
};
</script>


<style>
.login-form {
  max-width: 300px;
  margin: auto;
  padding: 20px;
  border: 1px solid #ddd;
  border-radius: 4px;
  background: #f9f9f9;
}

.login-form h2 {
  text-align: center;
  margin-bottom: 20px;
}

.login-form div {
  margin-bottom: 15px;
}

.login-form label {
  display: block;
  margin-bottom: 5px;
  font-weight: bold;
}

.login-form input {
  width: 100%;
  padding: 8px;
  box-sizing: border-box;
}

.login-form button {
  width: 100%;
  padding: 10px;
  background-color: #4CAF50;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.login-form button:hover {
  background-color: #45a049;
}

p.success {
  color: green;
  text-align: center;
  margin-top: 10px;
}

p.error {
  color: red;
  text-align: center;
  margin-top: 10px;
}
</style>