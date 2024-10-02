<template>
    <main>
      <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
          <router-link to="/" class="navbar-brand">User Management App</router-link>
          <div class="collapse navbar-collapse">
            <div class="navbar-nav">
              <!-- Common link for all users -->
              <router-link exact-active-class="active" to="/" class="nav-item nav-link">Home</router-link>
  
              <!-- Show admin links if the user role is admin -->
              <router-link
                v-if="role === 'admin'"
                exact-active-class="active"
                to="/users"
                class="nav-item nav-link"
              >Manage Users</router-link>
  
              <!-- Show regular user links if the user role is user -->
              <router-link
                v-if="role === 'user'"
                exact-active-class="active"
                to="/profile"
                class="nav-item nav-link"
              >Profile</router-link>
  
              <!-- Dynamically show login or logout based on authentication status -->
              <router-link
                v-if="!isAuthenticated"
                exact-active-class="active"
                to="/register"
                class="nav-item nav-link"
              >Register User</router-link>
  
              <router-link
                v-if="!isAuthenticated"
                exact-active-class="active"
                to="/login"
                class="nav-item nav-link"
              >Login</router-link>
  
              <a v-if="isAuthenticated" @click="logout" class="nav-item nav-link" href="#">Logout</a>
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
  import { ref } from 'vue'; // Import ref for reactivity
  import { useRouter } from 'vue-router';
  import axios from 'axios';
  
  export default {
    setup() {
      const router = useRouter(); // Use Vue Router hook for navigation
  
      // Reactive reference to track the authentication status and role
      const isAuthenticated = ref(!!localStorage.getItem('token'));
      const role = ref(localStorage.getItem('role')); // Get role from localStorage
  
      // Logout function
      const logout = async () => {
        try {
          // Call the logout API directly with Axios (Ensure the endpoint is correct)
          await axios.post('/logout');
  
          // Remove the token and role from localStorage
          localStorage.removeItem('token');
          localStorage.removeItem('role');
          delete axios.defaults.headers.common['Authorization'];
  
          // Set isAuthenticated to false and clear the role
          isAuthenticated.value = false;
          role.value = null;
  
          // Redirect to the login page using the router
          router.push('/login');
        } catch (error) {
          console.error('Logout failed:', error);
        }
      };
  
      // Return reactive values to the template
      return {
        isAuthenticated,
        role,
        logout,
      };
    },
  };
  </script>
  