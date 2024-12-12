import '../assets/styles/app.css';

console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');

import { createApp } from 'vue';

import HomeApp from './components/User/HomeApp.vue';
import ProductApp from './components/Admin/ProductApp.vue';
import ValiderPanierApp from './components/User/ValiderPanierApp.vue';
import CommandeApp from './components/Admin/CommandeApp.vue';
import NavbarAdmin from './components/Admin/NavbarAdmin.vue';
import AccueilAdmin from './components/Admin/AccueilAdmin.vue';
import Login from './components/User/Login.vue';


createApp(Login).mount('#Login-app')
createApp(NavbarAdmin).mount('NavbarAdmin-app')
createApp(AccueilAdmin).mount('#Admin-app')
createApp(CommandeApp).mount('#Commandes-app');
createApp(HomeApp).mount('#Home-app');
createApp(ProductApp).mount('#Produits-app');
createApp(ValiderPanierApp).mount('#ValiderPanier-app');
