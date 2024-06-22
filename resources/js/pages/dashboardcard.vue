<template>
    <v-card style="padding: 1rem; padding-bottom: 1.5rem;">
      <div style="text-align: center;">
        <i :class="iconClass" style="font-size: 2rem; color: #A5A6F6;"></i>
        </div>
      <v-card-title style="text-align: center; font-size: 14px; color: white; font-weight: 100;">{{ title }}</v-card-title>
      <v-card-subtitle style="font-weight: bold; font-size: 24px; color: #fff;">
        <center>{{ stats }}</center>
      </v-card-subtitle>
    </v-card>
  </template>
  
  <script setup>
  const props = defineProps({
    title: {
      type: String,
      required: true,
    },
    stats: {
      type: [String, Number],
      required: true,
    },
    icon: {
      type: String,
      required: true,
    },
  })
  
  const iconClass = computed(() => `ri-${props.icon}-line`)
  </script>
  
  <style scoped>
  v-card {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    padding: 16px;
    text-align: center;
  }
  v-card-title {
    font-size: 14px;
    font-weight: 100;
  }
  v-card-subtitle {
    font-size: 24px;
    font-weight: bold;
    color: #000000;
    margin-top: 8px;
  }
  </style>
  