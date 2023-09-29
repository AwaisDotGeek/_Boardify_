<template>
    <nav class="bg-black-4 flex justify-between">
        <!-- Logo -->
        <div class="flex justify-center items-center gap-2">
            <div class="w-16 h-16">
                <img src="../assets/logo.png" alt="logo" class="rounded-full">
            </div>
            <h2 class="text-2xl text-gray-200">Boardify</h2>
        </div>

        <div class="flex items-center gap-2">
            <ul class="flex gap-2">
                <li><Link href="/" class="p-2 rounded-xl hover:bg-black-3 text-lg text-gray-200 active:bg-black-5">Home</Link></li>
                <li><Link href="" class="p-2 rounded-xl hover:bg-black-3 text-lg text-gray-200">Products</Link></li>
                <li><Link href="" class="p-2 rounded-xl hover:bg-black-3 text-lg text-gray-200">About</Link></li>
                <li><Link href="" class="p-2 rounded-xl hover:bg-black-3 text-lg text-gray-200">Contact</Link></li>
            </ul>
            <div class="w-12 h-12 cursor-pointer" @click="$emit('toggleUserProfileSlider')">
                <img src="../assets/logo.png" class="w-full h-full rounded-full" alt="">
            </div>
        </div>
    </nav>
</template>

<script setup>
    import { Link } from "@inertiajs/vue3";
</script>

<style scoped>
nav {
    padding: 10px 25px 10px 20px;
}
</style>
