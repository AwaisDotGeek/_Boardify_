<template>
    <div class="" v-if="winner">
        <div class="absolute top-20 left-8 w-72 h-48 flex justify-center items-center text-gray-200 font-bold text-4xl shadow-2xl shadow-black-5 rounded-xl bg-gradient-to-b from-black-1 to-black-3">
            <span :class="winner === 'X' ? 'text-shadow-red':'text-shadow-green'">{{ winner }}</span> &nbsp;Won!!!
        </div>
    </div>
</template>

<script>
export default {
    props: ["winner"],
};
</script>

<style scoped>
.text-shadow-red {
    text-shadow: 2px 2px 5px yellowgreen,
    -2px -2px 5px red,
    0 2px 8px goldenrod,
    2px 0 5px darkorange;
}

.text-shadow-green {
    text-shadow: 2px 2px 5px darkblue,
    -2px -2px 5px deepskyblue,
    0 2px 5px cyan,
    2px 0 5px royalblue;
}
</style>
