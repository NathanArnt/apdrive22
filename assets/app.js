import './styles/app.css';

console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');

import { createApp } from 'vue';
import HomeApp from './components/HomeApp.vue';
import ProductApp from './components/ProductApp.vue';

createApp(HomeApp).mount('#Home-app');
createApp(ProductApp).mount('#Product-app');
