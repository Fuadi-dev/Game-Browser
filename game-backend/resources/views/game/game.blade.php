<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Game</title>
    @vite('resources/css/app.css')
    <style>
        .page-title {
            color: #4f46e5;
            font-size: 2.5rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: 2rem;
            letter-spacing: -0.025em;
        }

        /* Add these animation styles */
        .animate-fadeIn {
            animation: fadeIn 0.3s ease-in-out;
        }

        .animate-fadeOut {
            animation: fadeOut 0.3s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeOut {
            from { opacity: 1; transform: translateY(0); }
            to { opacity: 0; transform: translateY(20px); }
        }

        /* Add slide animations for the user modal */
        .slide-in-right {
            animation: slideInRight 0.3s forwards;
        }

        .slide-out-right {
            animation: slideOutRight 0.3s forwards;
        }

        @keyframes slideInRight {
            from { transform: translateX(100%); }
            to { transform: translateX(0); }
        }

        @keyframes slideOutRight {
            from { transform: translateX(0); }
            to { transform: translateX(100%); }
        }
    </style>
</head>

<body class="bg-gray-100">
    @extends('components.sidebar')
    @section('content')
        <div class="container mx-auto px-6 py-6">
            <!-- Title (Centered) -->
            <h1 class="page-title">Game Management</h1>

            <!-- Add Button (Left-aligned) -->
            <div class="mb-4">
            <button type="button" onclick="openAddModal()"
                class="inline-block rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 transition-all">
                    Add Game
            </button>
            </div>

            <!-- User Table -->
            <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-lg">
                <table class="min-w-full divide-y divide-gray-300 bg-white text-sm">
                    <thead class="bg-indigo-100 text-gray-800">
                        <tr>
                            <th class="whitespace-nowrap px-6 py-3 font-semibold text-center">Image</th>
                            @if (Auth::user()->role == 'admin')
                            <th class="whitespace-nowrap px-6 py-3 font-semibold text-center">Developer Name</th>
                            @endif
                            <th class="whitespace-nowrap px-6 py-3 font-semibold text-center">Game Name</th>
                            <th class="whitespace-nowrap px-6 py-3 font-semibold text-center">Description</th>
                            <th class="whitespace-nowrap px-6 py-3 font-semibold text-center">Game Version</th>
                            <th class="whitespace-nowrap px-6 py-3 font-semibold text-center">Categories</th>
                            <th class="whitespace-nowrap px-6 py-3 font-semibold text-center">Played</th>
                            <th class="whitespace-nowrap px-6 py-3 font-semibold text-center">Option</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200">
                        @if (count($games) == 0)
                            <tr>
                                <td colspan="7" class="text-center px-6 py-3 text-gray-700">No data found</td>
                            </tr>
                        @endif

                        @foreach ($games as $game)
                            <tr class="hover:bg-gray-50 transition-all">
                                <td class="text-center px-6 py-3">
                                    @if($game->image)
                                        <img src="{{ asset('storage/images/' . $game->image) }}" alt="{{ $game->name }}" class="w-10 h-10 rounded-full mx-auto object-cover">
                                    @else
                                        <div class="inline-flex items-center justify-center w-10 h-10 rounded-full text-white font-bold mx-auto"
                                            style="background-color: {{ '#' . substr(md5($game->name), 0, 6) }}">
                                            {{ strtoupper(substr($game->name, 0, 2)) }}
                                        </div>
                                    @endif
                                </td>

                                @if (Auth::user()->role == 'admin')
                                <td class="text-center px-6 py-3 text-gray-700 truncate max-w-xs">
                                    {{ $game->user ? $game->user->name : 'Unknown' }}
                                </td>
                                @endif

                                <td class="text-center px-6 py-3 text-gray-700 truncate max-w-xs">{{ $game->name }}</td>
                                <td class="text-center px-6 py-3 text-gray-700 truncate max-w-xs">{{ $game->description }}</td>
                                <td class="text-center px-6 py-3 text-gray-700">{{ $game->game_version }}</td>
                                <td class="text-center px-6 py-3 text-gray-700 truncate max-w-xs">
                                    @foreach($game->categories as $category)
                                        <span class="inline-flex items-center rounded-full bg-indigo-100 px-2.5 py-0.5 text-xs font-medium text-indigo-800 mr-1">
                                            {{ $category->name }}
                                        </span>
                                    @endforeach
                                </td>
                                <td class="text-center px-6 py-3 text-gray-700">{{ $game->played ?? 0 }}</td>
                                <td class="text-center px-6 py-3 text-gray-700">
                                        <!-- Edit -->
                                        <button type="button" onclick="openEditModal('{{ $game->id }}', '{{ $game->name }}')"
                                            class="inline-flex items-center justify-center rounded-full bg-blue-500 p-3 text-white hover:bg-blue-600 focus:outline-none transition-all"
                                            title="Edit Category">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                            </svg>
                                        </button>
                                    <!-- Delete -->
                                    <form action="{{ url('/delete-game') }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="game_id" value="{{ $game->id }}">
                                        <button type="submit"
                                            class="inline-flex items-center justify-center rounded-full bg-red-500 p-3 text-white hover:bg-red-600 focus:outline-none transition-all"
                                            title="Delete Game"
                                            onclick="return confirm('Are you sure you want to delete this game?');">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                            </svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Form Pencarian dan Filter -->
                <div class="bg-white p-4 border-t border-gray-200">
                    <form method="GET" action="{{ url('/game') }}" class="flex flex-wrap items-end gap-4">
                        <!-- Pencarian -->
                        <div class="flex-grow max-w-sm">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Search Game</label>
                            <input type="text" name="search" value="{{ $search ?? '' }}"
                                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-gray-700 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"
                                placeholder="Search game name, description, or version...">
                        </div>

                        <!-- Jumlah per halaman -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Show</label>
                            <select name="per_page" class="rounded-lg border border-gray-300 px-3 py-2 text-gray-700 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                                @foreach ([10, 25, 50, 100] as $value)
                                    <option value="{{ $value }}" {{ ($perPage ?? 10) == $value ? 'selected' : '' }}>{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Tombol Filter -->
                        <div>
                            <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-700 transition-all">
                                Filter
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Pagination Links dengan Tailwind Styling -->
                <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                    {{ $games->links() }}
                </div>
            </div>
        </div>


        {{-- //* Modal untuk menambahkan data game baru *// --}}
        <div id="addCategoryModal" tabindex="-1" aria-hidden="true" class="fixed top-0 right-0 z-50 hidden h-full max-h-full overflow-auto shadow-xl">
            <div class="relative w-96 h-full bg-white border-l border-gray-200">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-medium text-gray-900">Add New Game</h3>
                        <button type="button" onclick="closeAddModal()" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <form class="space-y-6" action="{{ url('/add-game') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div>
                            <label for="image" class="block text-sm font-medium text-gray-700">Game Image <span class="text-red-500">*</span></label>
                            <div class="mt-2">
                                <div class="w-full relative">
                                    <!-- Image upload input -->
                                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 flex flex-col items-center justify-center bg-gray-50 hover:bg-gray-100 transition-colors duration-200">
                                        <div class="preview-container hidden mb-3">
                                            <img id="preview-image" class="h-32 object-contain" alt="Preview">
                                        </div>
                                        <div class="upload-icon-container">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 text-gray-400 mb-2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                                            </svg>
                                            <p class="text-sm text-gray-500 mb-1">Click or drag file to upload</p>
                                            <p class="text-xs text-gray-400">PNG, JPG, JPEG up to 2MB</p>
                                        </div>
                                        <input type="file" name="image" id="image" accept="image/*" required
                                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                            onchange="previewImage(this, 'preview-image')">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Game Name <span class="text-red-500">*</span></label>
                            <div class="mt-2">
                                <input type="text" name="name" id="name" required
                                    class="block w-full rounded-md bg-white px-3 py-2 text-gray-900 border border-gray-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                            </div>
                        </div>

                        <div>
                            <label for="game" class="block text-sm font-medium text-gray-700">Game File <span class="text-red-500">*</span></label>
                            <div class="mt-2">
                                <div class="w-full relative">
                                    <!-- Game file upload input -->
                                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 flex flex-col items-center justify-center bg-gray-50 hover:bg-gray-100 transition-colors duration-200">
                                        <div id="file-name-container" class="hidden mb-3 text-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 text-gray-400 mb-2 mx-auto">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                            </svg>
                                            <p id="file-name-display" class="text-sm font-medium text-gray-700"></p>
                                        </div>
                                        <div class="upload-icon-container items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 text-gray-400 mb-2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                                            </svg>
                                            <p class="text-sm text-gray-500 mb-1">Click or drag file to upload</p>
                                            <p class="text-xs text-gray-400 font-medium">ZIP files only, up to 10MB</p>
                                        </div>
                                        <input type="file" name="game" id="game" accept=".zip" required
                                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                            onchange="previewFile(this, 'file-name-display')">
                                    </div>
                                </div>
                            </div>
                            <div class="mt-2 bg-blue-50 p-3 rounded-md">
                                <h4 class="text-xs font-medium text-blue-700 mb-1">Game File Requirements:</h4>
                                <ul class="text-xs text-blue-600 list-disc list-inside space-y-1">
                                    <li>File must be in ZIP format</li>
                                    <li>Maximum size 10MB</li>
                                    <li>Ensure ZIP file contains a ready-to-run game application</li>
                                    <li>Including a README file with installation instructions is recommended</li>
                                </ul>
                            </div>
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">Description <span class="text-red-500">*</span></label>
                            <div class="mt-2">
                                <textarea name="description" id="description" rows="3" required
                                    class="block w-full rounded-md bg-white px-3 py-2 text-gray-900 border border-gray-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"></textarea>
                            </div>
                        </div>

                        <div>
                            <label for="game_version" class="block text-sm font-medium text-gray-700">Game Version <span class="text-red-500">*</span></label>
                            <div class="mt-2">
                                <input type="text" name="game_version" id="game_version" required
                                    class="block w-full rounded-md bg-white px-3 py-2 text-gray-900 border border-gray-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Categories <span class="text-red-500">*</span></label>
                            <div class="mt-2 grid grid-cols-2 gap-2">
                                @foreach($categories ?? [] as $category)
                                    <div class="flex items-center">
                                        <input type="checkbox" name="categories[]" id="category-{{ $category->id }}" value="{{ $category->id }}"
                                            class="category-checkbox rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                        <label for="category-{{ $category->id }}" class="ml-2 text-sm text-gray-700">{{ $category->name }}</label>
                                    </div>
                                @endforeach
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Select at least one category</p>
                        </div>

                        <div class="pt-4">
                            <button type="submit"
                                class="flex w-full justify-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                Add Game
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- //* Modal untuk mengedit data game *// --}}
        <div id="editGameModal" tabindex="-1" aria-hidden="true" class="fixed top-0 right-0 z-50 hidden h-full max-h-full overflow-auto shadow-xl">
            <div class="relative w-96 h-full bg-white border-l border-gray-200">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-medium text-gray-900">Edit Game</h3>
                        <button type="button" onclick="closeEditModal()" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <form class="space-y-6" action="{{ url('/update-game') }}" method="POST" enctype="multipart/form-data" id="editGameForm">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="game_id" id="edit_game_id">

                        <div>
                            <label for="edit_image" class="block text-sm font-medium text-gray-700">Game Image</label>
                            <div class="mt-2">
                                <div class="w-full relative">
                                    <!-- Current image preview -->
                                    <div class="mb-3 flex justify-center">
                                        <img id="current-image-preview" class="h-32 object-contain border rounded p-1" alt="Current Image">
                                    </div>

                                    <!-- Image upload input -->
                                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 flex flex-col items-center justify-center bg-gray-50 hover:bg-gray-100 transition-colors duration-200">
                                        <div class="preview-container hidden mb-3">
                                            <img id="edit-preview-image" class="h-32 object-contain" alt="Preview">
                                        </div>
                                        <div class="upload-icon-container">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 text-gray-400 mb-2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                                            </svg>
                                            <p class="text-sm text-gray-500 mb-1">Click or drag file to upload new image</p>
                                            <p class="text-xs text-gray-400">PNG, JPG, JPEG up to 2MB</p>
                                        </div>
                                        <input type="file" name="image" id="edit_image" accept="image/*"
                                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                            onchange="previewImage(this, 'edit-preview-image')">
                                    </div>
                                    <p class="mt-1 text-xs text-gray-500">Leave empty to keep current image</p>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label for="edit_name" class="block text-sm font-medium text-gray-700">Game Name <span class="text-red-500">*</span></label>
                            <div class="mt-2">
                                <input type="text" name="name" id="edit_name" required
                                    class="block w-full rounded-md bg-white px-3 py-2 text-gray-900 border border-gray-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                            </div>
                        </div>

                        <div>
                            <label for="edit_game_file" class="block text-sm font-medium text-gray-700">Game File</label>
                            <div class="mt-2">
                                <div class="w-full relative">
                                    <!-- Current file info -->
                                    <div class="mb-3 p-2 bg-gray-100 rounded text-sm" id="current-file-info">
                                        <span id="current-file-name" class="font-medium">filename.zip</span>
                                    </div>

                                    <!-- Game file upload input -->
                                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 flex flex-col items-center justify-center bg-gray-50 hover:bg-gray-100 transition-colors duration-200">
                                        <div id="edit-file-name-container" class="hidden mb-3 text-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 text-gray-400 mb-2 mx-auto">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                            </svg>
                                            <p id="edit-file-name-display" class="text-sm font-medium text-gray-700"></p>
                                        </div>
                                        <div class="upload-icon-container items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 text-gray-400 mb-2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                                            </svg>
                                            <p class="text-sm text-gray-500 mb-1">Click or drag file to upload new game</p>
                                            <p class="text-xs text-gray-400 font-medium">ZIP files only, up to 10MB</p>
                                        </div>
                                        <input type="file" name="game" id="edit_game_file" accept=".zip"
                                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                            onchange="previewFile(this, 'edit-file-name-display', 'edit-file-name-container')">
                                    </div>
                                    <p class="mt-1 text-xs text-gray-500">Leave empty to keep current game file</p>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label for="edit_description" class="block text-sm font-medium text-gray-700">Description <span class="text-red-500">*</span></label>
                            <div class="mt-2">
                                <textarea name="description" id="edit_description" rows="3" required
                                    class="block w-full rounded-md bg-white px-3 py-2 text-gray-900 border border-gray-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"></textarea>
                            </div>
                        </div>

                        <div>
                            <label for="edit_game_version" class="block text-sm font-medium text-gray-700">Game Version <span class="text-red-500">*</span></label>
                            <div class="mt-2">
                                <input type="text" name="game_version" id="edit_game_version" required
                                    class="block w-full rounded-md bg-white px-3 py-2 text-gray-900 border border-gray-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Categories <span class="text-red-500">*</span></label>
                            <div class="mt-2 grid grid-cols-2 gap-2" id="edit-categories-container">
                                @foreach($categories ?? [] as $category)
                                    <div class="flex items-center">
                                        <input type="checkbox" name="categories[]" id="edit-category-{{ $category->id }}" value="{{ $category->id }}"
                                            class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 edit-category-checkbox">
                                        <label for="edit-category-{{ $category->id }}" class="ml-2 text-sm text-gray-700">{{ $category->name }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="pt-4">
                            <button type="submit"
                                class="flex w-full justify-center rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                Update Game
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Debug indicator -->
        @if(session()->has('error'))
            <div style="position: fixed; top: 0; left: 0; background: #4338ca; color: white; padding: 10px; z-index: 9999; font-size: 12px;">
                Error message: {{ session('error') }}
            </div>
        @endif

        <!-- Modal untuk menampilkan pesan kesalahan -->
        @if(session('error'))
        <div id="popup-modal" tabindex="-1" class="fixed bottom-4 right-4 z-50 flex">
            <div class="relative p-4 w-80">
                <div class="relative bg-white rounded-lg shadow-lg border border-gray-200">
                    <button type="button" class="absolute top-3 right-3 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center" onclick="closeModal(event)">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                    <div class="p-4 text-left">
                        <div class="flex items-start">
                            <svg class="text-red-500 w-6 h-6 mr-3 flex-shrink-0" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                            </svg>
                            <div>
                                <h3 class="text-base font-medium text-gray-900 mb-1">Error</h3>
                                <p class="text-sm text-gray-500">{{ session('error') }}</p>
                            </div>
                        </div>
                        <div class="mt-3 flex justify-end">
                            <button onclick="closeModal(event)" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-xs px-3 py-1.5 text-center">
                                Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Modal untuk menampilkan pesan sukses -->
        @if(session('success'))
        <div id="success-modal" tabindex="-1" class="fixed bottom-4 right-4 z-50 flex">
            <div class="relative p-4 w-80">
                <div class="relative bg-white rounded-lg shadow-lg border border-gray-200">
                    <button type="button" class="absolute top-3 right-3 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center" onclick="closeSuccessModal(event)">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                    <div class="p-4 text-left">
                        <div class="flex items-start">
                            <svg class="text-green-500 w-6 h-6 mr-3 flex-shrink-0" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div>
                                <h3 class="text-base font-medium text-gray-900 mb-1">Success</h3>
                                <p class="text-sm text-gray-500">{{ session('success') }}</p>
                            </div>
                        </div>
                        <div class="mt-3 flex justify-end">
                            <button onclick="closeSuccessModal(event)" class="text-white bg-green-600 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-xs px-3 py-1.5 text-center">
                                    Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

    @endsection

    <!-- Script dipindahkan ke dalam body -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM loaded');
        // Cek apakah ada pesan error
        const modal = document.getElementById('popup-modal');
        console.log('Modal element:', modal);

        if (modal) {
            // Tambahkan animasi masuk
            setTimeout(function() {
                modal.classList.add('animate-fadeIn');
            }, 100);

            // Auto close setelah 5 detik
            setTimeout(function() {
                closeModal();
            }, 5000);
        }

        // Cek apakah ada pesan sukses
        const successModal = document.getElementById('success-modal');
        console.log('Success Modal element:', successModal);

        if (successModal) {
            // Tambahkan animasi masuk
            setTimeout(function() {
                successModal.classList.add('animate-fadeIn');
            }, 100);

            // Auto close setelah 5 detik
            setTimeout(function() {
                closeSuccessModal();
            }, 5000);
        }
    });

        // Function untuk modal Add dengan animasi slide-in
    function openAddModal() {
        const modal = document.getElementById('addCategoryModal');
        modal.classList.remove('hidden');
        // Animasi slide-in dari kanan
        setTimeout(() => {
            modal.querySelector('.relative').classList.add('slide-in-right');
        }, 10);
    }

    function closeAddModal() {
        const modal = document.getElementById('addCategoryModal');
        const content = modal.querySelector('.relative');
        // Animasi slide-out ke kanan
        content.classList.remove('slide-in-right');
        content.classList.add('slide-out-right');
        setTimeout(() => {
            modal.classList.add('hidden');
            content.classList.remove('slide-out-right');
        }, 300);
    }

    function closeModal(event) {
        if (event) event.preventDefault();
        const modal = document.getElementById('popup-modal');
        if (modal) {
            // Tambahkan animasi keluar
            modal.classList.add('animate-fadeOut');
            setTimeout(function() {
                modal.classList.add('hidden');
            }, 300);
        }
    }

    function closeSuccessModal(event) {
        if (event) event.preventDefault();
        const successModal = document.getElementById('success-modal');
        if (successModal) {
            // Tambahkan animasi keluar
            successModal.classList.add('animate-fadeOut');
            setTimeout(function() {
                successModal.classList.add('hidden');
            }, 300);
        }
    }

    function previewImage(input, previewId) {
        const preview = document.getElementById(previewId);
        const previewContainer = preview.parentElement;
        const uploadIconContainer = input.closest('.border-dashed').querySelector('.upload-icon-container');

        if (input.files && input.files[0]) {
            const reader = new FileReader();

            reader.onload = function(e) {
                preview.src = e.target.result;
                previewContainer.classList.remove('hidden');

                // Sembunyikan icon dan teks instruksi upload
                if (uploadIconContainer) {
                    uploadIconContainer.classList.add('hidden');
                }
            }

            reader.readAsDataURL(input.files[0]);
        } else {
            preview.src = '';
            previewContainer.classList.add('hidden');

            // Tampilkan kembali icon dan teks instruksi upload
            if (uploadIconContainer) {
                uploadIconContainer.classList.remove('hidden');
            }
        }
    }

    function previewFile(input, previewId) {
        const fileNameContainer = document.getElementById('file-name-container');
        const fileNameDisplay = document.getElementById(previewId);
        const uploadIconContainer = input.closest('.border-dashed').querySelector('.upload-icon-container');

        if (input.files && input.files[0]) {
            // Tampilkan nama file
            const fileName = input.files[0].name;
            fileNameDisplay.textContent = fileName;
            fileNameContainer.classList.remove('hidden');

            // Sembunyikan icon dan teks instruksi upload
            if (uploadIconContainer) {
                uploadIconContainer.classList.add('hidden');
            }
        } else {
            fileNameContainer.classList.add('hidden');

            // Tampilkan kembali icon dan teks instruksi upload
            if (uploadIconContainer) {
                uploadIconContainer.classList.remove('hidden');
            }
        }
    }

    // Add these functions to your existing script section

    function openEditModal(gameId, gameName) {
        // Show modal
        const modal = document.getElementById('editGameModal');
        modal.classList.remove('hidden');

        // Set game ID in form
        document.getElementById('edit_game_id').value = gameId;

        // Fetch game data via AJAX
        fetch(`/get-game/${gameId}`)
            .then(response => response.json())
            .then(data => {
                // Populate the form fields
                document.getElementById('edit_name').value = data.name;
                document.getElementById('edit_description').value = data.description;
                document.getElementById('edit_game_version').value = data.game_version;

                // Set current image preview
                document.getElementById('current-image-preview').src = `/storage/images/${data.image}`;

                // Set current file name
                document.getElementById('current-file-name').textContent = data.game ? data.game + '.zip' : 'No file uploaded';

                // Set categories
                const categoryCheckboxes = document.querySelectorAll('.edit-category-checkbox');
                categoryCheckboxes.forEach(checkbox => {
                    checkbox.checked = data.categories.some(cat => cat.id == checkbox.value);
                });

                // Animate modal in
                setTimeout(() => {
                    modal.querySelector('.relative').classList.add('slide-in-right');
                }, 10);
            })
            .catch(error => {
                console.error('Error fetching game data:', error);
                closeEditModal();
                alert('Failed to load game data. Please try again.');
            });
    }

    function closeEditModal() {
        const modal = document.getElementById('editGameModal');
        const content = modal.querySelector('.relative');

        // Animate modal out
        content.classList.remove('slide-in-right');
        content.classList.add('slide-out-right');

        setTimeout(() => {
            modal.classList.add('hidden');
            content.classList.remove('slide-out-right');

            // Reset form
            document.getElementById('editGameForm').reset();
        }, 300);
    }

    // Extended version of previewFile for edit modal
    function previewFile(input, displayId, containerId) {
        const fileNameContainer = document.getElementById(containerId || 'file-name-container');
        const fileNameDisplay = document.getElementById(displayId);
        const uploadIconContainer = input.closest('.border-dashed').querySelector('.upload-icon-container');

        if (input.files && input.files[0]) {
            // Tampilkan nama file
            const fileName = input.files[0].name;
            fileNameDisplay.textContent = fileName;
            fileNameContainer.classList.remove('hidden');

            // Sembunyikan icon dan teks instruksi upload
            if (uploadIconContainer) {
                uploadIconContainer.classList.add('hidden');
            }
        } else {
            fileNameContainer.classList.add('hidden');

            // Tampilkan kembali icon dan teks instruksi upload
            if (uploadIconContainer) {
                uploadIconContainer.classList.remove('hidden');
            }
        }
    }
</script>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
</body>
</html>
