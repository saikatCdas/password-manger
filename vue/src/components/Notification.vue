<template>
    <div
        v-if="notification.show"
        class=" z-50 flex items-center fixed w-[300px] right-4 top-20 py-2 px-4 text-white animate-fade-in-down"
        :class="[notification.type === 'success' ? 'bg-emerald-500' : 'bg-red-500']"
    >
        <div class="w-6 h-6 mr-2">
            <svg @click="notification.show = false" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" :class="[notification.type === 'success'? 'hover:bg-emerald-600' : 'hover:bg-red-600' ,'w-6 h-6 cursor-pointer rounded-full']">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </div>
        <p>{{ notification.message }}</p>
    </div>
</template>

<script setup>
import { computed } from "vue";
import { useStore } from "vuex";

const store = useStore();

const notification = computed(() => store.state.notification);
</script>

<style></style>
