<template>
    <div class="login-form">
      <h2>Login</h2>
      <form @submit.prevent="login">
        <div class="form-group">
          <label for="email">Email</label>
          <input v-model="email" type="email" id="email" class="form-control" required />
        </div>
        <div class="form-group">
          <label for="password">Password</label>
          <input v-model="password" type="password" id="password" class="form-control" required />
        </div>
        <button type="submit" class="btn btn-primary">Login</button>
      </form>
      <div v-if="errorMessage" class="alert alert-danger mt-3">
        {{ errorMessage }}
      </div>
    </div>
  </template>
  
  <script>
  import { ref } from 'vue';
  import { useRouter } from 'vue-router';
  import axios from 'axios';
  
  export default {
    setup() {
      const email = ref('');
      const password = ref('');
      const errorMessage = ref('');
      const router = useRouter();
  
      const login = async () => {
        try {
          // Make a POST request to the login API
          const response = await axios.post('/login', {
            email: email.value,
            password: password.value,
          });

          // Check if the login was successful and if the user is active
          if (response.status === 200 && response.data.token) {
            // Store the token in localStorage
            localStorage.setItem('token', response.data.token);
            localStorage.setItem('role', response.data.role);  // Store user role
            axios.defaults.headers.common['Authorization'] = `Bearer ${response.data.token}`;

            // Redirect to the home or dashboard page after successful login
            router.push('/');
          }
        } catch (error) {
          // Handle specific error responses from the server
          if (error.response && error.response.status === 403) {
            // If the user's account is not activated, show an appropriate message
            errorMessage.value = 'Please activate your account. Check your email for the activation link.';
          } else if (error.response && error.response.status === 401) {
            // Invalid login credentials
            errorMessage.value = 'Invalid login credentials. Please try again.';
          } else {
            // General error message
            errorMessage.value = 'An error occurred. Please try again later.';
          }
        }
      };
  
      return { email, password, errorMessage, login };
    },
  };
</script>

  <style scoped>
  .login-form {
    max-width: 400px;
    margin: 0 auto;
  }
  </style>
  