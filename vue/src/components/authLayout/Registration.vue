<template>
<div class="flex min-h-full items-center bg-gray-100 justify-center py-12 px-4 sm:px-6 lg:px-8">
  <div class="w-full max-w-md space-y-8">
    <form class="bg-white p-6 rounded-lg" @submit="submit">
        <Alert v-if="Object.keys(errors).length" class="flex-col items-stretch text-sm">
            <div v-for="(field, i) of Object.keys(errors)" :key="i">
                <div v-for="(error, ind) of errors[field] || []" :key="ind">
                * {{ error }}
                </div>
            </div>
        </Alert>
        <h2 class="text-lg font-medium mb-4 text-center">Create Account</h2>
        <div class="mb-4">
            <label class="block text-gray-700 font-medium mb-2" for="name">
            Name
            </label>
            <input
            v-model="user.name"
            class="border border-gray-400 p-2 w-full"
            type="text"
            id="name"
            required
            />
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 font-medium mb-2" for="email">
            Email
            </label>
            <input
            v-model="user.email"
            class="border border-gray-400 p-2 w-full"
            type="email"
            id="email"
            required
            />
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 font-medium mb-2" for="password">
            Password
            </label>
            <input
            v-model="user.password"
            class="border border-gray-400 p-2 w-full"
            type="password"
            id="password"
            required
            />
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 font-medium mb-2" for="password_confirmation">
            Confirm Password
            </label>
            <input
            v-model="user.password_confirmation"
            class="border border-gray-400 p-2 w-full"
            type="password"
            id="password_confirmation"
            required
            />
        </div>
        <div class="mb-6">
            <button
            :disabled="loading"
            class="bg-blue-500 flex items-center justify-center w-full text-center text-white p-2 rounded-sm hover:bg-blue-600"
            type="submit"
            :class="{
              'cursor-not-allowed': loading,
              'hover:bg-indigo-500': loading,
            }"
            >
                <svg
                v-if="loading"
                class="animate-spin -ml-1 mr-3 h-5 w-5 text-white"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                >
                <circle
                    class="opacity-25"
                    cx="12"
                    cy="12"
                    r="10"
                    stroke="currentColor"
                    stroke-width="4"
                ></circle>
                <path
                    class="opacity-75"
                    fill="currentColor"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                ></path>
                </svg>
                Create account
            </button>
        </div>
        <div class="flex">
            <p class="text-gray-700">Already have an account?</p>
            <router-link :to="{name: 'Login'}"
            class="pl-2 text-blue-600"
            >
                Login
            </router-link>
        </div>
    </form>
   </div>
</div>
</template>

<script setup>
import { ref } from '@vue/reactivity';
import { useRouter } from 'vue-router';
import store from '../../store';
import Alert from '../Alert.vue';

const router = useRouter();

const user = {
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
}

const loading = ref(false);
const errors = ref({});

function submit(ev) {
  ev.preventDefault();
  loading.value = true;
  store
    .dispatch("register", user)
    .then(() => {
      loading.value = false;
      router.push({
        name: "Vaults",
      });
    })
    .catch((error) => {
      loading.value = false;
      if (error.response.status === 422) {
        errors.value = error.response.data.errors;
      }
    });
}
</script>

<style>
</style>
