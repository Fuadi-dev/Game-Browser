<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login | E-Commerce</title>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .notification {
            position: fixed;
            top: 1rem;
            right: 1rem;
            padding: 1rem;
            border-radius: 0.5rem;
            color: white;
            max-width: 24rem;
            z-index: 50;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            transform: translateX(150%);
            transition: transform 0.3s ease-in-out;
        }
        .notification.show {
            transform: translateX(0);
        }
        .notification-error {
            background-color: #EF4444;
        }
        .notification-success {
            background-color: #10B981;
        }
    </style>
</head>
<body class="bg-gray-50 font-[Poppins]">
     <!-- Error Notification -->
     @if(session('error'))
     <div id="error-notification" class="notification notification-error">
         <div class="flex items-center">
             <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
             </svg>
             <span>{{ session('error') }}</span>
         </div>
     </div>
     @endif

    <!-- Success Notification -->
    @if(session('success'))
    <div id="success-notification" class="notification notification-success">
        <div class="flex items-center">
            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    </div>
    @endif

    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="flex w-full max-w-5xl rounded-xl shadow-lg overflow-hidden">
            <!-- Left side - Image -->
            <div class="hidden md:block md:w-1/2 bg-gradient-to-br from-orange-400 to-orange-600 p-12 relative">
                <div class="absolute inset-0 bg-black opacity-10 z-0"></div>
                <div class="relative z-10 h-full flex flex-col justify-between">
                    <div>
                        <h1 class="text-4xl font-bold text-white mb-6">Hello Developer</h1>
                        <p class="text-white/90 text-lg">Discover amazing products at incredible prices.</p>
                    </div>

                    <div class="space-y-6">
                        <div class="bg-white/10 p-4 rounded-lg backdrop-blur-sm">
                            <p class="text-white italic">"The best game play experience I've ever had. Fast delivery and great products!"</p>
                            <p class="text-white/80 mt-2">- Happy Gammers</p>
                        </div>

                        <div class="flex space-x-3">
                            <span class="w-2 h-2 rounded-full bg-white"></span>
                            <span class="w-2 h-2 rounded-full bg-white/50"></span>
                            <span class="w-2 h-2 rounded-full bg-white/50"></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right side - Login Form -->
            <div class="w-full md:w-1/2 bg-white p-8 md:p-12">
                <div class="mb-8">
                    <h2 class="text-3xl font-bold text-gray-800">Welcome Back!</h2>
                    <p class="text-gray-600 mt-2">Please sign in to your account</p>
                </div>

                <form method="POST" action="{{ url('/login') }}" class="space-y-6">
                    @csrf
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-orange-500 focus:border-orange-500 transition duration-200"
                            placeholder="your@email.com">
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                            {{-- <a href="{{ route('password.request') }}" class="text-sm text-orange-600 hover:text-orange-800 transition">
                                Forgot password?
                            </a> --}}
                        </div>
                        <input id="password" type="password" name="password" required
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-orange-500 focus:border-orange-500 transition duration-200"
                            placeholder="••••••••">
                        @error('password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center">
                        <input id="remember_me" type="checkbox" name="remember"
                            class="h-4 w-4 text-orange-600 border-gray-300 rounded focus:ring-orange-500">
                        <label for="remember_me" class="ml-2 block text-sm text-gray-700">
                            Remember me
                        </label>
                    </div>

                    <div>
                        <button type="submit"
                            class="w-full bg-orange-500 hover:bg-orange-600 text-white font-medium py-3 px-4 rounded-lg transition duration-200 transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-opacity-50">
                            Sign In
                        </button>
                    </div>
                </form>

                <div class="mt-8">
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-300"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-2 bg-white text-gray-500">Or continue with</span>
                        </div>
                    </div>

                    <div class="mt-6 grid grid-cols-2 gap-3">
                        <a href="#" class="flex justify-center items-center py-2.5 border rounded-lg hover:bg-gray-50 transition duration-150">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12.545 10.239v3.821h5.445c-0.712 2.315-2.647 3.972-5.445 3.972-3.332 0-6.033-2.701-6.033-6.032s2.701-6.032 6.033-6.032c1.498 0 2.866 0.549 3.921 1.453l2.814-2.814c-1.787-1.676-4.139-2.701-6.735-2.701-5.522 0-10.003 4.481-10.003 10.003s4.481 10.003 10.003 10.003c8.025 0 9.304-7.471 8.510-11.675l-13.509 0.002z"></path>
                            </svg>
                            <span class="text-sm font-medium text-gray-700 ml-2">Google</span>
                        </a>

                        <a href="#" class="flex justify-center items-center py-2.5 border rounded-lg hover:bg-gray-50 transition duration-150">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M22.675 0h-21.35c-.732 0-1.325.593-1.325 1.325v21.351c0 .731.593 1.324 1.325 1.324h11.495v-9.294h-3.128v-3.622h3.128v-2.671c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.463.099 2.795.143v3.24l-1.918.001c-1.504 0-1.795.715-1.795 1.763v2.313h3.587l-.467 3.622h-3.12v9.293h6.116c.73 0 1.323-.593 1.323-1.325v-21.35c0-.732-.593-1.325-1.325-1.325z"></path>
                            </svg>
                            <span class="text-sm font-medium text-gray-700 ml-2">Facebook</span>
                        </a>
                    </div>
                </div>

                <p class="mt-8 text-center text-sm text-gray-600">
                    Do you want back to the main page?
                    <a href="{{ url('/main') }}" class="font-medium text-orange-600 hover:text-orange-500 transition">
                        Back now
                    </a>
                </p>
            </div>
        </div>
    </div>
    <script>
        // Show notifications
        document.addEventListener('DOMContentLoaded', function() {
            const errorNotification = document.getElementById('error-notification');
            const successNotification = document.getElementById('success-notification');

            if (errorNotification) {
                setTimeout(() => {
                    errorNotification.classList.add('show');
                }, 100);

                setTimeout(() => {
                    errorNotification.classList.remove('show');
                }, 5000);
            }

            if (successNotification) {
                setTimeout(() => {
                    successNotification.classList.add('show');
                }, 100);

                setTimeout(() => {
                    successNotification.classList.remove('show');
                }, 5000);
            }
        });
    </script>
</body>
</html>
