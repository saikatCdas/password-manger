<template>
    <PageComponent>
        <div class="flex max-sm:flex-col max-sm:space-y-6 sm:space-x-6 mt-4 min-h-screen h-full max-md:px-4">
            <div class="">
                <div class="bg-white rounded-lg sm:rounded-md border  border-gray-400 w-full sm:w-52">
                    <div class=" bg-gray-100  rounded-md py-2 px-4">
                        <h3 class="text-lg font-medium"> Tools </h3>
                    </div>
                    <hr>
                    <div v-for="(item, ind) in navigation" :key="ind" class="">
                        <div class="py-2 px-4 flex">
                            <button @click.prevent="getPage(item)" :class="[ page === item.toLowerCase() ? 'text-blue-700 text-lg font-medium' : 'text-gray-500', ' hover:text-blue-400']"> {{ item }} </button>
                        </div>
                        <hr>
                    </div>
                </div>
            </div>
            <div class="w-full">
                <Generator v-if="page === 'generator'"/>
                <Imports v-if="page === 'import data'"/>
                <Exports v-if="page === 'export vault'"/>
            </div>
        </div>
    </PageComponent>
</template>

<script setup>
import { ref } from '@vue/reactivity';
import { onMounted } from '@vue/runtime-core';
import { useRoute, useRouter } from 'vue-router';
import PageComponent from '../components/PageComponent.vue';
import Exports from '../components/Tools/Exports.vue';
import Generator from '../components/Tools/Generator.vue';
import Imports from '../components/Tools/Imports.vue';

const page = ref('generator');
const navigation = ['Generator', 'Import Data', 'Export Vault'];
const router = useRouter();
const route = useRoute();

//
onMounted(()=>{
    if(route.query.page){
        page.value = route.query.page
    }
})

// navigating throw page
function getPage(pageName){
    page.value = pageName.toLowerCase();
    console.log(page.value);
    router.push({
        query:{page: pageName.toLowerCase()}
    })
};

</script>

<style>

</style>
