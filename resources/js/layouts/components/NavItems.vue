<script setup>
import VerticalNavSectionTitle from '@/@layouts/components/VerticalNavSectionTitle.vue';
import VerticalNavLink from '@layouts/components/VerticalNavLink.vue';
</script>

<template>
  <!-- 👉 Dashboards -->
  <VerticalNavSectionTitle
    :item="{
      heading: 'Menu',
    }"
  />
  <VerticalNavLink
    :item="{
      title: 'Dashboard',
      icon: 'ri-home-smile-line',
      to: '/dashboard',
    }"
  />

  <VerticalNavLink
    :item="{
      title: 'Categories',
      icon: 'ri-file-list-line',
      to: '/categories',
    }"
  />
  <VerticalNavLink
    :item="{
      title: 'Products',
      icon: 'ri-store-line',
      to: '/products',
    }"
  />
  <VerticalNavLink
    :item="{
      title: 'Customers',
      icon: 'ri-user-line',
      to: '/customer',
    }"
  />
  <VerticalNavLink
    :item="{
      title: 'Orders',
      icon: 'ri-file-list-3-line',
      to: '/orders',
    }"
  />

  <!-- <VerticalNavLink
    :item="{
      title: 'Account Settings',
      icon: 'ri-user-settings-line',
      to: '/account-settings',
    }"
  /> -->

  <VerticalNavLink
    :item="{
      title: 'Logout',
      icon: 'ri-login-box-line',
      to: '/login',
    }"
  />
</template>
