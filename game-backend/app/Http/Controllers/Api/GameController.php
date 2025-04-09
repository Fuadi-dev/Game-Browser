<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Game;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\DB; // Tambahkan import ini

use ZipArchive;

class GameController extends Controller
{
    public function game()
    {
        $game = Game::all();
        $game->transform(function ($game) {
            $game->imgUrl = URL::to('storage/images/' . $game->image);
            return $game;
        });
        $game->transform(function ($gameZip) {
            $gameZip->gameUrl = URL::to('storage/games/' . str_replace('games-zip', 'games', str_replace('.zip', '', $gameZip->game)));
            return $gameZip;
        });

        return response()->json(
            [
                'status' => 'success',
                'game' => $game,
            ],
            200,
        );
    }

    public function gameById($id)
    {
        $game = Game::with(['user'])->find($id);
        $game->imgUrl = URL::to('storage/images/' . $game->image);
        $game->gameUrl = URL::to('storage/games/' . $game->game);
        $game->developer_name = $game->user ? $game->user->name : 'Unknown';
        return response()->json(
            [
                'status' => 'success',
                'game' => $game,
            ],
            200,
        );
    }


    public function gamePost(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'game' => 'required|file|mimes:zip|max:20480', // Ukuran diperbesar untuk game
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'game_version' => 'required|string|max:255',
            'developer_name' => 'required|string|max:255',
        ]);
        // die(print_r($request->file('image'), true));
        // return response()->json(
        //     [
        //         'status' => 200,
        //         'data' => $request->all(),
        //         'image' => $request->file('image')->getClientOriginalName(),
        //     ],
        //     200,
        // );


        // get file name and set to unique name
        $imageName = time() . '_' . $request->file('image')->getClientOriginalName();
        //save to public_path
        $imagePath = $request->file('image')->storeAs('images', $imageName, 'public');

        // get file name and set to unique name
        $gameName = time() . '_' . $request->file('game')->getClientOriginalName();
        $gamePath = $request->file('game')->storeAs('games-zip', $gameName, 'public');

        // Simpan ke database
        $game = new Game();
        $game->$imageName;
        $game->$gamePath;
        $game->name = $request->name;
        $game->description = $request->description;
        $game->game = $gameName;
        $game->image = $imageName;
        $game->game_version = $request->game_version;
        $game->developer_name = $request->developer_name;

        $fname = basename($game->game, '.zip');
        $storage = str_replace('\\', '/', public_path('storage'));
         // $game->game = $game->game . '.zip';
         //unzip the game file
         $zip = new \ZipArchive;
         $zip->open("$storage/games-zip/$fname.zip");
         //if
         $zip->extractTo("$storage/games/$fname");
         //only one folderr in the zip file

         $zip->close();
         //delete the zip file
            unlink("$storage/games-zip/$fname.zip") or die("Could not delete the file");

         //move the game file to games folder
         $list = glob("$storage/games/$fname/*");
         if(count($list) == 1){
             $files = glob($list[0]. '/*');
             foreach($files as $file){
                //move folder to games folder
                rename($file, "$storage/games/$fname/".basename($file));
             }
                //delete the folder
                rmdir($list[0]);
         }
         $game->game = str_replace('.zip', '', $game->game);
         $game->save();

        return response()->json([
            'status' => 'ok',
            'data' => 'added success',
        ], 200);

    }

    public function playGame(Request $request, $id)
    {
        // Cari game
        $game = Game::find($id);

        if (!$game) {
            return response()->json([
                'status' => 'error',
                'message' => 'Game tidak ditemukan',
            ], 404);
        }

        // Periksa apakah pengguna sudah terautentikasi
        if ($request->user()) {
            $user = $request->user();

            // Periksa apakah pengguna sudah pernah memainkan game ini
            $alreadyPlayed = DB::table('game_plays') // Hapus backslash
                ->where('user_id', $user->id)
                ->where('game_id', $id)
                ->exists();

            if (!$alreadyPlayed) {
                // Tambahkan penghitung played
                $game->increment('played');

                // Catat bahwa pengguna ini telah memainkan game ini
                DB::table('game_plays')->insert([ // Hapus backslash
                    'user_id' => $user->id,
                    'game_id' => $id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Permainan game berhasil dicatat',
                ], 200);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Sudah pernah dimainkan',
            ], 200);
        }

        // Untuk pengguna tamu, kita akan menambah hitungan sekali per sesi
        return response()->json([
            'status' => 'success',
            'message' => 'Permainan tamu dicatat',
        ], 200);
    }

    public function getRecommendations($id)
    {
        // Find the current game
        $currentGame = Game::find($id);

        if (!$currentGame) {
            return response()->json([
                'status' => 'error',
                'message' => 'Game tidak ditemukan',
            ], 404);
        }

        // Get categories of the current game
        $categoryIds = $currentGame->categories()->pluck('categories.id');

        if ($categoryIds->isEmpty()) {
            return response()->json([
                'status' => 'success',
                'recommendations' => [],
                'message' => 'No categories found for this game'
            ], 200);
        }

        // Get games that share at least one category with the current game
        $recommendations = Game::where('id', '!=', $id)
                            ->whereHas('categories', function($query) use ($categoryIds) {
                                $query->whereIn('categories.id', $categoryIds);
                            })
                            ->limit(5)
                            ->get();

        // Transform URLs like in other methods
        $recommendations->transform(function ($game) {
            $game->imgUrl = URL::to('storage/images/' . $game->image);
            return $game;
        });

        return response()->json([
            'status' => 'success',
            'recommendations' => $recommendations,
        ], 200);
    }

    public function searchGames(Request $request)
    {
        $query = Game::query();

        // Search by name
        if ($request->has('name') && !empty($request->name)) {
            $query->where('name', 'LIKE', '%' . $request->name . '%');
        }

        // Search by category
        if ($request->has('category') && !empty($request->category)) {
            $categoryId = $request->category;
            $query->whereHas('categories', function($q) use ($categoryId) {
                $q->where('categories.id', $categoryId);
            });
        }

        $games = $query->get();

        // Transform URLs like in other methods
        $games->transform(function ($game) {
            $game->imgUrl = URL::to('storage/images/' . $game->image);
            return $game;
        });

        return response()->json([
            'status' => 'success',
            'games' => $games,
        ], 200);
    }

    public function categories(){
        $categories = Category::all();
        return response()->json([
            'status' => 'success',
            'categories' => $categories,
        ], 200);
    }
}
