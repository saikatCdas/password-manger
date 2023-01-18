<template>
    <div>
        <Navbar/>
        <router-view></router-view>
        <Notification/>
    </div>
</template>

<script setup>
import Navbar from './Navbar.vue';
import Notification from './Notification.vue';
</script>

<style>

</style>
