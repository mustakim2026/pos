<template>
  <div class="container mt-4">
    <form @submit.prevent="submitForm">
      <div class="mb-3">
        <label for="firstName" class="form-label">Product Name</label>
        <input type="text" class="form-control" id="firstName" placeholder="Enter product name" v-model="formData.name">
      </div>
      <div class="mb-3">
        <label for="lastName" class="form-label">Item </label>
        <input type="text" class="form-control" id="lastName" placeholder="Enter Item name" v-model="formData.item">
      </div>
      <div class="mb-3">
        <label for="handle" class="form-label">Price</label>
        <input type="text" class="form-control" id="handle" placeholder="Enter Price" v-model="formData.price">
      </div>
      <div class="btn-group" role="group" aria-label="Basic example">
        <button type="button" class="btn btn-primary" @click="submitForm">submit</button>
      </div>
    </form>
  </div>
</template>

<script>
import axios from 'axios';
import router from '../../router';

export default {
  data() {
    return {
      formData: {
        name: '',
        item: '',
        price: ''
      }
    }
  },
  methods: {
    submitForm() {
      axios.post('/api/products/add', this.formData)
        .then(res => {
          console.log(res.data);
          router.push({ name: 'UserList' });
        })
        .catch(err => {
          console.log(err);
        });
      console.log(this.formData);
    }
  }
}
</script>

<style></style>