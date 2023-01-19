<template>
    <div>
        <h4 v-if="loading">Loading ...</h4>
        <pre>{{ props.vaultItems }}</pre>
        <Pagination
            :paginationLinks="paginationLinks"
            @getForPage="getForPage"
        />
    </div>
</template>

<script setup>
import Pagination from '../Pagination.vue';
const props = defineProps({
    vaultItems: Object,
    paginationLinks: Array,
    loading: Boolean
})
const emit = defineEmits(['getForPage']);

function getForPage(event, link){
    emit('getForPage', event, link)
}
</script>

<style>

</style>
