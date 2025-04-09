<!-- filepath: d:\laragon\www\Game-Browser\game-backend\resources\views\category\category.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Category</title>
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

        /* Add these slide-in and slide-out styles */
        .slide-in-right {
            animation: slideInRight 0.3s ease-in-out forwards;
        }

        .slide-out-right {
            animation: slideOutRight 0.3s ease-in-out forwards;
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
            <h1 class="page-title">Category Management</h1>

            <!-- Add Button (Left-aligned) -->
            <div class="mb-4">
                <button type="button" onclick="openAddModal()"
                    class="inline-block rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 transition-all">
                    Add Category
                </button>
            </div>

            <!-- Category Table -->
            <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-lg">
                <table class="min-w-full divide-y divide-gray-300 bg-white text-sm">
                    <thead class="bg-indigo-100 text-gray-800">
                        <tr>
                            <th class="whitespace-nowrap px-6 py-3 font-semibold text-center">No</th>
                            <th class="whitespace-nowrap px-6 py-3 font-semibold text-center">Nama</th>
                            <th class="whitespace-nowrap px-6 py-3 font-semibold text-center">Option</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200">
                        @if(count($category) == 0)
                            <tr>
                                <td colspan="3" class="text-center px-6 py-3 text-gray-700">No data category found</td>
                            </tr>
                        @else
                            @foreach ($category as $index => $ctgry)
                                <tr class="hover:bg-gray-50 transition-all">
                                    <td class="text-center px-6 py-3 text-gray-700">{{ $index + 1 }}</td>
                                    <td class="text-center px-6 py-3 text-gray-700 truncate max-w-xs">{{ $ctgry->name }}</td>
                                    <td class="text-center px-6 py-3 text-gray-700">
                                        <!-- Edit -->
                                        <button type="button" onclick="openEditModal('{{ $ctgry->id }}', '{{ $ctgry->name }}')"
                                            class="inline-flex items-center justify-center rounded-full bg-blue-500 p-3 text-white hover:bg-blue-600 focus:outline-none transition-all"
                                            title="Edit Category">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                            </svg>
                                        </button>
                                        <!-- Delete -->
                                        <form action="{{ url('/delete-category') }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="category_id" value="{{ $ctgry->id }}">
                                            <button type="submit"
                                                class="inline-flex items-center justify-center rounded-full bg-red-500 p-3 text-white hover:bg-red-600 focus:outline-none transition-all"
                                                title="Delete Category"
                                                onclick="return confirm('Are you sure you want to delete this category?');">
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
                        @endif
                    </tbody>
                </table>

                <!-- Form Pencarian dan Filter -->
                <div class="bg-white p-4 border-t border-gray-200">
                    <form method="GET" action="{{ url('/category') }}" class="flex flex-wrap items-end gap-4">
                        <!-- Pencarian -->
                        <div class="flex-grow max-w-sm">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Search Category</label>
                            <input type="text" name="search" value="{{ $search ?? '' }}"
                                   class="w-full rounded-lg border border-gray-300 px-3 py-2 text-gray-700 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"
                                   placeholder="Search ...">
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
                    {{ $category->links() }}
                </div>
            </div>
        </div>

        <!-- Modal untuk menambah kategori - Style non-blocking -->
        <div id="addCategoryModal" tabindex="-1" aria-hidden="true" class="fixed top-0 right-0 z-50 hidden h-full max-h-full overflow-auto shadow-xl">
            <div class="relative w-96 h-full bg-white border-l border-gray-200">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-medium text-gray-900">Add New Category</h3>
                        <button type="button" onclick="closeAddModal()" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <form class="space-y-6" action="{{ url('/add-category') }}" method="POST">
                        @csrf
                        <div>
                            <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Category Name</label>
                            <input type="text" name="name" id="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5" placeholder="Enter category name" required>
                        </div>
                        <button type="submit" class="w-full text-white bg-indigo-600 hover:bg-indigo-700 focus:ring-4 focus:outline-none focus:ring-indigo-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Add Category</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal untuk edit kategori - Style non-blocking -->
        <div id="editCategoryModal" tabindex="-1" aria-hidden="true" class="fixed top-0 right-0 z-50 hidden h-full max-h-full overflow-auto shadow-xl">
            <div class="relative w-96 h-full bg-white border-l border-gray-200">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-medium text-gray-900">Edit Category</h3>
                        <button type="button" onclick="closeEditModal()" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <form class="space-y-6" action="{{ url('/update-category') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="category_id" id="edit_category_id">
                        <div>
                            <label for="edit_name" class="block mb-2 text-sm font-medium text-gray-900">Category Name</label>
                            <input type="text" name="name" id="edit_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5" placeholder="Enter category name" required>
                        </div>
                        <button type="submit" class="w-full text-white bg-indigo-600 hover:bg-indigo-700 focus:ring-4 focus:outline-none focus:ring-indigo-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Update Category</button>
                    </form>
                </div>
            </div>
        </div>

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
                                Tutup
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

    // Function untuk modal Edit dengan animasi slide-in
    function openEditModal(id, name) {
        document.getElementById('edit_category_id').value = id;
        document.getElementById('edit_name').value = name;
        const modal = document.getElementById('editCategoryModal');
        modal.classList.remove('hidden');
        // Animasi slide-in dari kanan
        setTimeout(() => {
            modal.querySelector('.relative').classList.add('slide-in-right');
        }, 10);
    }

    function closeEditModal() {
        const modal = document.getElementById('editCategoryModal');
        const content = modal.querySelector('.relative');
        // Animasi slide-out ke kanan
        content.classList.remove('slide-in-right');
        content.classList.add('slide-out-right');
        setTimeout(() => {
            modal.classList.add('hidden');
            content.classList.remove('slide-out-right');
        }, 300);
    }

    // Function untuk notification modals
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
    </script>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
</body>
</html>
