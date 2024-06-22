<template>
  <v-container style="background: #fff; padding: 1.5rem;">
    <h2>User Admin Detail</h2>
    <br />
    <v-form @submit.prevent="updateJobSeeker">
      <v-row>
        <v-col cols="12" md="6">
          <v-text-field v-model="jobSeeker.nama_lengkap" label="Nama Lengkap" placeholder="Nama Lengkap" />
        </v-col>
        <v-col cols="12" md="6">
          <v-text-field v-model="jobSeeker.email" label="Email" placeholder="Email" />
        </v-col>
        <v-col cols="12" md="6">
          <v-text-field v-model="jobSeeker.nik" label="NIK" placeholder="NIK" />
        </v-col>
        <v-col cols="12" md="6">
          <v-text-field v-model="jobSeeker.tanggal_lahir" label="Tanggal Lahir" placeholder="Tanggal Lahir" />
        </v-col>
        <v-col cols="12" md="6">
          <v-text-field v-model="jobSeeker.password" label="Password" type="password" placeholder="Password" />
        </v-col>
        <v-col cols="12" class="d-flex gap-4">
          <v-btn type="submit" style="width: 100%;">Simpan Data</v-btn>
        </v-col>
      </v-row>
    </v-form>

    <!-- Snackbar for notification -->
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
const jobSeeker = ref({
    nama_lengkap: '',
  email: '',
  nik: '',
  tanggal_lahir: '',
  password: '',
  timestamp_str: '',
});

const snackbar = ref(false);
const snackbarMessage = ref('');
const snackbarColor = ref('');
const snackbarTimeout = ref(3000);
const selectedFile = ref(null);

const fetchJobSeeker = async () => {
  try {
    const response = await axios.get(`/api/admin_list?id=${route.params.id}`);
    jobSeeker.value = response.data;
  } catch (error) {
    console.error('Error fetching job seeker:', error);
  }
};

const handleFileUpload = (file) => {
  selectedFile.value = file;
};

const validatePassword = (password) => {
  // Password must be at least 8 characters, include one special character, and one uppercase letter
  const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
  return regex.test(password);
};

const validateNIK = (nik) => {
  return /^\d{16}$/.test(nik);
};

const updateJobSeeker = async (event) => {
  event.preventDefault();

  if (!validatePassword(jobSeeker.value.password)) {
    snackbarMessage.value = 'Password must be at least 8 characters, include one special character, and one uppercase letter.';
    snackbarColor.value = 'error';
    snackbar.value = true;
    loading.value = false;
    return;
  }

  if (!validateNIK(jobSeeker.value.nik)) {
    snackbarMessage.value = 'NIK must be exactly 16 digits.';
    snackbarColor.value = 'error';
    snackbar.value = true;
    loading.value = false;
    return;
  }

  try {
    const formData = new FormData();
    formData.append('nama_lengkap', jobSeeker.value.nama_lengkap);
    formData.append('email', jobSeeker.value.email);
    formData.append('nik', jobSeeker.value.nik);
    formData.append('tanggal_lahir', jobSeeker.value.tanggal_lahir);
    formData.append('password', jobSeeker.value.password);

    const response = await axios.post(`/api/save_admin?id=${route.params.id}`, formData, {
      headers: {
        'Content-Type': 'form-data',
      },
    });

    if (response.status === 200) {
      snackbarMessage.value = 'Data berhasil disimpan';
      snackbarColor.value = 'success';
      snackbar.value = true;
      router.push('/adminlist');
    } else {
      throw new Error('Unexpected response status');
    }
  } catch (error) {
    snackbarMessage.value = 'Error updating job seeker';
    snackbarColor.value = 'error';
    snackbar.value = true;
    console.error('Error updating job seeker:', error);
  }
};

const resetForm = () => {
  fetchJobSeeker();
};

onMounted(fetchJobSeeker);
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
