<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\TicTacToeUpdate;
use Illuminate\Support\Facades\Broadcast;

class TicTacToeController extends Controller
{
    // Start a new game
    public function newGame(Request $request)
    {
        // Reset the game state
        $gameData = [
            'cells' => array_fill(0, 9, null), // Reset the game board
            'currentPlayer' => 'X', // Set the starting player
            'winner' => null, // Reset the winner status
            'moves' => [], // Clear the move history
        ];

        // Broadcast the updated game state
        broadcast(new TicTacToeUpdate($gameData))->toOthers();

        return response()->json(['message' => 'New game started and broadcasted']);
    }

    // Make a move
    public function makeMove(Request $request)
    {
        // Validate the request data (e.g., ensure the move is valid)

        // Extract the move data from the request
        $index = $request->input('index'); // Assuming the index of the cell is sent in the request

        // Retrieve the current game state (or load it from your storage, e.g., a database)
        $gameData = [
            'cells' => [...$request -> cells], // Current state of the game board
            'currentPlayer' => 'X', // Current player
            'winner' => null, // Winner of the game (if any)
            'moves' => [], // Move history
        ];

        // Implement your game logic to update the game state based on the move
        // For example, update the 'cells' array with the player's move

        // Check if the game has been won or if it's a draw and update the 'winner' accordingly

        // Add the move to the 'moves' history

        // Broadcast the updated game state to all connected clients
        broadcast(new TicTacToeUpdate($gameData))->toOthers();

        // Respond with a success message (optional)
        return response()->json(['message' => 'Move made and game state updated']);
    }

    // Broadcast game updates
    public function broadcastUpdate(Request $request)
    {
        $gameData = [
            'cells' => $request->input('cells'),
            'currentPlayer' => $request->input('currentPlayer'),
            'winner' => $request->input('winner'),
            'moves' => $request->input('moves'),
        ];

        broadcast(new TicTacToeUpdate($gameData));

        return response()->json(['message' => 'Game updated and broadcasted']);
    }
}
