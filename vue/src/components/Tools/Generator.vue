<template>
    <div>
        <div>
            <h1 class="text-3xl text-gray-700">Generator</h1>
            <hr class="mt-4">
        </div>
        <div class="flex items-center justify-center w-full h-16 border border-gray-300 mt-4">
            <p class="">{{password}}</p>
        </div>
        <div class="mt-4 text-gray-600">
            <h2 class="font-semibold ">What would you like to generate??</h2>
            <div class="mt-1 flex items-center">
                <input type="radio" v-model="selectedOption" :value="'password'" name="password" :class="'form-radio w-3 h-3 rounded-full'">
                <label :class="'ml-2 text-[15px]'" for="password">Password</label>
                <input type="radio" v-model="selectedOption" :value="'passphrase'" name="passphrase" :class="'form-radio w-3 h-3 rounded-full ml-4'">
                <label :class="'ml-2 text-[15px]'" for="passphrase">Passphrase</label>
            </div>
        </div>
        <div>
            <Password v-if="selectedOption === 'password'" class="w-full"
                @setPasswordValue="setPasswordValue"
                ref="passwordRef"
            />
            <Passphrase v-if="selectedOption === 'passphrase'"
                @setPasswordValue="setPasswordValue"
                ref="passphraseRef"
            />
            <div class="flex max-sm:flex-col max-sm:space-y-1 sm:space-x-2">
                <button @click.prevent="generatePassword" type=" button" class="py-1 px-3 text-lg rounded-md border bg-emerald-500 hover:bg-emerald-600 text-white">Regenerate Password</button>
                <button type="button" @click="copyText" class="text-gray-600 text-lg border border-gray-400 px-3 py-1 rounded-md ">Copy password</button>
        </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from "@vue/reactivity";
import { onMounted} from "@vue/runtime-core";
import Password from "./Password.vue";
import Passphrase from "./Passphrase.vue";
import store from "../../store";

const password = ref();
const passwordRef = ref(null);
const passphraseRef = ref(null);
const selectedOption = ref('password');

// generate password
function generatePassword(){
    if(selectedOption.value === 'password'){
        passwordRef.value.initPassword();
    } else if (selectedOption.value === 'passphrase'){
        passphraseRef.value.initPassword();
    } else{
        store.commit("notify", {
            type: "failed",
            message: "Something is wrong !! ",
        });
    }
}

// set password value
function setPasswordValue(pd){
    password.value = pd;
}

onMounted(()=>{
    passwordRef.value.initPassword();
})



// Copy The generated password
function copyText() {
    navigator.clipboard.writeText(password.value)
    .then(() => {
        store.commit("notify", {
            type: "success",
            message: "Password copied successfully ",
        });
    })
    .catch(err => {
        console.error('Failed to password: ', err);
    });
}

</script>

<style>

</style>
