<template>
    <div>
        <div>
            <h1 class="text-3xl text-gray-700">Generator</h1>
            <hr class="mt-4">
        </div>
        <div class="flex items-center justify-center w-full h-16 border border-gray-300 mt-4">
            <p class="">{{password}}</p>
        </div>
        <div>
            <form class="mt-5">
                <div class="md:flex md:space-x-5 w-full">
                    <div class="mb-4">
                        <label class="block text-gray-600 font-medium mb-2" for="length">
                            Length
                        </label>
                        <input
                        v-model="length"
                        @change="onChange"
                        class="border border-gray-300 rounded-sm p-2 w-full"
                        type="number"
                        id="Length"
                        required
                        />
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-600 font-medium mb-2" for="number">
                            Minimum numbers
                        </label>
                        <input
                        v-model="minNum"
                        @change="onChange"
                        class="border border-gray-300 rounded-sm p-2 w-full"
                        type="number"
                        id="number"
                        required
                        :disabled="!numbers"
                        />
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-600 font-medium mb-2" for="spCharacter">
                            Minimum special character
                        </label>
                        <input
                        v-model="minSpecChar"
                        @change="onChange"
                        class="border border-gray-300 rounded-sm p-2 w-full"
                        type="number"
                        id="spCharacter"
                        required
                        :disabled="!specialCharacter"
                        />
                    </div>
                </div>
                <div>
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
                        <label class="block text-gray-600 font-medium" for="capitalLetters">
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
                        <label class="block text-gray-600 font-medium" for="smallLetters">
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
                        <label class="block text-gray-600 font-medium" for="numbers">
                            0-9
                        </label>
                    </div>
                    <div class="mb-1 flex items-center ">
                        <input
                        v-model="specialCharacter"
                        @change="onChange"
                        class="border border-gray-300 rounded-sm  mr-3"
                        type="checkbox"
                        id="specialCharacter"
                        required
                        />
                        <label class="block text-gray-600 font-medium" for="specialCharacter">
                            !@#$%^&*()
                        </label>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { ref } from "@vue/reactivity";
import { onMounted, watchEffect } from "@vue/runtime-core";

const password = ref();
const length = ref(14);
const minNum = ref(3);
const minSpecChar = ref(3);
const capitalLetters = ref(true);
const smallLetters = ref(true);
const specialCharacter = ref(true);
const numbers = ref(true)


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

    //
    if(capitalLetters){
        possiblePassword += upperChars;
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


onMounted(()=>{
    password.value = generatePassword(length.value, minNum.value, minSpecChar.value)
})

function onChange () {
    // return if length is smaller than
    if(lengthForLetters<0){
        return 'You have give right number for special character and numbers'
    }
    password.value = generatePassword(length.value, minNum.value, minSpecChar.value)
}

</script>

<style>

</style>
