<template>
  <div class="container mt-5">
    <h2>Register</h2>
    <form @submit.prevent="registerUser">
      <!-- Name Field -->
      <div class="form-group">
        <label for="name">Name</label>
        <input v-model="form.name" type="text" class="form-control" placeholder="Enter your name" required>
      </div>

      <!-- Email Field -->
      <div class="form-group">
        <label for="email">Email</label>
        <input v-model="form.email" type="email" class="form-control" placeholder="Enter your email" required>
      </div>

      <!-- Password Field -->
      <div class="form-group">
        <label for="password">Password</label>
        <input v-model="form.password" type="password" class="form-control" placeholder="Enter your password" required>
      </div>

      <!-- Password Confirmation Field -->
      <div class="form-group">
        <label for="password_confirmation">Confirm Password</label>
        <input v-model="form.password_confirmation" type="password" class="form-control" placeholder="Confirm your password" required>
      </div>

      <!-- Address Field -->
      <div class="form-group">
        <label for="address">Address</label>
        <input v-model="form.address" type="text" class="form-control" placeholder="Enter your address">
      </div>

      <!-- Gender Field -->
      <div class="form-group">
        <label for="gender">Gender</label>
        <select v-model="form.gender" class="form-control">
          <option value="" disabled>Select Gender</option>
          <option value="Male">Male</option>
          <option value="Female">Female</option>
        </select>
      </div>

      <!-- Permanent State Dropdown -->
      <div class="form-group">
        <label for="permanent_state">Select Permanent State</label>
        <select v-model="form.permanent_state_id" class="form-control" @change="fetchCities('permanent')">
          <option value="" disabled>Select a permanent state</option>
          <option v-for="state in states" :key="state.id" :value="state.id">{{ state.name }}</option>
        </select>
      </div>

      <!-- Permanent City Dropdown -->
      <div class="form-group">
        <label for="permanent_city">Select Permanent City</label>
        <select v-model="form.permanent_city_ids" class="form-control" multiple>
          <option v-for="city in permanentCities" :key="city.id" :value="city.id">{{ city.name }}</option>
        </select>
      </div>

      <!-- Temporary State Dropdown -->
      <div class="form-group">
        <label for="temporary_state">Select Temporary State</label>
        <select v-model="form.temporary_state_id" class="form-control" @change="fetchCities('temporary')">
          <option value="" disabled>Select a temporary state</option>
          <option v-for="state in states" :key="state.id" :value="state.id">{{ state.name }}</option>
        </select>
      </div>

      <!-- Temporary City Dropdown -->
      <div class="form-group">
        <label for="temporary_city">Select Temporary City</label>
        <select v-model="form.temporary_city_ids" class="form-control" multiple>
          <option v-for="city in temporaryCities" :key="city.id" :value="city.id">{{ city.name }}</option>
        </select>
      </div>

      <button type="submit" class="btn btn-primary mt-3">Register</button>

      <div v-if="error" class="alert alert-danger mt-3">{{ error }}</div>
      <div v-if="success" class="alert alert-success mt-3">{{ success }}</div>
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
        permanent_state_id: null,
        permanent_city_ids: [],
        temporary_state_id: null,
        temporary_city_ids: [],
      },
      states: [], // All available states
      permanentCities: [], // Permanent cities based on selected permanent state
      temporaryCities: [], // Temporary cities based on selected temporary state
      error: null,
      success: null,
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
    async fetchCities(type) {
      try {
        let stateId = type === 'permanent' ? this.form.permanent_state_id : this.form.temporary_state_id;
        if (stateId) {
          const response = await axios.get(`/states/${stateId}/cities`);
          if (type === 'permanent') {
            this.permanentCities = response.data;
          } else {
            this.temporaryCities = response.data;
          }
        }
      } catch (error) {
        console.error('Failed to fetch cities:', error);
      }
    },
    async registerUser() {
      try {
          const response = await axios.post('/register', this.form);
          // On success, show a success message
          this.success = 'Registration successful! Please check your email to activate your account.';
          this.error = null;

          // Redirect to the home page after 2 seconds
          setTimeout(() => {
            this.$router.push({ name: 'home' });  // 'home' should match the name of your home route
          }, 2000);
      } catch (error) {
        this.error = error.response.data.errors || 'Registration failed';
        this.success = null;
      }
    }
  },
  mounted() {
    this.fetchStates();
  }
};
</script>
