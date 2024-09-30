
<template>
    <main>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container-fluid">
                <router-link to="/" class="navbar-brand" href="#">Laravel Vue Crud App</router-link>
                <div class="collapse navbar-collapse">
                    <div class="navbar-nav">
                        <router-link exact-active-class="active" to="/" class="nav-item nav-link">Home</router-link>
                        <router-link v-if="isAuthenticated" exact-active-class="active" to="/category" class="nav-item nav-link">Category</router-link>
                        <!-- Dynamically show login or logout based on authentication status -->
                        <router-link v-if="!isAuthenticated" exact-active-class="active" to="/login" class="nav-item nav-link">Login</router-link>
                        <a v-else @click="logout" class="nav-item nav-link" href="#">Logout</a>
                    </div>
                </div>
            </div>
        </nav>
        <div class="container mt-5">
            <router-view></router-view>
        </div>
    </main>
</template>
 
<script>
import { computed } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios'; // Import Axios directly

export default {
  setup() {
    const router = useRouter(); // Use Vue Router hook for navigation

    // Computed property to check if the user is authenticated based on the token
    const isAuthenticated = computed(() => !!localStorage.getItem('token'));

    const logout = async () => {
      try {
        // Call the logout API directly with Axios
        await axios.post('/logout');
        
        // Remove the token from localStorage
        localStorage.removeItem('token');
        delete axios.defaults.headers.common['Authorization'];
        
        // Redirect to the login page using the router
        router.push('/login');
      } catch (error) {
        console.error('Logout failed:', error);
      }
    };

    return {
      isAuthenticated,
      logout
    };
  }
};
</script>
