<!-- this is simple edit working edit for user -->
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
        <button type="submit" class="btn btn-primary mt-3">Update</button>
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
      };
    },
    async mounted() {
      try {
        const response = await axios.get(`/user/show/${this.$route.params.id}`);  // Fetch user by ID
        this.user = response.data.user;
        console.log(response.data.user);
      } catch (error) {
        console.error('Failed to fetch user:', error);
      }
    },
    methods: {
      async updateUser() {
        try {
          await axios.put(`/users/${this.$route.params.id}`, this.user);  // Update user by ID
          this.$router.push({ name: 'userList' });  // Redirect to user list after updating
        } catch (error) {
          console.error('Failed to update user:', error);
        }
      },
    },
  };
  </script>
  