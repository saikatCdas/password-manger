<template>
    <div>
        <div>
            <h1 class="text-3xl text-gray-700">Generator</h1>
            <hr class="mt-4">
        </div>
        <div class="flex items-center w-full h-16 border border-gray-300 mt-4 ">
            <p class="text-center w-full">{{password}}</p>
            <div @mouseover="showChild = true" @mouseleave="showChild = false" class="relative">
                <svg @click.prevent="copyText" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-gray-400 cursor-pointer top-0">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 01-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 011.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 00-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 01-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 00-3.375-3.375h-1.5a1.125 1.125 0 01-1.125-1.125v-1.5a3.375 3.375 0 00-3.375-3.375H9.75" />
                </svg>
                <p v-show="showChild" class="absolute font-semibold -top-3 right-6 text-blue-500 rounded-md">copy</p>
            </div>
        </div>
        <div class="mt-4 text-gray-600">
            <h2 class="font-semibold ">What would you like to generate??</h2>
            <div class="mt-1 flex items-center">
                <input type="radio" @change="generatePassword" v-model="selectedOption" :value="'password'" name="password" :class="'form-radio w-3 h-3 rounded-full'">
                <label :class="'ml-2 text-[15px]'" for="password">Password</label>
                <input type="radio" @change="generatePassword" v-model="selectedOption" :value="'passphrase'" name="passphrase" :class="'form-radio w-3 h-3 rounded-full ml-4'">
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
import { onMounted, watchEffect} from "@vue/runtime-core";
import Password from "./Password.vue";
import Passphrase from "./Passphrase.vue";
import store from "../../store";

const password = ref();
const passwordRef = ref(null);
const passphraseRef = ref(null);
const selectedOption = ref('password');
const showChild = ref(false);

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
        store.commit("notify", {
            type: "failed",
            message: "Something is wrong !! ",
        });
    });
}

</script>

<style>

</style>
