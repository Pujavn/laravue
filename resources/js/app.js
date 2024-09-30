// require('./bootstrap');
// import { createApp } from 'vue';
// import HelloVue from './components/HelloVue.vue';

// createApp({
//     components: {
//         HelloVue,
//     }
// }).mount('#app');
// import { createApp } from 'vue';
// import App from './components/App.vue'; // Main App component
// import axios from 'axios'; // Axios for HTTP requests
// import VueAxios from 'vue-axios'; // VueAxios for integrating axios with Vue
// import { createRouter, createWebHistory } from 'vue-router'; // Vue Router for navigation
// import { routes } from './routes'; // Your defined routes

// // Create a router instance
// const router = createRouter({
//     history: createWebHistory(), // Use Web History mode
//     routes, // Your defined routes
// });

// // Set base URL for Axios
// axios.defaults.baseURL = 'http://localhost:8000/api';

// // Add token to Axios headers if available
// const token = localStorage.getItem('token');
// if (token) {
//   axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
// }

// // Create a Vue application
// const app = createApp(App);

// // Use Axios and VueAxios globally
// app.use(VueAxios, axios);

// // Use the router
// app.use(router);

// // Mount the Vue app to the DOM
// app.mount('#app');


import { createApp } from 'vue';
import App from './components/App.vue'; // Main App component
import axios from 'axios'; // Axios for HTTP requests
import VueAxios from 'vue-axios'; // VueAxios for integrating axios with Vue
import router from './routes'; // Import the router instance

// Set base URL for Axios
axios.defaults.baseURL = 'http://localhost:8000/api';

// Add token to Axios headers if available
const token = localStorage.getItem('token');
if (token) {
  axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
}

// Create a Vue application
const app = createApp(App);

// Use Axios globally in the Vue app
app.use(VueAxios, axios);

// Use the router instance
app.use(router);

// Mount the Vue app to the DOM
app.mount('#app');

