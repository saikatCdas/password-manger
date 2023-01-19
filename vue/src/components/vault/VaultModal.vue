<template>
    <div v-if="props.formModalOpen" class="fixed inset-0 z-50 overflow-y-auto" >
        <div class="flex items-end justify-center min-h-screen px-4 text-center md:items-center sm:block sm:p-0">
            <div
                x-transition:enter="transition ease-out duration-300 transform"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="transition ease-in duration-200 transform"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="inline-block w-full max-w-xl p-8 my-20 overflow-hidden text-left transition-all transform bg-white rounded-lg shadow-xl 2xl:max-w-2xl"
            >
                <div class="flex items-center justify-between space-x-4">
                    <h1 class="text-xl font-medium text-gray-800 ">Add item</h1>

                    <button @click="formModalClose" class="text-gray-600 focus:outline-none hover:text-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </button>
                </div>

                <form class="mt-5" @submit="CreateVault">
                    <div class="mb-4">
                        <label for="category" class="block text-md font-semibold text-gray-900 capitalize">What type of item is this?</label>
                        <select v-model="vaultData.category" id="category" name="category" class="block w-full px-3 py-2 mt-2 text-gray-600 placeholder-gray-400 bg-white border border-gray-300 rounded-md focus:border-indigo-400 focus:outline-none focus:ring focus:ring-indigo-300 focus:ring-opacity-40">
                            <option value="Login" >Login</option>
                            <option value="Card" >Card</option>
                            <option value="Secure note" >Secure note</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="folder" class="block text-md font-semibold text-gray-900 capitalize">Folder</label>
                        <select v-model="vaultData.folder" id="folder" name="folder" class="block w-full px-3 py-2 mt-2 text-gray-600 placeholder-gray-400 bg-white border border-gray-300 rounded-md focus:border-indigo-400 focus:outline-none focus:ring focus:ring-indigo-300 focus:ring-opacity-40">
                            <option v-for="(folder, ind) in folders" :key="ind" :value="folder.name">{{ folder.name }}</option>
                            <option value="">Chose a folder</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="name" class="block text-md font-semibold text-gray-900 capitalize">Name</label>
                        <input v-model="vaultData.name" type="text" id="name" name="name" class="block w-full px-3 py-2 mt-2 text-gray-00 placeholder-gray-400 bg-white border border-gray-300 rounded-md focus:border-indigo-400 focus:outline-none focus:ring focus:ring-indigo-300 focus:ring-opacity-40" required/>
                    </div>

                    <div class="mb-4">
                        <label for="user_name" class="block text-md font-semibold text-gray-900 capitalize">User Name</label>
                        <input v-model="vaultData.user_name" type="text" id="user_name" name="user_name" class="block w-full px-3 py-2 mt-2 text-gray-600 placeholder-gray-400 bg-white border border-gray-300 rounded-md focus:border-indigo-400 focus:outline-none focus:ring focus:ring-indigo-300 focus:ring-opacity-40" />
                    </div>
                    <div class="mb-4">
                        <label for="email" class="block text-md font-semibold text-gray-900 capitalize">Email</label>
                        <input v-model="vaultData.email" type="text" id="email" name="email" class="block w-full px-3 py-2 mt-2 text-gray-600 placeholder-gray-400 bg-white border border-gray-300 rounded-md focus:border-indigo-400 focus:outline-none focus:ring focus:ring-indigo-300 focus:ring-opacity-40"/>
                    </div>
                    <div class="mb-4">
                        <label for="password" class="block text-md font-semibold text-gray-900 capitalize">Password</label>
                        <input v-model="vaultData.password" type="text" id="password" name="password" class="block w-full px-3 py-2 mt-2 text-gray-600 placeholder-gray-400 bg-white border border-gray-300 rounded-md focus:border-indigo-400 focus:outline-none focus:ring focus:ring-indigo-300 focus:ring-opacity-40"/>
                    </div>
                    <div class="mb-4">
                        <label for="url" class="block text-md font-semibold text-gray-900 capitalize">URL</label>
                        <input v-model="vaultData.url" type="text" id="url" name="url" class="block w-full px-3 py-2 mt-2 text-gray-600 placeholder-gray-400 bg-white border border-gray-300 rounded-md focus:border-indigo-400 focus:outline-none focus:ring focus:ring-indigo-300 focus:ring-opacity-40" placeholder="ex. https://google.com"/>
                    </div>
                    <div class="mb-4">
                        <label for="notes" class="block text-md font-semibold text-gray-900 capitalize">Notes</label>
                        <textarea v-model="vaultData.notes" rows="4" type="text" id="notes" name="notes" class="block w-full px-3 py-2 mt-2 text-gray-600 placeholder-gray-400 bg-white border border-gray-300 rounded-md focus:border-indigo-400 focus:outline-none focus:ring focus:ring-indigo-300 focus:ring-opacity-40"/>
                    </div>

                    <div class="flex justify-end mt-6">
                        <button type="submit" class="px-3 py-2 text-sm tracking-wide text-white capitalize transition-colors duration-200 transform bg-indigo-500 rounded-md dark:bg-indigo-600 dark:hover:bg-indigo-700 dark:focus:bg-indigo-700 hover:bg-indigo-600 focus:outline-none focus:bg-indigo-500 focus:ring focus:ring-indigo-300 focus:ring-opacity-50">
                            Add item
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from "@vue/reactivity";
import { computed } from "@vue/runtime-core";
import { useRouter } from "vue-router";
import store from "../../store";


const router = useRouter();
const props = defineProps(['formModalOpen']);
const emit = defineEmits(['formModalClose']);
const folders = computed(()=>store.state.folders)
const vaultData = ref({
    'name': '',
    'category' : 'Login',
    'email': '',
    'folder': '',
    'user_name': '',
    'password': '',
    'url': '',
    'notes': ''
});

function formModalClose (){
    emit('formModalClose');
}

function CreateVault(ev){
    ev.preventDefault();
    store.dispatch('CreateVault', vaultData.value)
        .then(()=>{
            router.push({
                name: 'Vaults',
                query: { type : 'all'}
            })
            store.commit("notify", {
                type: "success",
                message: "Item Added Successfully.",
            });
            vaultData.value = {'category' : 'Login', 'folder': ''}
            formModalClose();
        }).catch((err)=>{
            store.commit("notify", {
                type: "failed",
                message: "Something went wrong.",
            });
        })
}
</script>

<style>

</style>
