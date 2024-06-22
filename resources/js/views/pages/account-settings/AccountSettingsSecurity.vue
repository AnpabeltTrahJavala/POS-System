<script setup>
import axios from 'axios';
import { onMounted, ref } from 'vue';

const isCurrentPasswordVisible = ref(false)
const isNewPasswordVisible = ref(false)
const isConfirmPasswordVisible = ref(false)
const currentPassword = ref('')
const newPassword = ref('')
const confirmPassword = ref('')
const oldPasswordFromServer = ref('')
const snackbar = ref(false);
const snackbarMessage = ref('');
const snackbarColor = ref('');
const snackbarTimeout = ref(3000);

const passwordRequirements = [
  'Minimum 8 characters long - the more, the better',
  'At least one uppercase character',
  'At least one symbol character',
]

const fetchOldPassword = async () => {
  try {
    const response = await axios.get('/api/admin_list?id=1')
    oldPasswordFromServer.value = response.data.password
  } catch (error) {
    console.error('Failed to fetch old password:', error)
  }
}

const validatePassword = (password) => {
  // Password must be at least 8 characters, include one special character, and one uppercase letter
  const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
  return regex.test(password);
};

const saveNewPassword = async () => {
  if (!validatePassword(newPassword.value)) {
    snackbarMessage.value = 'Password must be at least 8 characters, include one special character, and one uppercase letter.';
    snackbarColor.value = 'error';
    snackbar.value = true;
    loading.value = false;
    return;
  }

  if (currentPassword.value !== oldPasswordFromServer.value) {
    // alert('Current password is incorrect')
    snackbarMessage.value = 'Password lama Anda salah';
    snackbarColor.value = 'error';
    snackbar.value = true;
    return
  }
  if (newPassword.value !== confirmPassword.value) {
    // alert('New passwords do not match')
    // return
    snackbarMessage.value = 'Password baru dengan konfirmasi password tidak sama';
    snackbarColor.value = 'error';
    snackbar.value = true;
    return;
  }
  try {
    await axios.post('/api/save_admin_password?id=1', {
      password: newPassword.value
    })
    // alert('Password updated successfully')
    snackbarMessage.value = 'Password berhasil diupdate';
    snackbarColor.value = 'success';
    snackbar.value = true;
  } catch (error) {
    console.error('Failed to save new password:', error)
    // alert('Failed to save new password')
    snackbarMessage.value = 'Password gagal diupdate silahkan coba kembali. Error: '+error.message;
    snackbarColor.value = 'error';
    snackbar.value = true;
  }
}

onMounted(fetchOldPassword)
</script>

<template>
  <VRow>
    <!-- SECTION: Change Password -->
    <VCol cols="12">
      <VCard title="Change Password">
        <VForm>
          <VCardText>
            <!-- 👉 Current Password -->
            <VRow class="mb-3">
              <VCol cols="12" md="6">
                <!-- 👉 current password -->
                <VTextField
                  v-model="currentPassword"
                  :type="isCurrentPasswordVisible ? 'text' : 'password'"
                  :append-inner-icon="isCurrentPasswordVisible ? 'ri-eye-off-line' : 'ri-eye-line'"
                  autocomplete="on"
                  label="Current Password"
                  placeholder="············"
                  @click:append-inner="isCurrentPasswordVisible = !isCurrentPasswordVisible"
                />
              </VCol>
            </VRow>

            <!-- 👉 New Password -->
            <VRow>
              <VCol cols="12" md="6">
                <!-- 👉 new password -->
                <VTextField
                  v-model="newPassword"
                  :type="isNewPasswordVisible ? 'text' : 'password'"
                  :append-inner-icon="isNewPasswordVisible ? 'ri-eye-off-line' : 'ri-eye-line'"
                  label="New Password"
                  autocomplete="on"
                  placeholder="············"
                  @click:append-inner="isNewPasswordVisible = !isNewPasswordVisible"
                />
              </VCol>

              <VCol cols="12" md="6">
                <!-- 👉 confirm password -->
                <VTextField
                  v-model="confirmPassword"
                  :type="isConfirmPasswordVisible ? 'text' : 'password'"
                  :append-inner-icon="isConfirmPasswordVisible ? 'ri-eye-off-line' : 'ri-eye-line'"
                  autocomplete="on"
                  label="Confirm New Password"
                  placeholder="············"
                  @click:append-inner="isConfirmPasswordVisible = !isConfirmPasswordVisible"
                />
              </VCol>
            </VRow>
          </VCardText>

          <!-- 👉 Password Requirements -->
          <VCardText>
            <p class="text-base font-weight-medium mt-2">
              Password Requirements:
            </p>

            <ul class="d-flex flex-column gap-y-3">
              <li v-for="item in passwordRequirements" :key="item" class="d-flex">
                <div>
                  <VIcon size="7" icon="ri-checkbox-blank-circle-fill" class="me-3" />
                </div>
                <span class="font-weight-medium">{{ item }}</span>
              </li>
            </ul>
          </VCardText>

          <!-- 👉 Action Buttons -->
          <VCardText class="d-flex flex-wrap gap-4">
            <VBtn @click="saveNewPassword">Save changes</VBtn>

            <VBtn type="reset" color="secondary" variant="outlined">
              Reset
            </VBtn>
          </VCardText>
        </VForm>
      </VCard>
    </VCol>
    <!-- !SECTION -->
  </VRow>

  <!-- Snackbar for notification -->
  <v-snackbar v-model="snackbar" :timeout="snackbarTimeout" :color="snackbarColor" top right>
  {{ snackbarMessage }}
  <template v-slot:action="{ attrs }">
    <v-btn color="white" text v-bind="attrs" @click="snackbar = false">Close</v-btn>
  </template>
</v-snackbar>
</template>
