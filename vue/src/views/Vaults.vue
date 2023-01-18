<template>
    <a href="#" @click.prevent="exportData">Export Data</a>
</template>

<script>
import axiosClient from '../axios';

export default {
    methods: {
        async exportData() {
            try {
                const response = await axiosClient.get('/export');
                const url = window.URL.createObjectURL(new Blob([response.data]));
                const link = document.createElement('a');
                link.href = url;
                link.setAttribute('download', 'data.csv');
                document.body.appendChild(link);
                link.click();
            } catch (error) {
                console.error(error);
            }
        },
    },
};
</script>
<style scoped>

</style>
