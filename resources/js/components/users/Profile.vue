<template>
    <div class="container mt-5">
      <h2 class="mb-4">Profile</h2>
  
      <!-- Error Alert -->
      <div v-if="error" class="alert alert-danger">{{ error }}</div>
  
      <!-- Profile Card -->
      <div v-if="user" class="card shadow-sm">
        <div class="card-body">
          <h4 class="card-title mb-4">{{ user.name }}'s Profile</h4>
          
          <!-- Profile Details Grid -->
          <div class="row">
            <div class="col-md-6">
              <p><strong>Name:</strong> {{ user.name }}</p>
              <p><strong>Email:</strong> {{ user.email }}</p>
              <p><strong>Address:</strong> {{ user.address }}</p>
            </div>
            <div class="col-md-6">
              <p><strong>Gender:</strong> {{ user.gender }}</p>
              <p><strong>Status:</strong> 
                <span class="badge" 
                      :class="user.status === 'active' ? 'bg-success' : 'bg-secondary'">
                  {{ user.status }}
                </span>
              </p>
              <p><strong>Role:</strong> {{ user.role }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  
  <script>
  import axios from 'axios';
  
  export default {
    name: "profile",
    data() {
      return {
        user: null, // Store logged-in user's data
        error: null,
      };
    },
    async mounted() {
      try {
        // Fetch the currently logged-in user's data
        const response = await axios.get('/user/profile');
        this.user = response.data.user;
      } catch (error) {
        this.error = 'Failed to fetch profile details.';
        console.error('Error fetching profile:', error);
      }
    },
  };
  </script>
  