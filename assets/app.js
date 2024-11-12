import './styles/app.css'
import { createApp } from "vue";
import Home from "./vue/Home.vue";
import Login from "./vue/Login.vue";

createApp(Home).mount('#app')
createApp(Login).mount('#appLogin')

// const appHome = createApp(Home);

// appHome.mount('#app1')
