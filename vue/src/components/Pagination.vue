<template>
    <div v-if="paginationLinks.length > 3" class="flex justify-center mt-5 pb-3">
      <nav
      class="relative z-0 inline-flex justify-center rounded-md shadow-sm -space-x-px"
      aria-label="Pagination"
      >
      <a
      v-for="(link, i) of props.paginationLinks"
      :key="i"
      :disabled="!link.url"
          href="#"
          @click="getForPage($event, link)"
          aria-current="page"
          class="relative inline-flex items-center px-4 py-2 border text-sm font-medium whitespace-nowrap"
          :class="[
            link.active
              ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600'
              : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50',
            i === 0 ? 'rounded-l-md bg-gray-100 text-gray-700' : '',
            i === paginationLinks.length - 1 ? 'rounded-r-md' : '',
          ]"
          v-html="link.label"
        >
        </a>
      </nav>
    </div>
  </template>

  <script setup>


  const props = defineProps(['paginationLinks']);
  const emit = defineEmits(['getForPage']);



  function getForPage(event, link){
    emit('getForPage', event, link)
  }

  </script>

  <style>

  </style>
