<template>
    <main>
        <div class="flex justify-center items-center bg-gradient-to-tl from-black-2 to-black-4 w-full p-2">
            <input
                id="btn-input"
                type="text"
                name="message"
                class="w-width_90 p-2 rounded-lg mr-2 outline-none"
                placeholder="Type your message here..."
                v-model="newMessage"
                @keyup.enter="sendMessage"
                @keyup="sendTypingEvent">

            <button id="btn-chat" @click="sendMessage">
                <i class="fa-solid fa-paper-plane text-white text-xl ml-2"></i>
            </button>
        </div>
    </main>
</template>

<script>
export default {
    props: ['user'],

    data() {
        return {
            newMessage: ''
        }
    },

    methods: {
        sendTypingEvent() {
            console.log('abc...');
            Echo.join('chat')
                .whisper('typing', this.user);
        },

        sendMessage() {
            this.$emit('messageSent', {
                message: this.newMessage
            });

            this.newMessage = ''
        }
    }
}
</script>
