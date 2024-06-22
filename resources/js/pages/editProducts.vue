<template>
  <v-container style="padding: 1.5rem;">
    <h2>Product Form</h2>
    <br />
    <v-form @submit.prevent="updateForm">
      <v-row>
        <v-col cols="12" class="d-flex justify-center">
            <img :src="form.thumbnailUrl || form.thumbnail" alt="Thumbnail" style="object-fit: cover; width: 150px; height: 150px;">
        </v-col>
        <v-col cols="12" md="12">
            <v-file-input
                label="Upload Thumbnail"
                @change="onThumbnailChange"
                accept="image/*"
            />
        </v-col>
        <v-col cols="12" md="12">
          <v-text-field v-model="form.name" label="Name" placeholder="Name" />
        </v-col>
        <v-col cols="12" md="12">
            <v-select
                v-model="form.category"
                :items="categoryOptions"
                label="Category"
                item-text="category"
                item-value="id"
            />
        </v-col>
        <v-col cols="12" md="12">
          <v-textarea v-model="form.description" label="Description" placeholder="Description" />
        </v-col>
        <v-col cols="12" md="12">
          <v-text-field v-model="form.buy_price" type="number" label="Buy Price" placeholder="Buy Price" />
        </v-col>
        <v-col cols="12" md="12">
          <v-text-field v-model="form.sell_price" type="number" label="Sell Price" placeholder="Sell Price" />
        </v-col>
        <v-col cols="12" md="12">
          <v-text-field v-model="form.stock" type="number" label="Stock" placeholder="Stock" />
        </v-col>
        <v-col cols="12" class="d-flex gap-4">
          <v-btn type="submit" style="width: 100%;">Save Data</v-btn>
        </v-col>
      </v-row>
    </v-form>

    <v-snackbar v-model="snackbar" :timeout="snackbarTimeout" :color="snackbarColor" top right>
      {{ snackbarMessage }}
      <template v-slot:action="{ attrs }">
        <v-btn color="white" text v-bind="attrs" @click="snackbar = false">Close</v-btn>
      </template>
    </v-snackbar>
  </v-container>
</template>

<script setup>
import axios from 'axios';
import { onMounted, ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';

const route = useRoute();
const router = useRouter();
const form = ref({
  name: '',
  description: '',
  stock: '',
  category: '',
  thumbnail: '/images/products/default.png',
  thumbnailUrl: '',
  buy_price: '',
  sell_price: '',
  timestamp_str: '',
});

const loading = ref(false); // Added loading state
const snackbar = ref(false);
const snackbarMessage = ref('');
const snackbarColor = ref('');
const snackbarTimeout = ref(3000);
const selectedFile = ref(null);
const categoryOptions = ref([]);

const fetchForm = async () => {
  try {
    if (route.params.id > 0) {
        const response = await axios.get(`/api/products?id=${route.params.id}`);
        form.value = response.data;
    }
    const fetchCategories = async () => {
        try {
            const response = await axios.get('/api/categories');
            categoryOptions.value = response.data.map(i => `${i.title}`);
        } catch (error) {
            console.error('Error fetching categories:', error);
        }
    };
    fetchCategories();
  } catch (error) {
    console.error('Error fetching data:', error);
  }
};

const onThumbnailChange = (event) => {
  const file = event.target.files[0];
  if (file) {
    const reader = new FileReader();
    reader.onload = (e) => {
      form.value.thumbnailUrl = e.target.result;
    };
    reader.readAsDataURL(file);
    form.value.thumbnail = file;
  }
};

const updateForm = async (event) => {
  event.preventDefault();
  try {
    const formData = new FormData();
    for (const key in form.value) {
      formData.append(key, form.value[key]);
    }

    const response = await axios.post(`/api/save_products?id=${route.params.id}`, formData, {
      headers: {
        'Content-Type': 'form-data',
      },
    });

    if (response.status === 200) {
      snackbarMessage.value = 'Data berhasil disimpan';
      snackbarColor.value = 'success';
      snackbar.value = true;
      router.push('/products');
    } else {
      throw new Error('Unexpected response status');
    }
  } catch (error) {
    snackbarMessage.value = 'Error updating data';
    snackbarColor.value = 'error';
    snackbar.value = true;
    console.error('Error updating data:', error);
  }
};

const resetForm = () => {
    fetchForm();
};

onMounted(fetchForm);
</script>

<style scoped>
.d-flex {
  display: flex;
}
.gap-4 {
  gap: 1rem;
}
.justify-center {
  justify-content: center;
}
</style>
