<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard</title>
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
  </style>
</head>
<body class="bg-gray-100">
    @extends('components.sidebar')
    @section('content')
    <div class="container mx-auto px-6 py-8">
        <h1 class="page-title">Dashboard</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <!-- Card untuk Total Games -->
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 text-blue-500">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 9.563C9 9.252 9.252 9 9.563 9h4.874c.311 0 .563.252.563.563v4.874c0 .311-.252.563-.563.563H9.564A.562.562 0 0 1 9 14.437V9.564Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 4v4M4 8h4M16 4v4M20 8h-4M4 16h4v4M16 16h4v4" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-lg text-gray-600">Total Games</p>
                        <p class="text-2xl font-semibold text-gray-800">{{ $data['totalGames'] }}</p>
                    </div>
                </div>
            </div>

            <!-- Card untuk Total Kategori -->
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 text-green-500">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-lg text-gray-600">Total Categories</p>
                        <p class="text-2xl font-semibold text-gray-800">{{ $data['totalCategories'] }}</p>
                    </div>
                </div>
            </div>

            @if(Auth::user()->role === 'admin')
            <!-- Card untuk Total Developers (Admin only) -->
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100 mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 text-purple-500">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-lg text-gray-600">Total Developers</p>
                        <p class="text-2xl font-semibold text-gray-800">{{ $data['totalDevelopers'] }}</p>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Main Content Area -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Left Column -->
            <div>
                @if(Auth::user()->role === 'admin')
                <!-- Top Developer Widget (Admin only) -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Top Developer</h2>
                    @if(isset($data['topDeveloper']))
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-12 w-12 rounded-full bg-indigo-500 text-white flex items-center justify-center font-bold text-xl">
                            {{ strtoupper(substr($data['topDeveloper']->name ?? 'N/A', 0, 2)) }}
                        </div>
                        <div class="ml-4">
                            <p class="text-lg font-medium text-gray-800">{{ $data['topDeveloper']->name ?? 'No developers yet' }}</p>
                            <p class="text-gray-600">{{ $data['topDeveloper']->game_count ?? 0 }} games</p>
                        </div>
                    </div>
                    @else
                    <p class="text-gray-600">No developers have created games yet.</p>
                    @endif
                </div>

                <!-- Games per Category Widget (Admin only) -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Games per Category</h2>
                    @if(count($data['gamesByCategory'] ?? []) > 0)
                    <div class="space-y-4">
                        @foreach($data['gamesByCategory'] as $category)
                        <div class="flex items-center justify-between">
                            <span class="text-gray-700">{{ $category->name }}</span>
                            <div class="flex items-center">
                                <span class="text-gray-700 font-medium">{{ $category->game_count }}</span>
                                <div class="h-2 w-32 bg-gray-200 rounded-full ml-2">
                                    <div class="h-2 bg-indigo-500 rounded-full" style="width: {{ min(100, ($category->game_count / max(1, $data['totalGames'])) * 100) }}%"></div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <p class="text-gray-600">No categories with games yet.</p>
                    @endif
                </div>
                @else
                <!-- Developer's Top Categories -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Your Top Categories</h2>
                    @if(count($data['topCategories'] ?? []) > 0)
                    <div class="space-y-4">
                        @foreach($data['topCategories'] as $category)
                        <div class="flex items-center justify-between">
                            <span class="text-gray-700">{{ $category->name }}</span>
                            <div class="flex items-center">
                                <span class="text-gray-700 font-medium">{{ $category->game_count }}</span>
                                <div class="h-2 w-32 bg-gray-200 rounded-full ml-2">
                                    <div class="h-2 bg-indigo-500 rounded-full" style="width: {{ min(100, ($category->game_count / max(1, $data['totalGames'])) * 100) }}%"></div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <p class="text-gray-600">You haven't added any games with categories yet.</p>
                    @endif
                </div>
                @endif
            </div>

            <!-- Right Column -->
            <div>
                <!-- Top Games Widget -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">
                        {{ Auth::user()->role === 'admin' ? 'Most Played Games' : 'Your Most Played Games' }}
                    </h2>

                    @if(count($data['topGames'] ?? []) > 0)
                        <div class="space-y-4">
                            @foreach($data['topGames'] as $index => $game)
                                <div class="flex items-center">
                                    <!-- Medal based on ranking -->
                                    <div class="flex-shrink-0 mr-3">
                                        @if($index == 0)
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-8 h-8 text-yellow-500">
                                                <path fill-rule="evenodd" d="M12 1.5a5.25 5.25 0 0 0-5.25 5.25v3a3 3 0 0 0-3 3v6.75a3 3 0 0 0 3 3h10.5a3 3 0 0 0 3-3v-6.75a3 3 0 0 0-3-3v-3A5.25 5.25 0 0 0 12 1.5Zm3.75 8.25v-3a3.75 3.75 0 1 0-7.5 0v3h7.5Z" clip-rule="evenodd" />
                                            </svg>
                                        @elseif($index == 1)
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-8 h-8 text-gray-400">
                                                <path fill-rule="evenodd" d="M12 1.5a5.25 5.25 0 0 0-5.25 5.25v3a3 3 0 0 0-3 3v6.75a3 3 0 0 0 3 3h10.5a3 3 0 0 0 3-3v-6.75a3 3 0 0 0-3-3v-3A5.25 5.25 0 0 0 12 1.5Zm3.75 8.25v-3a3.75 3.75 0 1 0-7.5 0v3h7.5Z" clip-rule="evenodd" />
                                            </svg>
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-8 h-8 text-amber-700">
                                                <path fill-rule="evenodd" d="M12 1.5a5.25 5.25 0 0 0-5.25 5.25v3a3 3 0 0 0-3 3v6.75a3 3 0 0 0 3 3h10.5a3 3 0 0 0 3-3v-6.75a3 3 0 0 0-3-3v-3A5.25 5.25 0 0 0 12 1.5Zm3.75 8.25v-3a3.75 3.75 0 1 0-7.5 0v3h7.5Z" clip-rule="evenodd" />
                                            </svg>
                                        @endif
                                    </div>

                                    <!-- Game image (circular) -->
                                    <div class="flex-shrink-0 mr-4">
                                        @if($game->image)
                                            <img src="{{ asset('storage/images/' . $game->image) }}" alt="{{ $game->name }}"
                                                class="w-12 h-12 rounded-full object-cover border-2
                                                {{ $index == 0 ? 'border-yellow-500' : ($index == 1 ? 'border-gray-400' : 'border-amber-700') }}">
                                        @else
                                            <div class="w-12 h-12 rounded-full bg-gray-200 flex items-center justify-center
                                                {{ $index == 0 ? 'border-2 border-yellow-500' : ($index == 1 ? 'border-2 border-gray-400' : 'border-2 border-amber-700') }}">
                                                <span class="text-gray-500 font-bold">{{ strtoupper(substr($game->name, 0, 2)) }}</span>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Game info -->
                                    <div class="flex-grow">
                                        <p class="font-medium text-gray-800">{{ $game->name }}</p>
                                        <div class="flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 text-indigo-500">
                                                <path d="M4.5 6.375a4.125 4.125 0 1 1 8.25 0 4.125 4.125 0 0 1-8.25 0ZM14.25 8.625a3.375 3.375 0 1 1 6.75 0 3.375 3.375 0 0 1-6.75 0ZM1.5 19.125a7.125 7.125 0 0 1 14.25 0v.003l-.001.119a.75.75 0 0 1-.363.63 13.067 13.067 0 0 1-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 0 1-.364-.63l-.001-.122ZM17.25 19.128l-.001.144a2.25 2.25 0 0 1-.233.96 10.088 10.088 0 0 0 5.06-1.01.75.75 0 0 0 .42-.643 4.875 4.875 0 0 0-6.957-4.611 8.586 8.586 0 0 1 1.71 5.157v.003Z" />
                                            </svg>
                                            <span class="ml-1 text-gray-700">{{ number_format($game->played) }} plays</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="bg-gray-100 rounded-lg p-4 text-center">
                            <p class="text-gray-600">No games available yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endsection
</body>
</html>
