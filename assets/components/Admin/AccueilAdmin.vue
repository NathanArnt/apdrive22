<template>
  <div class="admin-app">
    <NavbarAdmin />
    <div class="dashboard">
      <h1>Bienvenue</h1>
      <p class="welcome-text">
        Gérez votre plateforme facilement. Utilisez le menu ci-dessus pour naviguer
        entre les sections.
      </p>
    </div>
  </div>
</template>

<script>
import NavbarAdmin from './NavbarAdmin.vue';

export default {
  name: "AdminHome",
  components: {
    NavbarAdmin,
  },
};
</script>

<style scoped>
.admin-app {
  font-family: 'Arial', sans-serif;
  color: #2c3e50;
  background: linear-gradient(135deg, #e0eafc, #cfdef3);
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  align-items: center;
  padding: 1rem;
}

.dashboard {
  max-width: 800px; /* Ajustement de la largeur */
  width: 100%;
  padding: 2rem;
  text-align: center;
  background: white;
  border-radius: 10px;
  box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
  margin-top: 2rem;
  transition: transform 0.3s ease-in-out;
}

.dashboard:hover {
  transform: translateY(-5px);
}

.dashboard h1 {
  font-size: 2.5rem;
  margin-bottom: 1rem;
  color: #34495e;
}

.welcome-text {
  font-size: 1.2rem;
  margin-bottom: 2rem;
  color: #7f8c8d;
}
</style>