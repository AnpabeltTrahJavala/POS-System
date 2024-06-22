<script setup>
import axios from 'axios';
import { computed, onMounted, ref } from 'vue';
import { useRouter } from 'vue-router';

const router = useRouter();

const data = ref([]);
const searchQuery = ref('');
const startDate = ref('');
const endDate = ref('');
const selectedStatus = ref('');

const fetchData = async () => {
  try {
    const response = await axios.get('/api/products');
    data.value = response.data;
  } catch (error) {
    console.error('Error fetching data:', error);
  }
};

const formatRupiah = (number) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    }).format(number);
};

onMounted(fetchData);

const addData = (id) => {
  router.push({ path: `/editProducts/0` });
};

const edit = (id) => {
  router.push({ path: `/editProducts/${id}` });
};

const _delete = async (id) => {
  try {
    const response = await axios.get(`/api/delete_products?id=${id}`);
    if (response.status === 200) {
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
    await axios.post('/api/update_status', { id: d.id, status: d.status, type: 'products' });
  } catch (error) {
    console.error('Error updating status:', error);
  }
};

const filteredData = computed(() => {
  return data.value.filter(d => {
    const matchesSearch = d.name.toLowerCase().includes(searchQuery.value.toLowerCase());
    const dateTimestamp = new Date(d.timestamp_str);
    const withinDateRange = (!startDate.value || dateTimestamp >= new Date(startDate.value)) &&
                            (!endDate.value || dateTimestamp <= new Date(endDate.value));
    const matchesStatus = (!selectedStatus.value || selectedStatus.value == "All") || d.status == (selectedStatus.value == "Active" ? 1 : 0);

    return matchesSearch && withinDateRange && matchesStatus;
  });
});
</script>

<template>
    <v-container>
    <h2 style="margin-bottom: 2%; margin-top: -2%;">Products</h2>
    <v-row no-gutters>
      <v-col cols="12" md="3">
        <label for="searchQuery">Search</label>
      </v-col>
      <v-col cols="12" md="5">
        <v-text-field
          id="searchQuery"
          v-model="searchQuery"
          prepend-inner-icon="ri-search-line"
          placeholder="Search by Product Name"
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
          :items="['All', 'Active', 'Disabled']" 
          placeholder="Choose Status"
          persistent-placeholder
        />
      </v-col>
    </v-row>

    <div style="margin-top: 1%; margin-bottom: 1%; color: #fff; visibility: hidden;">.</div>

    <v-row>
      <v-col cols="12" class="d-flex justify-start mb-4">
        <v-btn color="primary" @click="addData">Add Data</v-btn>
      </v-col>
    </v-row>

  <v-table>
    <thead>
      <tr>
        <th class="text-uppercase">#</th>
        <th class="text-uppercase">Name</th>
        <th class="text-uppercase">Category</th>
        <th class="text-uppercase">Stock</th>
        <th class="text-uppercase">Buy Price</th>
        <th class="text-uppercase">Sell Price</th>
        <th class="text-uppercase">Date</th>
        <th class="text-uppercase">Status</th>
        <th class="text-uppercase">Action</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="d in filteredData" :key="d.id">
        <td>{{ d.id }}</td>
        <td>{{ d.name }}</td>
        <td>{{ d.category.title }}</td>
        <td>{{ d.stock }}</td>
        <td>{{ formatRupiah(d.buy_price) }}</td>
        <td>{{ formatRupiah(d.sell_price) }}</td>
        <td>{{ d.timestamp_str }}</td>
        <td>
          <v-switch v-model="d.status" @click="toggleStatus(d)" :value="1"></v-switch>
        </td>
        <td>
          <i class="ri-pencil-line icon" @click="edit(d.id)"></i>
          <i class="ri-delete-bin-line icon" @click="_delete(d.id)"></i>
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
