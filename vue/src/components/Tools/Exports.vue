<template>
    <a href="#" @click.prevent="exportData" class="px-3 py-2 bg-emerald-500 hover:bg-emerald-600 rounded-sm text-gray-100 hover:text-white">Export Data</a>
</template>

<script setup>
import { computed } from "@vue/runtime-core";
import axiosClient from "../../axios";
import store from "../../store";

function exportData() {
    store.dispatch('exportVault')
        .then(()=>{
            const url = window.URL.createObjectURL(new Blob([store.state.exportUrl]));
            const link = document.createElement('a');
            link.href = url;
            link.setAttribute('download', 'data.csv');
            document.body.appendChild(link);
            link.click();
        }).catch((error)=>{
            console.log(error);
        })
    }

</script>
