<template>
    <div class="mt-5">
        <div class="md:flex md:space-x-5 items-center w-full">
            <div class="mb-4 md:w-[35%] lg:w-[25%]">
                <label class="block text-gray-600 font-medium mb-2" for="numWords">
                    Number of words
                </label>
                <input
                v-model="numWords"
                @change="initPassword"
                class="bg-gray-50 border border-gray-300 py-1 px-5 w-full rounded-md text-gray-600"
                type="number"
                id="numWords"
                max="10"
                min="2"
                required
                />
            </div>
            <div class="mb-4 md:w-[35%] lg:w-[25%]">
                <label class="block text-gray-600 font-medium mb-2" for="wordSeparator">
                    Word Separator
                </label>
                <input
                v-model="wordSeparator"
                @change="initPassword"
                class="bg-gray-50 border border-gray-300 py-1 px-5 w-full rounded-md text-gray-600"
                type="text"
                id="wordSeparator"
                />
            </div>
        </div>
        <div class="mb-4">
            <h2 class="block text-gray-600 font-medium mb-2">
                Options
            </h2>
            <div class="mb-1 flex items-center ">
                <input
                v-model="capitalize"
                @change="initPassword"
                class="border border-gray-300 rounded-sm  mr-3"
                type="checkbox"
                id="capitalLetters"
                required
                />
                <label class="block text-gray-600 font-medium text-sm" for="capitalLetters">
                    Capitalize
                </label>
            </div>
            <div class="mb-1 flex items-center ">
                <input
                v-model="includeNumber"
                @change="initPassword"
                class="border border-gray-300 rounded-sm  mr-3 "
                type="checkbox"
                id="smallLetters"
                required
                />
                <label class="block text-gray-600 font-medium text-sm" for="smallLetters">
                    Include Number
                </label>
            </div>
        </div>

    </div>
</template>

<script setup>
import { ref } from "@vue/reactivity";


const emit = defineEmits(['setPasswordValue']);
const capitalize = ref(true);
const includeNumber = ref(true);
const wordSeparator = ref('-');
const numWords = ref (3);


function initPassword(){
    emit('setPasswordValue', generatePassphrase(capitalize.value, includeNumber.value, wordSeparator.value, numWords.value));
}



function generatePassphrase(capitalize, includeNumber, wordSeparator, numWords) {
  // array of words to use for passphrase
  let words = [ "there", "many", "variations", "passages", "lorem", "ipsum", "available", "majority", "have", "suffered", "alteration", "some", "form", "injected", "humour", "randomised", "words", "which", "dont", "look", "even", "slightly", "believable", "going", "passage", "lorem", "ipsum", "need", "sure", "there", "isnt", "anything", "embarrassing", "hidden", "middle", "text", "lorem", "ipsum", "generators", "internet", "tend", "repeat", "predefined", "chunks", "necessary", "making", "this", "first", "true", "generator", "internet", "uses", "dictionary", "over", "latin", "words", "combined", "with", "handful", "model", "sentence", "structures", "generate", "lorem", "ipsum", "which", "looks", "reasonable", "generated", "lorem", "ipsum", "therefore", "always", "free", "from", "repetition", "injected", "humour", "noncharacteristic", "words" ];

  // randomly select words for passphrase
  let passphrase = "";
  for (let i = 0; i < numWords; i++) {
    let randomIndex = Math.floor(Math.random() * words.length);
    let word = words[randomIndex];
    if (capitalize) {
      word = word.charAt(0).toUpperCase() + word.slice(1);
    }
    passphrase += word;
    if (i < numWords - 1) {
      passphrase += wordSeparator;
    }
  }

  if (includeNumber) {
    // generate a random number between 0 and 99
    let randomNumber = Math.floor(Math.random() * 100);

    // convert text to an array
    let textArray = passphrase.split("-");

    // choose a random word to insert number
    let randomIndex = Math.floor(Math.random() * textArray.length);
    textArray[randomIndex] = textArray[randomIndex] + randomNumber;

    passphrase = textArray.join("-");
  }

  return passphrase;
}

defineExpose({
    initPassword,
})


</script>

<style>

</style>
