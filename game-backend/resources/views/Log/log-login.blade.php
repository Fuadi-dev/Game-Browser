<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Log Login</title>
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
    <!-- filepath: d:\laragon\www\Game-Browser\game-backend\resources\views\Log\log-login.blade.php -->
@extends('components.sidebar')
@section('content')
<div class="container mx-auto px-6 py-6">
    <h1 class="page-title">History Login</h1>

    <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-lg">
        <table class="min-w-full divide-y divide-gray-300 bg-white text-sm">
            <thead class="bg-indigo-100 text-gray-800">
                <tr>
                    <th class="whitespace-nowrap px-6 py-3 font-semibold text-center">Name</th>
                    <th class="whitespace-nowrap px-6 py-3 font-semibold text-center">Email</th>
                    <th class="whitespace-nowrap px-6 py-3 font-semibold text-center">Login At</th>
                    <th class="whitespace-nowrap px-6 py-3 font-semibold text-center">Logout At</th>
                    <th class="whitespace-nowrap px-6 py-3 font-semibold text-center">Duration</th>
                    <th class="whitespace-nowrap px-6 py-3 font-semibold text-center">IP Address</th>
                    <th class="whitespace-nowrap px-6 py-3 font-semibold text-center">Browser</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                {{-- if data log is null show not found --}}
                @if (count($logs) == 0)
                <tr>
                    <td colspan="7" class="text-center px-6 py-3 text-gray-700">No data logs found</td>
                </tr>
                @endif
                @foreach($logs as $log)
                <tr class="hover:bg-gray-50 transition-all">
                    <td class="text-center px-6 py-3 text-gray-700">{{ $log->user->name }}</td>
                    <td class="text-center px-6 py-3 text-gray-700">{{ $log->user->email }}</td>
                    <td class="text-center px-6 py-3 text-gray-700">{{ $log->login_at->format('d M Y, H:i:s') }}</td>
                    <td class="text-center px-6 py-3 text-gray-700">
                        @if($log->logout_at)
                            {{ $log->logout_at->format('d M Y, H:i:s') }}
                        @else
                            <span class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
                                Active
                            </span>
                        @endif
                    </td>
                    <td class="text-center px-6 py-3 text-gray-700">{{ $log->duration }}</td>
                    <td class="text-center px-6 py-3 text-gray-700">{{ $log->ip_address }}</td>
                    <td class="text-center px-6 py-3 text-gray-700 truncate max-w-xs">{{ $log->browser }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
                        <!-- Form Pencarian dan Filter -->
                <div class="bg-white p-4 border-t border-gray-200">
                    <form method="GET" action="{{ url('/login-history') }}" class="flex flex-wrap items-end gap-4">
                        <!-- Pencarian -->
                        <div class="flex-grow max-w-sm">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Search History</label>
                            <input type="text" name="search" value="{{ $search ?? '' }}"
                                   class="w-full rounded-lg border border-gray-300 px-3 py-2 text-gray-700 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"
                                   placeholder="Search name, email, or ip_address...">
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

                        <!-- Sort By -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Sort By</label>
                            <select name="sort_by" class="rounded-lg border border-gray-300 px-3 py-2 text-gray-700 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                                <option value="login_at" {{ ($sortBy ?? 'login_at') == 'login_at' ? 'selected' : '' }}>Login Time</option>
                                <option value="logout_at" {{ ($sortBy ?? '') == 'logout_at' ? 'selected' : '' }}>Logout Time</option>
                                <option value="ip_address" {{ ($sortBy ?? '') == 'ip_address' ? 'selected' : '' }}>IP Address</option>
                            </select>
                        </div>

                        <!-- Sort Direction -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Direction</label>
                            <select name="sort_dir" class="rounded-lg border border-gray-300 px-3 py-2 text-gray-700 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                                <option value="desc" {{ ($sortDir ?? 'desc') == 'desc' ? 'selected' : '' }}>Descending</option>
                                <option value="asc" {{ ($sortDir ?? '') == 'asc' ? 'selected' : '' }}>Ascending</option>
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

    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $logs->links() }}
    </div>
</div>
@endsection

</body>
</html>
