<script setup>
import { onMounted, ref, watch } from 'vue';
import DashboardCard from './dashboardcard.vue'; // Adjust the import path as needed

const apiData = ref({})

const fetchData = async () => {
  // Replace with your actual API call
  const response = await fetch('/api/dashboard')
  apiData.value = await response.json()
}

onMounted(fetchData)

const cardData = ref([])

const createCardData = (title, stats, icon) => ({ title, stats, icon })

watch(apiData, (newData) => {
  cardData.value = [
    createCardData('Total Categories', newData.total_categories, 'file-list-line'),
    createCardData('Total Products', newData.total_products, 'store-line'),
    createCardData('Total Customer', newData.total_customer, 'user-line'),
    createCardData('Total Orders', newData.total_orders, 'file-list-3-line'),
  ]
})
</script>

<template>
  <h2 style="margin-bottom: 2%; margin-top: -2%;">Dashboard</h2>
  <v-row class="match-height">
    <v-col cols="12" md="6" v-for="(card, index) in cardData" :key="index">
      <DashboardCard :title="card.title" :stats="card.stats" :icon="card.icon" />
    </v-col>
  </v-row>
</template>
