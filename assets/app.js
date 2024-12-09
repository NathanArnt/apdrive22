import './styles/app.css'

console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');

import { createApp } from 'vue';
import HomeApp from './components/User/HomeApp.vue';
import ProductApp from './components/ProductApp.vue';
import ValiderPanierApp from './components/User/ValiderPanierApp.vue';

createApp(HomeApp).mount('#Home-app');
createApp(ProductApp).mount('#Product-app');
createApp(ValiderPanierApp).mount('#ValiderPanier-app');
