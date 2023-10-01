<template>
    <main class="overflow-x-hidden">
        <ul class="p-2">
            <li v-for="message in messages">
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-8 h-8">
                        <img src="../../../assets/logo.png" alt="" class="w-full h-full object-cover rounded-full">
<!--                        <strong class="text-gray-400">-->
<!--                            {{ message.user_id }}-->
<!--                        </strong>-->
                    </div>
                    <p class="text-gray-300 bg-gradient-to-br from-black-2 to-black-4 max-w-max p-2 rounded-lg shadow-sm shadow-black-5">
                        {{ message.message }}
                    </p>
                </div>
            </li>
        </ul>
    </main>
</template>

<script>
    export default {
        props: ['messages'],
    }
</script>
