<template>
  <div>
    <h1>User List</h1>

    <!-- Loading Spinner -->
    <div v-if="isLoading" class="text-center">
      <div class="spinner-border" role="status">
        <span class="sr-only">Loading...</span>
      </div>
    </div>

    <!-- Error Message -->
    <div v-if="errorMessage" class="alert alert-danger">
      {{ errorMessage }}
    </div>

    <!-- User Table -->
    <table v-if="!isLoading && users.length > 0" class="table mt-4">
      <thead>
        <tr>
          <th>Name</th>
          <th>Email</th>
          <th>Status</th>
          <th>Role</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="user in users" :key="user.id">
          <td>{{ user.name }}</td>
          <td>{{ user.email }}</td>
          <td>{{ capitalize(user.status) }}</td>
          <td>{{ capitalize(user.role) }}</td>
          <td>
            <router-link
              :to="{ name: 'userEdit', params: { id: user.id }}"
              class="btn btn-warning btn-sm"
            >
              Edit
            </router-link>
            <button
              @click="confirmDelete(user)"
              class="btn btn-danger btn-sm ml-2"
            >
              Delete
            </button>
          </td>
        </tr>
      </tbody>
    </table>

    <!-- No Users Found -->
    <p v-if="!isLoading && users.length === 0" class="text-center">
      No users found.
    </p>

    <!-- Confirmation Modal (show only if both selectedUser and showDeleteModal are true) -->
    <div v-if="showDeleteModal && selectedUser" class="modal">
      <div class="modal-content">
        <span class="close" @click="closeModal">&times;</span>
        <p>Are you sure you want to delete {{ selectedUser.name }}?</p>
        <button class="btn btn-danger" @click="deleteUser(selectedUser.id)">
          Confirm Delete
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: "usersList",
  data() {
    return {
      users: [],
      isLoading: false,
      errorMessage: '',
      selectedUser: null, // Track user to be deleted
      showDeleteModal: false, // Track modal visibility
    };
  },
  async mounted() {
    await this.fetchUsers(); // Fetch users when the component mounts
  },
  methods: {
    // Fetch the list of users
    async fetchUsers() {
      this.isLoading = true;
      this.errorMessage = '';
      try {
        const response = await axios.get('/users');
        this.users = response.data;
      } catch (error) {
        this.errorMessage = 'Failed to fetch users. Please try again later.';
        console.error('Failed to fetch users:', error);
      } finally {
        this.isLoading = false;
      }
    },

    // Confirm delete user
    confirmDelete(user) {
      this.selectedUser = user;
      this.showDeleteModal = true;
    },

    // Close the modal
    closeModal() {
      this.showDeleteModal = false;
      this.selectedUser = null;
    },

    // Delete a user after confirmation
    async deleteUser(id) {
      this.showDeleteModal = false; // Close modal
      try {
        await axios.delete(`/user/${id}`);
        this.users = this.users.filter(user => user.id !== id);
        alert('User deleted successfully!');
      } catch (error) {
        this.errorMessage = 'Failed to delete user. Please try again later.';
        console.error('Failed to delete user:', error);
      }
    },

    // Helper function to capitalize the first letter
    capitalize(str) {
      return str ? str.charAt(0).toUpperCase() + str.slice(1) : '';
    },
  },
};
</script>

<style scoped>
.modal {
  display: block;
  position: fixed;
  z-index: 1;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  overflow: auto;
  background-color: rgba(0, 0, 0, 0.4);
}

.modal-content {
  background-color: #fefefe;
  margin: 15% auto;
  padding: 20px;
  border: 1px solid #888;
  width: 30%;
}

.close {
  color: #aaa;
  float: right;
  font-size: 28px;
  font-weight: bold;
}

.close:hover,
.close:focus {
  color: black;
  text-decoration: none;
  cursor: pointer;
}
</style>
