<template>
    <PageComponent>
        <div class="flex max-sm:flex-col max-sm:space-y-6 sm:space-x-6 mt-4">
            <div class="sm:rounded-md border  border-gray-400 w-full sm:w-52">
                <div class="bg-white rounded-lg">
                    <div class=" bg-gray-100  rounded-md py-2 px-4">
                        <h3 class="text-lg font-medium"> Tools </h3>
                    </div>
                    <hr>
                    <div v-for="(item, ind) in navigation" :key="ind" class="">
                        <div class="py-2 px-4 flex">
                            <router-link :to="item.to" :class="[ route.name === item.to.name ?'text-gray-800 text-lg font-medium': 'text-gray-500', ' hover:underline']"> {{ item.name }} </router-link>
                        </div>
                        <hr>
                    </div>
                </div>
            </div>
            <div class="w-full">
                <router-view></router-view>
            </div>
        </div>
    </PageComponent>
</template>

<script setup>
import { useRoute } from 'vue-router';
import PageComponent from '../components/PageComponent.vue';

const route = useRoute();
const navigation = [
    {name: 'Generator', to:{name: 'PasswordGenerator'}},
    {name: 'Imports', to:{name: 'Imports'}},
    {name: 'Exports', to:{name: 'Exports'}},
]
</script>

<style>

</style>
