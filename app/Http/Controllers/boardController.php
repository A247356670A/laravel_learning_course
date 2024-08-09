<?php

namespace App\Http\Controllers;

use App\Models\Board;
use Illuminate\Http\Request;

class boardController extends Controller
{

    public function retreiveBoards($board_id)
    {
        $token = config("services.monday.token");
        // Log::info($token);
        $apiUrl = "https://api.monday.com/v2";
        $headers = ["Content-Type: application/json", "Authorization: " . $token];
        // $board_id = ?;
        $query = "query {
            boards (ids: $board_id){
              items_page {
                cursor
                items {
                  id
                  name
                }
              }
            }
          }";

        $data = @file_get_contents($apiUrl, false, stream_context_create([
            "http" => [
            "method" => "POST",
            "header" => $headers,
            "content" => json_encode(["query" => $query]),
        ]
        ]));

        $responseContent = json_decode($data, true);
        echo json_encode($responseContent);
    }

    public function createBoard(Request $request)
    {
        $incomingFileds = $request->validate([
            "board_name"=> "required",
        ]);
        $incomingFileds['board_name'] = strip_tags($incomingFileds['board_name']);

        // $newBoard = Board::create($incomingFileds);

        $token = config("services.monday.token");
        $apiUrl = "https://api.monday.com/v2";
        $headers = ["Content-Type: application/json", "Authorization: " . $token];

        $query = "mutation {
            create_board (board_name: \"{$incomingFileds['board_name']}\", board_kind: public) {
              id
            }
          }";

        $data = @file_get_contents($apiUrl, false, stream_context_create([
            "http" => [
            "method" => "POST",
            "header" => $headers,
            "content" => json_encode(["query" => $query]),
            ]
        ]));
        $responseContent = json_decode($data, true);
        if (isset($responseContent["data"]['create_board']['id'])) {
            Board::create($incomingFileds);
        }

        echo json_encode($responseContent);
    }

    public function duplicateBoard(Request $request)
    {
        $apiUrl = "https://api.monday.com/v2";
        $token = config("services.monday.token");
        $board_id = $request->board_id;

        $query = "mutation { duplicate_board(board_id: $board_id, duplicate_type: duplicate_board_with_structure) { board { id }}}";

        $headers = [
            'Content-Type: application/json',
            'Authorization: ' . $token
        ];

        $data = @file_get_contents($apiUrl, false, stream_context_create([
            'http' => [
                'method'  => 'POST',
                'header'  => $headers,
                'content' => json_encode(['query' => $query]),
            ]
        ]));

        $responseContent = json_decode($data, true);

        echo json_encode($responseContent);
    }

    public function updateBoard(Request $request)
    {
        $apiUrl = "https://api.monday.com/v2";
        $token = config("services.monday.token");
        $board_id = $request->board_id;

        $query = "mutation { update_board(board_id: $board_id, board_attribute: description, new_value: \"This is my new description\")}";

        $headers = [
            'Content-Type: application/json',
            'Authorization: ' . $token
        ];

        $data = @file_get_contents($apiUrl, false, stream_context_create([
            'http' => [
                'method'  => 'POST',
                'header'  => $headers,
                'content' => json_encode(['query' => $query]),
            ]
        ]));

        $responseContent = json_decode($data, true);

        echo json_encode($responseContent);
    }

    public function deleteBoard(Request $request)
    {
        $apiUrl = "https://api.monday.com/v2";
        $token = config("services.monday.token");
        $board_id = $request->board_id;

        $query = "mutation { delete_board (board_id: $board_id) { id }}";

        $headers = [
            'Content-Type: application/json',
            'Authorization: ' . $token
        ];

        $data = @file_get_contents($apiUrl, false, stream_context_create([
            'http' => [
                'method'  => 'POST',
                'header'  => $headers,
                'content' => json_encode(['query' => $query]),
            ]
        ]));

        $responseContent = json_decode($data, true);

        echo json_encode($responseContent);
    }
}
