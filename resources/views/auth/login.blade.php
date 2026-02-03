<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>เข้าสู่ระบบ - ระบบงานธุรการโรงเรียนพลาธิการ กรมพลาธิการทหารเรือ</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=sarabun:300,400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Sarabun', sans-serif; }
        .glass-panel {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }
        .anim-entry {
            animation: fadeUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards;
            opacity: 0;
            transform: translateY(20px);
        }
        @keyframes fadeUp {
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="antialiased h-screen w-full overflow-hidden flex items-center justify-center relative bg-gray-900">
    
    <!-- Dynamic Background -->
    <div class="absolute inset-0 z-0 select-none">
        <div class="absolute inset-0 bg-[url('https://upload.wikimedia.org/wikipedia/commons/thumb/d/d7/HTMS_Chakri_Naruebet_CVH-911.jpg/1200px-HTMS_Chakri_Naruebet_CVH-911.jpg')] bg-cover bg-center transform scale-105 transition duration-[30s] hover:scale-100 opacity-40"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-blue-900 via-blue-900/40 to-transparent mix-blend-multiply"></div>
        <div class="absolute inset-0 bg-gradient-to-b from-black/30 to-blue-900/60"></div>
        
        <!-- Decorative Elements -->
        <div class="absolute top-0 left-0 w-full h-[50vh] bg-gradient-to-b from-blue-800/20 to-transparent"></div>
    </div>

    <!-- Main Container -->
    <div class="relative z-10 w-full max-w-md px-6 anim-entry">
        <!-- Logo Section -->
        <div class="text-center mb-8">
            <div class="relative inline-block w-28 h-28 mb-4">
                <div class="absolute inset-0 bg-white/20 rounded-full blur-xl animate-pulse"></div>
                <img class="relative w-full h-full drop-shadow-2xl filter brightness-110" src="{{ asset('images/logonavy.png') }}" alt="Royal Thai Navy Logo">
            </div>
            <h1 class="text-3xl font-bold text-white drop-shadow-md tracking-wide">ระบบงานธุรการ</h1>
            <div class="flex items-center justify-center gap-2 mt-2 opacity-90">
                <span class="h-px w-8 bg-blue-300"></span>
                <p class="text-blue-200 text-sm font-light tracking-wider uppercase">Naval Supply School</p>
                <span class="h-px w-8 bg-blue-300"></span>
            </div>
        </div>

        <!-- Glass Panel Card -->
        <div class="glass-panel shadow-[0_8px_32px_rgba(0,0,0,0.4)] rounded-2xl overflow-hidden border border-white/20 relative">
            
            <!-- Glow Effect -->
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-blue-500/20 rounded-full blur-2xl"></div>
            <div class="absolute -bottom-10 -left-10 w-32 h-32 bg-indigo-500/20 rounded-full blur-2xl"></div>

            <div class="px-8 py-10 relative z-10">
                @if (session('status'))
                    <div class="mb-6 flex items-center gap-3 text-sm font-medium text-green-700 bg-green-50/80 border border-green-200 p-3 rounded-lg shadow-sm">
                        <svg class="w-5 h-5 flex-shrink-0 text-green-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <div class="space-y-1">
                        <label for="email" class="block text-sm font-medium text-gray-700 ml-1">อีเมลทางการทหาร</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none transition-colors group-focus-within:text-blue-600">
                                <svg class="h-5 w-5 text-gray-400 group-focus-within:text-blue-500 transition-colors" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                </svg>
                            </div>
                            <input id="email" name="email" type="email" autocomplete="email" required 
                                class="block w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent focus:bg-white transition-all duration-200 sm:text-sm" 
                                placeholder="example@navy.mi.th" value="{{ old('email') }}">
                        </div>
                        @error('email')
                            <p class="mt-1 text-xs text-red-500 ml-1 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="space-y-1">
                        <label for="password" class="block text-sm font-medium text-gray-700 ml-1">รหัสผ่าน</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400 group-focus-within:text-blue-500 transition-colors" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <input id="password" name="password" type="password" autocomplete="current-password" required 
                                class="block w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent focus:bg-white transition-all duration-200 sm:text-sm" 
                                placeholder="••••••••">
                        </div>
                        @error('password')
                            <p class="mt-1 text-xs text-red-500 ml-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between pt-2">
                        <div class="flex items-center">
                            <input id="remember_me" name="remember" type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded cursor-pointer">
                            <label for="remember_me" class="ml-2 block text-sm text-gray-600 cursor-pointer select-none group hover:text-gray-900 transition-colors">
                                จดจำการใช้งาน
                            </label>
                        </div>

                        @if (Route::has('password.request'))
                            <div class="text-sm">
                                <a href="{{ route('password.request') }}" class="font-medium text-blue-600 hover:text-blue-800 transition-colors underline-offset-2 hover:underline">
                                    ลืมรหัสผ่าน?
                                </a>
                            </div>
                        @endif
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full relative flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-lg shadow-blue-500/30 text-sm font-bold text-white bg-gradient-to-r from-blue-700 to-blue-900 hover:from-blue-800 hover:to-blue-950 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-300 transform hover:-translate-y-0.5 active:scale-[0.98]">
                            เข้าสู่ระบบ
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Footer -->
            <div class="px-6 py-4 bg-gray-50/50 border-t border-gray-100 text-center text-[10px] text-gray-400 uppercase tracking-widest font-semibold">
                Royal Thai Navy - Administration System &copy; {{ date('Y') }}
            </div>
        </div>
        
        <!-- Bottom Text -->
        <p class="text-center text-blue-200/40 text-xs mt-6 font-light">
            ออกแบบและพัฒนาระบบโดย จ.ท.ธีร์ธวัช  พิพัฒน์เดชธน
        </p>
    </div>
</body>
</html>
