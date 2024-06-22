<script setup>
import axios from 'axios';
import { onMounted, ref } from 'vue';
import { useRouter } from 'vue-router';

const router = useRouter();

const data = ref([]);
const searchQuery = ref('');
const startDate = ref('');
const endDate = ref('');
const selectedStatus = ref('');

const fetchData = async () => {
  try {
    const response = await axios.get('/api/admin_list');
    console.log(response)
    data.value = response.data;
  } catch (error) {
    console.error('Error fetching data:', error);
  }
};

onMounted(fetchData);

const addData = (id) => {
  // Handle the edit action
  // console.log('Edit partner with ID:', id);
  router.push({ path: `/editadmin/0` });
};

const edit = (id) => {
  // Handle the edit action
  // console.log('Edit partner with ID:', id);
  router.push({ path: `/editadmin/${id}` });
};

const view = (id) => {
  // Handle the view action
  console.log('View partner with ID:', id);
  router.push({ path: `/detailadmin/${id}` });
}

const _delete = async (id) => {
  // Handle the delete action
  // console.log('Delete partner with ID:', id);
  try {
    const response = await axios.get(`/api/delete_admin?id=${id}`);
    if (response.status === 200) {
      // Successfully deleted
      data.value = data.value.filter(d => d.id !== id);
    } else {
      throw new Error('Error deleting item');
    }
  } catch (error) {
    console.error('Error deleting education level:', error);
  }
}

const toggleStatus = async (d) => {
  d.status = d.status === 1 ? 0 : 1;
  try {
    await axios.post('/api/update_status', { id: d.id, status: d.status, type: 'admin' });
  } catch (error) {
    console.error('Error updating status:', error);
  }
};
const filteredData = computed(() => {
  return data.value.filter(d => {
    const matchesSearch = d.nama_lengkap.toLowerCase().includes(searchQuery.value.toLowerCase()) || d.email.toLowerCase().includes(searchQuery.value.toLowerCase());
    const dateTimestamp = new Date(d.timestamp_str);
    const withinDateRange = (!startDate.value || dateTimestamp >= new Date(startDate.value)) &&
                            (!endDate.value || dateTimestamp <= new Date(endDate.value));
    const matchesTipeKepegawaian = (!selectedStatus.value || selectedStatus.value == "Semua") || d.status == (selectedStatus.value == "Aktif" ? 1 : 0);

    return matchesSearch && withinDateRange && matchesTipeKepegawaian;
  });
});
</script>

<template>
  <v-container>
    <h2 style="margin-bottom: 2%; margin-top: -2%;">List</h2>
    <v-row no-gutters>
      <v-col cols="12" md="3">
        <label for="searchQuery">Search</label>
      </v-col>
      <v-col cols="12" md="5">
        <v-text-field
          id="searchQuery"
          v-model="searchQuery"
          prepend-inner-icon="ri-search-line"
          placeholder="Cari menggunakan Nama Lengkap / Email"
          persistent-placeholder
        />
      </v-col>
    </v-row>

    <br />

    <h3 style="font-weight: 500;">
      <i class="ri-filter-line"></i> Filter By
    </h3>

    <v-row no-gutters>
      <v-col cols="12" md="3">
        <label for="startDate" class="label-centered">Start Date</label>
      </v-col>
      <v-col cols="12" md="2">
        <v-text-field
          id="startDate"
          v-model="startDate"
          type="date"
          placeholder="Start Date"
          persistent-placeholder
        />
      </v-col>
    </v-row>

    <br />

    <v-row no-gutters>
      <v-col cols="12" md="3">
        <label for="endDate" class="label-centered">End Date</label>
      </v-col>
      <v-col cols="12" md="2">
        <v-text-field
          id="endDate"
          v-model="endDate"
          type="date"
          placeholder="End Date"
          persistent-placeholder
        />
      </v-col>
    </v-row>

    <br />

    <v-row no-gutters>
      <v-col cols="12" md="3">
        <label for="status" class="label-centered">Status</label>
      </v-col>
      <v-col cols="12" md="5">
        <v-select
          id="status"
          v-model="selectedStatus"
          :items="['Semua', 'Aktif', 'Tidak Aktif']" 
          placeholder="Pilih Status"
          persistent-placeholder
        />
      </v-col>
    </v-row>

    <div style="margin-top: 1%; margin-bottom: 1%; color: #fff; visibility: hidden;">.</div>
    
    <v-row>
      <v-col cols="12" class="d-flex justify-start mb-4">
        <v-btn color="primary" @click="addData">Tambah Data</v-btn>
      </v-col>
    </v-row>

    <v-table>
    <thead>
      <tr>
        <th class="text-uppercase">ID User</th>
        <th class="text-uppercase">Nama Lengkap</th>
        <th class="text-uppercase">Email</th>
        <th class="text-uppercase">Role</th>
        <th class="text-uppercase">Date</th>
        <th class="text-uppercase">Status</th>
        <th class="text-uppercase">Action</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="d in filteredData" :key="d.id">
        <td>{{ d.id }}</td>
        <td>{{ d.nama_lengkap }}</td>
        <td>{{ d.email }}</td>
        <td>{{ d.role }}</td>
        <td>{{ d.timestamp_str }}</td>
        <td>
          <template v-if="d.id != 1">
            <v-switch v-model="d.status" @click="toggleStatus(d)" :value="1"></v-switch>
          </template>
        </td>
        <td>
          <!-- <i class="ri-pencil-line icon" @click="edit(d.id)"></i>
          <i class="ri-eye-line icon" @click="view(d.id)"></i>
          <i class="ri-delete-bin-line icon" @click="_delete(d.id)"></i> -->
          <template v-if="d.id != 1">
            <i class="ri-pencil-line icon" @click="edit(d.id)"></i>
            <i class="ri-eye-line icon" @click="view(d.id)"></i>
            <i class="ri-delete-bin-line icon" @click="_delete(d.id)"></i>
          </template>
        </td>
      </tr>
    </tbody>
  </v-table>
</v-container>
</template>

<style scoped>
.icon {
  cursor: pointer;
  margin-right: 10px;
  font-size: 1.2em;
}

.label-centered {
  display: flex;
  align-items: center;
  height: 100%;
}
</style>
