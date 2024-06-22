<script setup>
import axios from 'axios';
import { onMounted, ref } from 'vue';
import { useRouter } from 'vue-router';

const router = useRouter();

const recentDevices = ref([
  {
    type: 'New for you',
    email: true,
    browser: true,
    app: true,
  },
  {
    type: 'Account activity',
    email: true,
    browser: true,
    app: true,
  },
  {
    type: 'A new browser used to sign in',
    email: true,
    browser: true,
    app: false,
  },
  {
    type: 'A new device is linked',
    email: true,
    browser: false,
    app: false,
  },
])

const selectedNotification = ref('Only when I\'m online')
const notifications = ref([])

const fetchNotifications = async () => {
  try {
    const response = await axios.get('/api/notifikasi_admin')
    notifications.value = response.data
  } catch (error) {
    console.error('Failed to fetch notifications', error)
  }
}

const saveChanges = async () => {
  try {
    await axios.post('/api/notifikasi_admin', {
      recentDevices: recentDevices.value,
      selectedNotification: selectedNotification.value,
    })
    alert('Changes saved successfully')
  } catch (error) {
    console.error('Failed to save changes', error)
  }
}

const deleteNotification = async (id) => {
  try {
    await axios.get(`/api/hapus_notifikasi_admin?id=${id}`)
    notifications.value = notifications.value.filter(notification => notification.id !== id)
    // alert('Notification deleted successfully')
  } catch (error) {
    console.error('Failed to delete notification', error)
  }
}

const redirectToChat = (userId) => {
  router.push(`/chatdetail/${userId}`);
};

onMounted(() => {
  fetchNotifications()
})
</script>

<template>
  <!-- <VCard title="Recent Devices">
    <VCardText>
      We need permission from your browser to show notifications.
      <a href="javascript:void(0)">Request Permission</a>
    </VCardText>

    <VTable class="text-no-wrap">
      <thead>
        <tr>
          <th scope="col">
            Type
          </th>
          <th scope="col">
            EMAIL
          </th>
          <th scope="col">
            BROWSER
          </th>
          <th scope="col">
            App
          </th>
        </tr>
      </thead>
      <tbody>
        <tr
          v-for="device in recentDevices"
          :key="device.type"
        >
          <td>
            {{ device.type }}
          </td>
          <td>
            <VCheckbox v-model="device.email" />
          </td>
          <td>
            <VCheckbox v-model="device.browser" />
          </td>
          <td>
            <VCheckbox v-model="device.app" />
          </td>
        </tr>
      </tbody>
    </VTable>
    <VDivider />

    <VCardText>
      <VForm @submit.prevent="saveChanges">
        <p class="text-base font-weight-medium">
          When should we send you notifications?
        </p>

        <VRow>
          <VCol
            cols="12"
            sm="6"
          >
            <VSelect
              v-model="selectedNotification"
              mandatory
              :items="['Only when I\'m online', 'Anytime']"
            />
          </VCol>
        </VRow>

        <div class="d-flex flex-wrap gap-4 mt-4">
          <VBtn type="submit">
            Save Changes
          </VBtn>
          <VBtn
            color="secondary"
            variant="outlined"
            type="reset"
          >
            Reset
          </VBtn>
        </div>
      </VForm>
    </VCardText>
  </VCard> -->

  <VCard title="Notifications">
    <VCardText v-if="notifications.length === 0">
      No notifications available.
    </VCardText>
    <VCardText v-else>
      <VTable>
        <thead>
          <tr>
            <th scope="col">Title</th>
            <th scope="col">Description</th>
            <th scope="col">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="notification in notifications"
            :key="notification.id"
          >
            <td>{{ notification.judul }}</td>
            <td>{{ notification.deskripsi }}</td>
            <td>
              <VBtn
                color="secondary"
                @click="redirectToChat(notification.user_id)"
              >
                Go to Chat
              </VBtn>&nbsp;&nbsp;
              <VBtn
                color="red"
                @click="deleteNotification(notification.id)"
              >
                Delete
              </VBtn>
            </td>
          </tr>
        </tbody>
      </VTable>
    </VCardText>
  </VCard>
</template>
