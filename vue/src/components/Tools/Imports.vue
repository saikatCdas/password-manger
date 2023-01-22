<template>
<div>
    <div>
        <h1 class="text-3xl text-gray-700">Import Data </h1>
        <hr class="mt-4">
    </div>
    <form @submit.prevent="submitFile" class="mt-6 space-y-6 py-2 px-3">
        <div class="flex flex-col  text-gray-600 space-y-2">
            <label class="  text-lg ">Choose a csv file</label>
            <input @click="showUploadButton = true" type="file" ref="fileInput" required/>
        </div>
        <button v-show="showUploadButton" type="submit" class="py-2 px-3 text-lg rounded-md bg-emerald-500 hover:bg-emerald-600 text-white">Upload</button>
    </form>
</div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import store from '../../store';

const fileInput = ref(null)
const router = useRouter();

const showUploadButton = ref(false);
function submitFile (){
    showUploadButton.value = false;
// Get the file from the input
    const file = fileInput.value.files[0];

    // Checking Extension
    let extensionNotMatch = checkingFileExtension(file.name)
    if(extensionNotMatch){
        store.commit("notify", {
                type: "failed",
                message: "Please select a csv file !! ",
            });
        return;
    }
    // Create a new FormData object and append the file
    const formData = new FormData()
    formData.append('csv_file', file)
    store.dispatch('import', formData)
        .then(() => {
            store.commit("notify", {
                type: "success",
                message: "Fill imported successfully !!",
            });
            router.push({
                name: 'Vaults'
            })
        })
        .catch((er)=>{
            console.log(er);
            store.commit("notify", {
                type: "failed",
                message: "Something is wrong !! ",
            });
        })
}


function checkingFileExtension(file){
    const fileExtension = file.split('.');
    if(fileExtension[fileExtension.length - 1].toLowerCase() !== 'csv'){
        return true;
    }
}
</script>
