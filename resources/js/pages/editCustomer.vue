<template>
  <v-container style="padding: 1.5rem;">
    <h2>Customer Form</h2>
    <br />
    <v-form @submit.prevent="updateForm">
      <v-row>
        <v-col cols="12" md="12">
          <v-text-field v-model="form.nama_lengkap" label="Name" placeholder="Name" />
        </v-col>
        <v-col cols="12" md="12">
          <v-text-field v-model="form.email" label="Email" placeholder="Email" />
        </v-col>
        <v-col cols="12" md="12">
          <v-text-field v-model="form.nomor_telpon" label="Phone Number" placeholder="Phone Number" />
        </v-col>
        <v-col cols="12" md="12">
          <v-textarea v-model="form.alamat" label="Address" placeholder="Address" />
        </v-col>
        <v-col cols="12" md="12">
          <v-text-field v-model="form.nama_perusahaan" label="Company" placeholder="Company" />
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
  nama_lengkap: '',
  email: '',
  nomor_telpon: '',
  alamat: '',
  nama_perusahaan: '',
  timestamp_str: '',
});

const snackbar = ref(false);
const snackbarMessage = ref('');
const snackbarColor = ref('');
const snackbarTimeout = ref(3000);
const selectedFile = ref(null);

const fetchForm = async () => {
  try {
    const response = await axios.get(`/api/customers?id=${route.params.id}`);
    form.value = response.data;
  } catch (error) {
    console.error('Error fetching data:', error);
  }
};

const handleFileUpload = (file) => {
  selectedFile.value = file;
};

const updateForm = async (event) => {
  event.preventDefault();
  try {
    const formData = new FormData();
    formData.append('nama_lengkap', form.value.nama_lengkap);
    formData.append('email', form.value.email);
    formData.append('nomor_telpon', form.value.nomor_telpon);
    formData.append('alamat', form.value.alamat);
    formData.append('nama_perusahaan', form.value.nama_perusahaan);

    const response = await axios.post(`/api/save_customers?id=${route.params.id}`, formData, {
      headers: {
        'Content-Type': 'form-data',
      },
    });

    if (response.status === 200) {
      snackbarMessage.value = 'Data berhasil disimpan';
      snackbarColor.value = 'success';
      snackbar.value = true;
      router.push('/customer');
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
