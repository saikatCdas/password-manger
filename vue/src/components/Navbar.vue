<template>
<nav class="flex items-center justify-center w-full px-4 py-3 bg-[#175DDC]">
    <div class="max-w-5xl max-w-sm:flex-col sm:flex items-center w-full">
        <div class="flex items-center mr-5 max-sm:mb-3">
            <a href="/">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 text-white">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                </svg>
            </a>
        </div>
        <div v-for="(item, ind) in navigation" :key="ind" class="max-sm:py-3  sm:p-3" >
            <router-link :to="item.to" class=" font-semibold " :class="route.name === item.to.name ? 'text-white' : 'text-gray-300'" >{{ item.name }}</router-link>
        </div>
        <div class="ml-auto">
            <button type="button" class="text-white" @click="logout">Logout</button>
        </div>
    </div>
</nav>
</template>

<script setup>
import { useRoute, useRouter } from "vue-router"
import store from "../store";


const route = useRoute();
const router = useRouter();
const navigation = [
{name: 'Vaults', to:{name: 'Vaults'}},
{name: 'Tools', to:{name: 'PasswordGenerator'}}
]

const logout = (ev) => {
    ev.preventDefault();
    store.dispatch('logout')
        .then(()=>{
            router.push({
                name: 'Login'
            })
        });
}
</script>
