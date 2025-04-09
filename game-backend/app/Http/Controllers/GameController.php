<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Game;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class GameController extends Controller
{
    public function game(Request $request)
    {
        $perPage = $request->get('per_page', 10); // Default 10 item per page
        $search = $request->get('search', ''); // Search Parameter
        $sortBy = $request->get('sort_by', 'name'); // Default sort by name
        $sortDir = $request->get('sort_dir', 'asc'); // Default ascending

        $query = Game::with(['user', 'categories']); // Eager load developer and categories

        // Filter by user role developer
        if (Auth::user()->role === 'developer') {
            // If developer, show only their own games
            $query->where('user_id', Auth::user()->id);
        }
        // If admin, show all games (including developer's games)

        // Filter by category
        if ($request->has('category_id') && !empty($request->category_id)) {
            $query->whereHas('categories', function($q) use ($request) {
                $q->where('categories.id', $request->category_id);
            });
        }

        // search by name, description, and game version
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('game_version', 'like', "%{$search}%");
            });
        }

        // Sorting data
        $query->orderBy($sortBy, $sortDir);

        // Pagination dengan parameter dinamis
        $games = $query->paginate($perPage)->withQueryString();
        $categories = Category::all();

        return view('game.game', compact('games', 'categories', 'search', 'sortBy', 'sortDir', 'perPage'));
    }
    function gamePost(Request $request) {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'game' => 'required|file|mimes:zip|max:20480', // Ukuran diperbesar untuk game
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'game_version' => 'required|string|max:255',
            'categories.*' => 'exists:categories,id',
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
        $game->user_id = Auth::user()->id;
        $game->$imageName;
        $game->$gamePath;
        $game->name = $request->name;
        $game->description = $request->description;
        $game->game = $gameName;
        $game->image = $imageName;
        $game->game_version = $request->game_version;
        // $game->

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
                // rmdir($list[0]);
         }
         $game->game = str_replace('.zip', '', $game->game);
         $game->save();
         if ($request->has('categories')) {
            $game->categories()->sync($request->categories);
        } else {
            $game->categories()->detach(); // Hapus semua kategori jika tidak ada yang dipilih
        }
        return redirect()->back()->with('success', 'Game berhasil ditambahkan');

    }

    public function getGame($id){
        $game = Game::with('categories')->findOrFail($id);
        return response()->json($game);
    }

    function updateGame(Request $request){
        // Validasi input
        $request->validate([
            'game_id' => 'required|exists:games,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'game' => 'nullable|file|mimes:zip|max:20480',
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'game_version' => 'required|string|max:255',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
        ]);

        // Cari game yang akan diupdate
        $game = Game::findOrFail($request->game_id);

        // Cek otorisasi, hanya admin atau developer pemilik game yang boleh edit
        if (Auth::user()->role !== 'admin' && $game->user_id !== Auth::user()->id) {
            return redirect()->back()->with('error', 'You are not authorized to edit this game');
        }

        // Update informasi dasar
        $game->name = $request->name;
        $game->description = $request->description;
        $game->game_version = $request->game_version;

        $storage = str_replace('\\', '/', public_path('storage'));

        // Handle upload gambar baru jika ada
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($game->image && file_exists("$storage/images/{$game->image}")) {
                unlink("$storage/images/{$game->image}");
            }

            // Upload gambar baru
            $imageName = time() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->storeAs('images', $imageName, 'public');
            $game->image = $imageName;
        }

        // Handle upload file game baru jika ada
        if ($request->hasFile('game')) {
            // Hapus direktori game lama beserta isinya
            $oldGameDir = "$storage/games/{$game->game}";
            if (file_exists($oldGameDir)) {
                $this->deleteDirectory($oldGameDir);
            }

            // Upload dan proses file game baru
            $gameName = time() . '_' . $request->file('game')->getClientOriginalName();
            $request->file('game')->storeAs('games-zip', $gameName, 'public');

            // Ekstrak file zip
            $fname = basename($gameName, '.zip');
            $zip = new \ZipArchive;
            $zip->open("$storage/games-zip/$fname.zip");
            $zip->extractTo("$storage/games/$fname");
            $zip->close();

            // Bersihkan file zip setelah diekstrak
            unlink("$storage/games-zip/$fname.zip") or die("Could not delete the file");

            // Proses konten jika dalam satu direktori
            $list = glob("$storage/games/$fname/*");
            if(count($list) == 1 && is_dir($list[0])) {
                // Pastikan bahwa item pertama adalah direktori
                $firstDir = $list[0];

                // Dapatkan semua file termasuk subdirektori
                $dirIterator = new \RecursiveDirectoryIterator($firstDir, \RecursiveDirectoryIterator::SKIP_DOTS);
                $iterator = new \RecursiveIteratorIterator($dirIterator, \RecursiveIteratorIterator::LEAVES_ONLY);

                // Pindahkan semua file ke direktori target
                foreach ($iterator as $file) {
                    // Dapatkan path relatif dari file terhadap direktori sumber
                    $subPath = substr($file->getPathname(), strlen($firstDir) + 1);
                    $targetPath = "$storage/games/$fname/" . $subPath;
                    $targetDir = dirname($targetPath);

                    // Buat direktori target jika belum ada
                    if (!is_dir($targetDir)) {
                        mkdir($targetDir, 0755, true);
                    }

                    // Pindahkan file
                    rename($file->getPathname(), $targetPath);
                }

                // Hapus direktori asli setelah semua file dipindahkan
                $this->deleteDirectory($firstDir);
            }

            $game->game = $fname;
        }

        // Simpan perubahan game
        $game->save();

        // Update kategori dengan sync (menambah yang baru, menghapus yang tidak dipilih)
        if ($request->has('categories')) {
            $game->categories()->sync($request->categories);
        } else {
            $game->categories()->detach(); // Hapus semua kategori jika tidak ada yang dipilih
        }

        return redirect()->back()->with('success', 'Game successfully updated');
    }

    // Fungsi helper untuk menghapus direktori dan isinya
    private function deleteDirectory($dir) {
        if (!file_exists($dir)) {
            return true;
        }

        // Jika bukan direktori, langsung hapus file
        if (!is_dir($dir)) {
            return unlink($dir);
        }

        // Gunakan DirectoryIterator untuk menangani semua file dan subdirektori
        $it = new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS);
        $files = new \RecursiveIteratorIterator($it, \RecursiveIteratorIterator::CHILD_FIRST);

        // Hapus semua file dan subdirektori (child first)
        foreach($files as $file) {
            if ($file->isDir()) {
                rmdir($file->getRealPath());
            } else {
                unlink($file->getRealPath());
            }
        }

        // Hapus direktori utama
        return rmdir($dir);
    }

    public function gameDelete(Request $request){
        $game = Game::find($request->game_id);

        if(!$game) {
            return redirect()->back()->with('error', 'Game not found');
        }

        // Authorization check
        if (Auth::user()->role !== 'admin' && $game->user_id !== Auth::user()->id) {
            return redirect()->back()->with('error', 'You are not authorized to delete this game');
        }

        // // Get storage paths
        // $storage = str_replace('\\', '/', public_path('storage'));
        // $gameDir = "$storage/games/{$game->game}";
        // $imagePath = "$storage/images/{$game->image}";

        // // First detach all category relationships
        // $game->categories()->detach();

        // // Delete the game record
        $game->delete();

        // // Delete image file if exists
        // if ($game->image && file_exists($imagePath)) {
        //     unlink($imagePath);
        // }

        // // Delete game directory if exists
        // if (file_exists($gameDir)) {
        //     $this->deleteDirectory($gameDir);
        // }

        return redirect()->back()->with('success', 'Game successfully deleted');
    }
}

