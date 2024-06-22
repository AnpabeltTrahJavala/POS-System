<template>
    <v-container style="padding: 1.5rem;">
      <h2>Order Form</h2>
      <br />
      <h2>Item List</h2>
      <br />
      <v-btn @click="showProductModal = true">Add Product</v-btn>
      <br /><br />
      <v-table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Subtotal</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(item, index) in selectedProducts" :key="index">
            <td>{{ item.id }}</td>
            <td>{{ item.name }}</td>
            <td>
              <v-text-field v-model="item.quantity" type="number" min="1" @input="updateSubtotal(item)" />
            </td>
            <td>{{ formatRupiah(item.price) }}</td>
            <td>{{ formatRupiah(item.subtotal) }}</td>
            <td>
              <v-btn icon @click="removeProduct(item.id)">
                <i class="ri-delete-bin-line icon"></i>
              </v-btn>
            </td>
          </tr>
        </tbody>
      </v-table>
  
      <br />
      <hr />
  
      <br />
      <h2>Order Information</h2>
      <br />
  
      <v-dialog v-model="showProductModal" max-width="800px">
        <v-card>
          <v-card-title>
            <span class="headline">Select Products</span>
          </v-card-title>
  
          <v-card-text>
            <v-table>
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Name</th>
                  <th>Price</th>
                  <th>Stock</th>
                </tr>
              </thead>
              <tbody>
                <tr style="cursor: pointer;" v-for="item in products" :key="item.id" @click="selectProduct(item)">
                  <td>{{ item.id }}</td>
                  <td>{{ item.name }}</td>
                  <td>{{ item.sell_price }}</td>
                  <td>{{ item.stock }}</td>
                </tr>
              </tbody>
            </v-table>
          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="blue darken-1" text @click="closeProductModal">Close</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
  
      <v-form @submit.prevent="updateForm">
        <v-row>
          <v-col cols="12" md="6">
            <v-text-field v-model="form.no_invoice" label="Invoice Number" placeholder="Invoice Number" />
          </v-col>
          <v-col cols="12" md="6">
            <v-text-field v-model="form.nama_lengkap" label="Name" placeholder="Name" />
          </v-col>
          <v-col cols="12" md="6">
            <v-select
              v-model="form.email"
              :items="emailOptions"
              label="Email"
              item-text="email"
              item-value="id"
            />
          </v-col>
          <v-col cols="12" md="6">
            <v-text-field v-model="form.nomor_telpon" label="Phone Number" placeholder="Phone Number" />
          </v-col>
          <v-col cols="12" md="12">
            <v-textarea v-model="form.alamat" label="Address" placeholder="Address" />
          </v-col>
          <v-col cols="12" md="4">
            <v-text-field type="number" v-model="form.shipping_cost" label="Shipping Cost" placeholder="Shipping Cost" />
          </v-col>
          <v-col cols="12" md="4">
            <v-text-field type="number" v-model="form.additional_cost" label="Additional Cost" placeholder="Additional Cost" />
          </v-col>
          <v-col cols="12" md="4">
            <v-text-field type="number" v-model="form.discount" label="Discount" placeholder="Discount" />
          </v-col>
          <v-col cols="12" md="12">
            <v-select
                v-model="form.status"
                :items="['Waiting for Payment', 'Paid', 'Processed', 'Completed']"
                label="Status"
            />
            </v-col>
          <v-col cols="12" md="12">
            <v-textarea v-model="form.notes" label="Notes" placeholder="Notes" />
          </v-col>
          <v-col cols="12" md="12">
            <center>
              <p>Subtotal: <strong>{{ formatRupiah(totalSubtotal) }}</strong></p>
              <p>Total: <strong>{{ formatRupiah(totalAmount) }}</strong></p>
            </center>
          </v-col>
          <v-col cols="12" class="d-flex gap-4">
            <v-btn type="submit" style="width: 100%;">Save Order</v-btn>
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
import { computed, onMounted, ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
  
  const route = useRoute();
  const router = useRouter();
  const form = ref({
    no_invoice: '',
    nama_lengkap: '',
    email: '',
    nomor_telpon: '',
    alamat: '',
    shipping_cost: 0,
    additional_cost: 0,
    discount: 0,
    notes: '',
    status: 'Waiting for Payment'
  });
  
  const snackbar = ref(false);
  const snackbarMessage = ref('');
  const snackbarColor = ref('');
  const snackbarTimeout = ref(3000);
  const emailOptions = ref([]);
  
  const formatRupiah = (number) => {
    return new Intl.NumberFormat('id-ID', {
      style: 'currency',
      currency: 'IDR',
      minimumFractionDigits: 0,
      maximumFractionDigits: 0
    }).format(number);
  };
  
  const fetchForm = async () => {
    try {
      if (route.params.id > 0) {
        const response = await axios.get(`/api/orders?id=${route.params.id}`);
        form.value = response.data;
  
        // Populate selected products
        selectedProducts.value = response.data.details.map(detail => ({
          id: detail.id_product,
          name: detail.name,
          quantity: detail.quantity,
          price: detail.price,
          subtotal: detail.quantity * detail.price,
        }));
  
        calculateTotalSubtotal();
      }
  
      const fetchCustomer = async () => {
        try {
          const response = await axios.get('/api/customers');
          emailOptions.value = response.data.map(i => `${i.email}`);
        } catch (error) {
          console.error('Error fetching customers:', error);
        }
      };
      fetchCustomer();
    } catch (error) {
      console.error('Error fetching data:', error);
    }
  };
  
  const updateForm = async (event) => {
    event.preventDefault();
    try {
      const orderDetails = selectedProducts.value.map((product, index) => ({
        id: index + 1,
        id_product: product.id,
        name: product.name,
        quantity: product.quantity,
        price: product.price
      }));
  
      if (totalAmount.value < 0) {
        alert('Total harga tidak boleh minus');
        return;
      }
      if (totalSubtotal.value <= 0) {
        alert('Silahkan pilih produk terlebih dahulu');
        return;
      }
  
      const orderData = {
        id: route.params.id,
        no_invoice: form.value.no_invoice,
        nama_lengkap: form.value.nama_lengkap,
        email: form.value.email,
        alamat: form.value.alamat,
        nomor_telpon: form.value.nomor_telpon,
        subtotal: totalSubtotal.value,
        shipping_cost: form.value.shipping_cost,
        additional_cost: form.value.additional_cost,
        discount: form.value.discount,
        total: totalAmount.value,
        status: form.value.status,
        notes: form.value.notes,
        timestamp: Math.floor(Date.now() / 1000), // Current timestamp in seconds
        timestamp_str: new Date().toLocaleDateString('en-GB', {
          day: 'numeric', month: 'long', year: 'numeric'
        }),
        details: orderDetails
      };
  
      const response = await axios.post(`/api/save_orders?id=${route.params.id}`, orderData, {
        headers: {
          'Content-Type': 'application/json',
        },
      });
  
      if (response.status === 200) {
        snackbarMessage.value = 'Data berhasil disimpan';
        snackbarColor.value = 'success';
        snackbar.value = true;
        router.push('/orders');
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
  
  // Product Management
  const products = ref([]);
  const selectedProducts = ref([]);
  const productHeaders = [
    { text: 'ID', value: 'id' },
    { text: 'Name', value: 'name' },
    { text: 'Price', value: 'sell_price' },
    { text: 'Stock', value: 'stock' },
  ];
  const showProductModal = ref(false);
  
  const fetchProducts = async () => {
    try {
      const response = await axios.get('/api/products');
      products.value = response.data;
    } catch (error) {
      console.error('Error fetching products:', error);
    }
  };
  
  const selectProduct = (product) => {
    const existingProduct = selectedProducts.value.find(p => p.id === product.id);
    if (existingProduct) {
      existingProduct.quantity += 1;
      updateSubtotal(existingProduct);
    } else {
      selectedProducts.value.push({
        id: product.id,
        name: product.name,
        quantity: 1,
        price: product.sell_price,
        subtotal: product.sell_price,
      });
    }
    calculateTotalSubtotal();
  };
  
  const removeProduct = (productId) => {
    selectedProducts.value = selectedProducts.value.filter(p => p.id !== productId);
    calculateTotalSubtotal();
  };
  
  const updateSubtotal = (item) => {
    item.subtotal = item.quantity * item.price;
    calculateTotalSubtotal();
  };
  
  const calculateTotalSubtotal = () => {
    totalSubtotal.value = selectedProducts.value.reduce((total, product) => total + product.subtotal, 0);
    totalAmount.value = calculateTotalAmount();
  };
  
  const totalSubtotal = computed(() => {
    return selectedProducts.value.reduce((total, product) => total + product.subtotal, 0);
  });
  
  const totalAmount = computed(() => {
    const subtotal = totalSubtotal.value;
    const shippingCost = parseInt(form.value.shipping_cost) || 0;
    const additionalCost = parseInt(form.value.additional_cost) || 0;
    const discount = parseInt(form.value.discount) || 0;
    return subtotal + shippingCost + additionalCost - discount;
  });
  
  const closeProductModal = () => {
    showProductModal.value = false;
  };
  
  onMounted(() => {
    fetchForm();
    fetchProducts();
  });
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
  .selected-products-table {
    width: 100%;
    border-collapse: collapse;
  }
  .selected-products-table th,
  .selected-products-table td {
    border: 1px solid #ccc;
    padding: 8px;
    text-align: left;
  }
  </style>
  