<template>
    <div>
      <h1>User List</h1>
      
      <table class="table mt-4">
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
            <td>{{ user.status }}</td>
            <td>{{ user.role }}</td>
            <td>
              <router-link :to="{ name: 'userEdit', params: { id: user.id }}" class="btn btn-warning">Edit</router-link>
              <button @click="deleteUser(user.id)" class="btn btn-danger">Delete</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    name:"usersList",
    data() {
      return {
        users: [],
      };
    },
    async mounted() {
      try {
        const response = await axios.get('/users');  // API call to fetch users
        this.users = response.data;
      } catch (error) {
        console.error('Failed to fetch users:', error);
      }
    },
    methods: {
      async deleteUser(id) {
        try {
          await axios.delete(`/users/${id}`);  // API call to delete user
          this.users = this.users.filter(user => user.id !== id);  // Remove from local list
        } catch (error) {
          console.error('Failed to delete user:', error);
        }
      },
    },
  };
  </script>
  