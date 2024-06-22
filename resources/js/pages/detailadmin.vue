<template>
  <v-container style="background: #fff; padding: 1.5rem;">
    <h2>User Admin Detail</h2>
    <br />
    <v-form @submit.prevent="updateJobSeeker">
      <v-row>
        <v-col cols="12" md="6">
          <v-text-field v-model="jobSeeker.nama_lengkap" label="Nama Lengkap" placeholder="Nama Lengkap" readonly />
        </v-col>
        <v-col cols="12" md="6">
          <v-text-field v-model="jobSeeker.email" label="Email" placeholder="Email" readonly />
        </v-col>
        <v-col cols="12" md="6">
          <v-text-field v-model="jobSeeker.nik" label="NIK" placeholder="NIK" readonly />
        </v-col>
        <v-col cols="12" md="6">
          <v-text-field v-model="jobSeeker.tanggal_lahir" label="Tanggal Lahir" placeholder="Tanggal Lahir"  readonly />
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

const updateJobSeeker = async () => {
  try {
    if (selectedFile.value) {
      const formData = new FormData();
    }
    await axios.put(`/api/admin_list?id=${route.params.id}`, jobSeeker.value);
    snackbarMessage.value = 'Data berhasil disimpan';
    snackbarColor.value = 'success';
    snackbar.value = true;
    // router.push('/jobseekerlist');
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
