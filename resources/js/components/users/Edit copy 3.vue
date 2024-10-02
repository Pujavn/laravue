<template>
  <div>
    <h1>Edit User</h1>
    <form @submit.prevent="updateUser">
      <div class="form-group">
        <label for="name">User Name</label>
        <input v-model="user.name" type="text" id="name" class="form-control" required />
      </div>
      <div class="form-group">
        <label for="email">Email</label>
        <input v-model="user.email" type="email" id="email" class="form-control" required />
      </div>
      <div class="form-group">
        <label for="status">Status</label>
        <select v-model="user.status" class="form-control" required>
          <option value="pending">Pending</option>
          <option value="active">Active</option>
          <option value="inactive">Inactive</option>
        </select>
      </div>
      <div class="form-group">
        <label for="role">Role</label>
        <select v-model="user.role" class="form-control" required>
          <option value="user">User</option>
          <option value="admin">Admin</option>
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
        <select v-model="selectedCities" class="form-control" multiple>
          <option v-for="city in cities" :key="city.id" :value="city.id">{{ city.name }}</option>
        </select>
      </div>

      <button type="submit" class="btn btn-primary mt-3">Update User</button>
    </form>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      user: {
        name: '',
        email: '',
        status: 'pending',
        role: 'user',
      },
      selectedState: '',    // Track the selected state ID
      selectedCities: [],   // Track the selected city IDs
      states: [],           // List of available states
      cities: [],           // List of cities for the selected state
    };
  },
  async mounted() {
    try {
      // Fetch user data by ID
      const response = await axios.get(`/user/show/${this.$route.params.id}`);
      this.user = response.data.user;

      // Pre-select the user's current state
      if (response.data.user.states.length > 0) {
        this.selectedState = response.data.user.states[0].id; // Assuming a single state
      }

      // Pre-select the user's current cities
      this.selectedCities = response.data.user.cities.map(city => city.id);

      // Fetch the available states and cities
      await this.fetchStates();
      await this.fetchCities();
    } catch (error) {
      console.error('Failed to fetch user:', error);
    }
  },
  methods: {
    // Fetch all available states from the backend
    async fetchStates() {
      try {
        const response = await axios.get('/states');
        this.states = response.data;
      } catch (error) {
        console.error('Failed to fetch states:', error);
      }
    },
    // Fetch cities based on the selected state
    async fetchCities() {
      try {
        if (this.selectedState) {
          const response = await axios.get(`/states/${this.selectedState}/cities`);
          this.cities = response.data;
        } else {
          this.cities = []; // Clear cities if no state is selected
        }
      } catch (error) {
        console.error('Failed to fetch cities:', error);
      }
    },
    // Update user details including state and city
    async updateUser() {
      try {
        await axios.put(`/user/update/${this.$route.params.id}`, {
          ...this.user,
          state_ids: [this.selectedState], // Send state ID as an array
          city_ids: this.selectedCities,   // Send selected city IDs
        });
        this.$router.push({ name: 'userList' });  // Redirect to user list after updating
      } catch (error) {
        console.error('Failed to update user:', error);
      }
    }
  }
};
</script>
