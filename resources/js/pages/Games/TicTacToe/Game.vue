<template>
    <main class="p-5 pr-6 max-w-4xl mx-auto">
        <h1 class="text-3xl font-semibold text-gray-200 text-left py-2">Tic-Tac-Toe</h1>

        <div id="game" class="flex items-center gap-2 shadow-md shadow-black-4 bg-gradient-to-tl from-black-2 via-black-1 to-black-4 p-5 rounded-xl">
            <div class="flex flex-col justify-center items-center gap-2  w-full p-2 rounded-lg">
                <GameStatus
                    :currentPlayer="currentPlayer"
                    :winner="winner"
                />
                <Board
                    :cells="cells"
                    :winner="winner"
                    @cell-clicked="handleCellClick"
                />
                <RestartButton @handleRestartClick="restartGame"></RestartButton>
            </div>
            <History class="w-1/2" :moves="moves" />
        </div>
    </main>
</template>

<script>
import axios from "axios";
import Board from "./components/Board.vue";
import GameStatus from "./components/GameStatus.vue";
import RestartButton from "./components/RestartButton.vue";
import History from "./components/History.vue";

export default {
    data() {
        return {
            cells: Array(9).fill(null),
            currentPlayer: "X",
            winner: null,
            moves: [], // Initialize with your game moves
            gameStarted: false,
        };
    },
    async mounted() {
        window.Echo.connector.pusher.connection.bind('connected', () => {
            console.log('Connected to Pusher');
        });
        if (!this.gameStarted) {
            const response = await axios.post('/tictactoe/new');
            console.log(response.data.message);
        }
    },
    methods: {
        handleGameEvent(event) {
            // Extract data from the event
            alert("Here!");
            const gameData = event.gameData;

            // Update the game board or UI based on the received game data
            this.cells = gameData.cells; // Update your game board cells
            this.currentPlayer = gameData.currentPlayer; // Update the current player
            this.winner = gameData.winner; // Update the winner status
            this.moves = gameData.moves; // Update game moves

            // You can also add any additional logic you need here
            console.log("Hi");
        },

        handleCellClick(index) {
            // Add logic to handle cell clicks
            if (!this.cells[index] && !this.winner) {
                switch (this.currentPlayer) {
                    case 'X':
                        this.cells[index] = 'X';
                        break;
                    case 'O':
                        this.cells[index] = 'O';
                        break;
                }
                this.moves.push(this.currentPlayer + ' on ' + index);

                let isGameWon = this.isGameWon();
                this.winner = isGameWon ? this.currentPlayer:null;

                // post the move
                this.postTheMove(index);

                window.Echo.channel("tictactoe")
                    .listen("tictactoe.event", (event) => {
                        // Handle the incoming event data
                        console.log("Hi");
                        this.handleGameEvent(event);
                    });

                this.currentPlayer = this.currentPlayer === 'X' ? 'O':'X';
            }
        },

        async postTheMove(index) {
            const response = await axios.post('/tictactoe/move', {
                index: index,
                cells: this.cells,
                currentPlayer: this.currentPlayer,
                winner: this.winner,
                moves: this.moves,
            });

            console.log(response.data.message);
        },

        restartGame() {
            if (confirm('Are you sure you want to start a new game?')) {
                this.cells = Array(9).fill(null);
                this.currentPlayer = 'X';
                this.winner = null;
                this.moves = [];
            }
        },

        isGameWon() {
            let winingCombos = [
                [0, 1, 2],
                [3, 4, 5],
                [6, 7, 8],
                [0, 3, 6],
                [1, 4, 7],
                [2, 5, 8],
                [0, 4, 8],
                [2, 4, 6],
            ];

            for (const combo of winingCombos){
                const [ a, b, c ] = combo;
                if (!(this.cells[a] === null || this.cells[b] === null || this.cells[c] === null)){
                    if (this.cells[a] === this.cells[b] && this.cells[a] === this.cells[c]) {
                        return true;
                    }
                }
            }

            return false;
        },

    },
    components: {
        Board,
        GameStatus,
        RestartButton,
        History,
    },
};
</script>
