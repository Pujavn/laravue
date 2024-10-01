<template>
  <div class="container mt-5">
    <h2>Register</h2>
    <form @submit.prevent="registerUser">
      <div class="form-group">
        <label for="name">Name</label>
        <input v-model="form.name" type="text" class="form-control" placeholder="Enter your name" required>
      </div>

      <div class="form-group">
        <label for="email">Email</label>
        <input v-model="form.email" type="email" class="form-control" placeholder="Enter your email" required>
      </div>

      <div class="form-group">
        <label for="password">Password</label>
        <input v-model="form.password" type="password" class="form-control" placeholder="Enter your password" required>
      </div>

      <div class="form-group">
        <label for="password_confirmation">Confirm Password</label>
        <input v-model="form.password_confirmation" type="password" class="form-control" placeholder="Confirm your password" required>
      </div>

      <div class="form-group">
        <label for="address">Address</label>
        <input v-model="form.address" type="text" class="form-control" placeholder="Enter your address">
      </div>

      <div class="form-group">
        <label for="gender">Gender</label>
        <select v-model="form.gender" class="form-control">
          <option value="" disabled>Select Gender</option>
          <option value="Male">Male</option>
          <option value="Female">Female</option>
        </select>
      </div>

      <!-- State Dropdown -->
      <div class="form-group">
        <label for="state">Select State</label>
        <select v-model="selectedState" class="form-control" @change="fetchCities">
          <option value="" disabled>Select a state</option>
          <option v-for="state in states" :key="state.id" :value="state.id">{{ state.name }}</option>
        </select>
      </div>

      <!-- City Dropdown -->
      <div class="form-group">
        <label for="city">Select City</label>
        <select v-model="form.city_ids" class="form-control" multiple>
          <option v-for="city in cities" :key="city.id" :value="city.id">{{ city.name }}</option>
        </select>
      </div>

      <button type="submit" class="btn btn-primary mt-3">Register</button>

      <div v-if="error" class="alert alert-danger mt-3">
        {{ error }}
      </div>
      <div v-if="success" class="alert alert-success mt-3">
        {{ success }}
      </div>
    </form>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      form: {
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
        address: '',
        gender: '',
        state_ids: [], // Holds the selected state ID(s) before form submission
        city_ids: []   // Holds selected cities
      },
      selectedState: '',  // Track the selected state
      states: [],         // List of states from the backend
      cities: [],         // List of cities fetched based on the selected state
      error: null,
      success: null
    };
  },
  methods: {
    async fetchStates() {
      try {
        const response = await axios.get('/states');
        this.states = response.data;
      } catch (error) {
        console.error('Failed to fetch states:', error);
      }
    },
    async fetchCities() {
      try {
        // Ensure a state is selected
        if (this.selectedState) {
          // Fetch cities based on the selected state
          const response = await axios.get(`/states/${this.selectedState}/cities`);
          this.cities = response.data;
        } else {
          this.cities = [];  // Reset cities if no state is selected
        }
      } catch (error) {
        console.error('Failed to fetch cities:', error);
      }
    },
    async registerUser() {
      // Assign the selected state to form.state_ids before form submission
      this.form.state_ids = [this.selectedState];  // Even if it's a single state, it should be an array

      try {
        // console.log('Form data before submitting:', this.form);  // Debugging
        const response = await axios.post('/register', this.form);
        // On success, show a success message
        this.success = 'Registration successful! Please check your email to activate your account.';
        this.error = null;
        this.resetForm();

        // Redirect to the home page after 2 seconds
        setTimeout(() => {
          this.$router.push({ name: 'home' });  // 'home' should match the name of your home route
        }, 2000);

      } catch (error) {
        this.error = error.response.data.errors || 'Registration failed';
        this.success = null;
      }
    },
    resetForm() {
      this.form.name = '';
      this.form.email = '';
      this.form.password = '';
      this.form.password_confirmation = '';
      this.form.address = '';
      this.form.gender = '';
      this.form.state_ids = [];
      this.form.city_ids = [];
      this.selectedState = '';
    }
  },
  mounted() {
    this.fetchStates();  // Fetch states when the component is mounted
  }
};
</script>
