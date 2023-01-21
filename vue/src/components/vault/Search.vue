<template>
    <form @submit.prevent="search">
        <div>
          <label for="search" class="sr-only">Email address</label>
          <input v-model="searchInp" id="search" name="search" type="text" class="relative block w-full appearance-none rounded-md border border-gray-300 px-3 py-2 text-gray-900 placeholder-gray-500 focus:z-10 focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm" placeholder="Search">
        </div>
    </form>
</template>

<script setup>
import { ref } from "@vue/reactivity";
import { watchEffect } from "@vue/runtime-core";

const searchInp = ref('');
const emit = defineEmits(["search"]);

function search(){
    emit('search', searchInp.value);
}

watchEffect((onInvalidate)=>{
    if(searchInp.value.length > 0){
        const onChange = setTimeout(() => {
            search();
        }, 600)

        onInvalidate(()=>{
            clearInterval(onChange);
        })
    }
})
</script>

<style>

</style>
