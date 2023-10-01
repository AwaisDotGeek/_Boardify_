<template>
    <main class="h-screen flex flex-col justify-between  bg-gradient-to-tr from-black-2 to-black-4">
        <div>
            <h1 class="p-2 bg-black-4 text-2xl font-semibold text-gray-200 text-left">Discussions</h1>
            <ChatMessages :messages="messages"></ChatMessages>
        </div>
        <ChatForm @messageSent="addMessage"></ChatForm>
    </main>
</template>

<script>
    import axios from "axios";
    import ChatForm from "./components/ChatForm.vue";
    import ChatMessages from "./components/ChatMessages.vue"

    export default {
        components: {
            ChatForm,
            ChatMessages,
        },

        data() {
            return {
                messages: [],
                users: [],
            }
        },

        created() {
            this.fetchMessages();

            console.log("inside Created!");
            Echo.join('chat')
                .here(users => {
                    this.users = users;
                })
                .joining(user => {
                    this.users.push(user);
                })
                .leaving(user => {
                    this.users = this.users.filter(u => u.id !== user.id);
                })
                .listenForWhisper('typing', ({id, name}) => {
                    this.users.forEach((user, index) => {
                        if (user.id === id) {
                            user.typing = true;
                            this.$set(this.users, index, user);
                        }
                    });

                    console.log("listening for whisper!");
                })
                .listen('MessageSent', (event) => {
                    this.messages.push({
                        message: event.message.message,
                        user: event.user
                    });

                    this.users.forEach((user, index) => {
                        if (user.id === event.user.id) {
                            user.typing = false;
                            this.$set(this.users, index, user);
                        }
                    });
                });
        },

        methods: {
            fetchMessages(){
                axios.get('/messages').then(response => {
                    this.messages = response.data;
                });
            },

            addMessage(message) {
                this.messages.push("Hello World!");
                this.messages.push(message.message);

                this.$inertia.post('/messages', {
                    message: message.message,
                });
            }
        },
    }
</script>
