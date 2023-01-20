<template>
    <div class="mt-5">
        <div class="lg:flex items-center lg:space-x-5 w-full">
            <div class="mb-4 md:w-[40%] lg:w-[25%]">
                <label class="block text-gray-600 font-medium mb-2" for="length">
                    Length
                </label>
                <input
                v-model="length"
                @change="onChange"
                class="bg-gray-50 border border-gray-300 py-1 px-5 w-full rounded-md text-gray-600"
                type="number"
                id="Length"
                max="80"
                min="6"
                required
                />
            </div>
            <div class="mb-4 md:w-[40%] lg:w-[25%]">
                <label class="block text-gray-600 font-medium mb-2" for="number">
                    Minimum numbers
                </label>
                <input
                v-model="minNum"
                @change="onChange"
                class="bg-gray-50 border border-gray-300 py-1 px-5 w-full rounded-md text-gray-600"
                type="number"
                id="number"
                max="80"
                min="0"
                required
                :disabled="!numbers"
                />
            </div>
            <div class="mb-4 md:w-[40%] lg:w-[25%]">
                <label class="block text-gray-600 font-medium mb-2" for="spCharacter">
                    Minimum special character
                </label>
                <input
                v-model="minSpecChar"
                @change="onChange"
                class="bg-gray-50 border border-gray-300 py-1 px-5 w-full rounded-md text-gray-600"
                type="number"
                id="spCharacter"
                max="80"
                min="0"
                required
                :disabled="!specialCharacter"
                />
            </div>
        </div>
        <div class="mb-4">
            <h2 class="block text-gray-600 font-medium mb-2">
                Options
            </h2>
            <div class="mb-1 flex items-center ">
                <input
                v-model="capitalLetters"
                @change="onChange"
                class="border border-gray-300 rounded-sm  mr-3"
                type="checkbox"
                id="capitalLetters"
                required
                />
                <label class="block text-gray-600 font-medium text-sm" for="capitalLetters">
                    A-Z
                </label>
            </div>
            <div class="mb-1 flex items-center ">
                <input
                v-model="smallLetters"
                @change="onChange"
                class="border border-gray-300 rounded-sm  mr-3"
                type="checkbox"
                id="smallLetters"
                required
                />
                <label class="block text-gray-600 font-medium text-sm" for="smallLetters">
                    a-z
                </label>
            </div>
            <div class="mb-1 flex items-center ">
                <input
                v-model="numbers"
                @change="onChange"
                class="border border-gray-300 rounded-sm  mr-3"
                type="checkbox"
                id="numbers"
                required
                />
                <label class="block text-gray-600 font-medium text-sm" for="numbers">
                    0-9
                </label>
            </div>
            <div class="mb-1 flex items-center ">
                <input
                v-model="specialCharacter"
                @change="onChange"
                class="border border-gray-300 rounded-sm  mr-3 "
                type="checkbox"
                id="specialCharacter"
                required
                />
                <label class="block text-gray-600 font-medium text-sm" for="specialCharacter">
                    !@#$%^&*()
                </label>
            </div>
        </div>

    </div>
</template>

<script setup>
import { ref } from "@vue/reactivity";
import { watchEffect } from "@vue/runtime-core";
import store from "../../store";


const emit = defineEmits(['setPasswordValue']);
const password = ref();
const length = ref(14);
const minNum = ref(3);
const minSpecChar = ref(3);
const capitalLetters = ref(true);
const smallLetters = ref(true);
const numbers = ref(true)
const specialCharacter = ref(true);


watchEffect(()=>{
    // numbers are not chosen
    if(!numbers.value){
        minNum.value = 0;
    }

    // Special Character are not chosen
    if(!specialCharacter.value){
        minSpecChar.value = 0;
    }
})

// Generate Password
function generatePassword(length, minNumericChars, minSpecialChars, capitalLetters, smallLetters) {
    // define the character sets to use
    var specialChars = "!#$%&*+-=?@^_";
    var upperChars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    var lowerChars = "abcdefghijklmnopqrstuvwxyz";
    var numericChars = "0123456789";

    var possiblePassword = "";

    // initialize the password variable
    var password = "";

    var lengthForLetters = length - (minNumericChars + minSpecialChars);

    // if capitalLetters
    if(capitalLetters){
        possiblePassword += upperChars;
    }

    // if smallLetters
    if(smallLetters){
        possiblePassword += lowerChars;
    }

    // add the Lower and||or uppercase characters to the password
    for (var i = 0; i < lengthForLetters; i++) {
        password += possiblePassword.charAt(Math.floor(Math.random() * possiblePassword.length));
    }

    // add the specified number of special characters to the password
    for (var i = 0; i < minSpecialChars; i++) {
        password += specialChars.charAt(Math.floor(Math.random() * specialChars.length));
    }

    // add the specified number of numeric characters to the password
    for (var i = 0; i < minNumericChars; i++) {
        password += numericChars.charAt(Math.floor(Math.random() * numericChars.length));
    }

    // shuffle the password
    password = shuffleString(password);

    // truncate the password if it's too long
    if (password.length > length) {
        password = password.substring(0, length);
    }

    // return the generated password
    return password;
}

// shuffle the characters in a string
function shuffleString(str) {
    var arr = str.split("");
    var shuffledArr = arr.map((a) => [Math.random(), a]).sort((a, b) => a[0] - b[0]).map((a) => a[1]);
    return shuffledArr.join("");
}



// Generate Password on value change
function onChange (ev) {
    ev.preventDefault();
    // return if length is smaller than
    if((length.value -(minNum.value + minSpecChar.value))< 0){
        minNum.value = 0;
        minSpecChar.value = 0;
        store.commit("notify", {
            type: "failed",
            message: "You have given more number of special character and numbers than your password length.",
        });
        return ;
    }
    emit('setPasswordValue', generatePassword(length.value, minNum.value, minSpecChar.value, capitalLetters.value, smallLetters.value));
}

function initPassword(){
    emit('setPasswordValue', generatePassword(length.value, minNum.value, minSpecChar.value, capitalLetters.value, smallLetters.value));
}

defineExpose({
    initPassword,
})
</script>

<style>

</style>
