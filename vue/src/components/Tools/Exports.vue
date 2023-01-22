<template>
    <div>
        <h1 class="text-3xl text-gray-700">Export Vault</h1>
        <hr class="mt-4">
    </div>
    <div class="mt-4">
        <a href="#" @click.prevent="exportData" class="py-2 px-3 text-lg rounded-md bg-emerald-500 hover:bg-emerald-600 text-white">Export Data</a>
    </div>
</template>

<script setup>
import { computed } from "@vue/runtime-core";
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
            store.commit("notify", {
                type: "failed",
                message: "Something is wrong !! ",
            });
        })
    }

</script>
