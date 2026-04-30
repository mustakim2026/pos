
import 'bootstrap/dist/css/bootstrap.min.css';
import $ from 'jquery';
import { createApp } from 'vue';
import App from './App.vue';
import router from './router';

// 1. Create the application instance
const app = createApp(App);

// 2. Use the router plugin
app.use(router);

// 3. Mount it to the DOM
app.mount('#app');
