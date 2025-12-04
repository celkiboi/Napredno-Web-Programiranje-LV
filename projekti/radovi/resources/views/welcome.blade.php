<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <linkpreconnect="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-gray-50 text-gray-800">

    <div class="relative min-h-screen flex flex-col items-center justify-center selection:bg-red-500 selection:text-white">

    <div class="fixed top-0 left-0 w-full z-50 bg-gray-50/90 backdrop-blur-sm border-b border-gray-200">
    
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            
            <div class="flex items-center shrink-0">
                <svg class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                <span class="ml-2 font-bold text-xl text-gray-700">TMS</span>
            </div>

            <div class="flex items-center gap-6">
                
                <div class="text-sm font-medium">
                    <a href="/lang/hr" class="{{ App::getLocale() == 'hr' ? 'text-blue-600 font-bold' : 'text-gray-500 hover:text-gray-900' }}">HR</a>
                    <span class="text-gray-300 mx-2">|</span>
                    <a href="/lang/en" class="{{ App::getLocale() == 'en' ? 'text-blue-600 font-bold' : 'text-gray-500 hover:text-gray-900' }}">EN</a>
                </div>

                @if (Route::has('login'))
                    <div class="flex items-center gap-4">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="font-semibold text-gray-600 hover:text-gray-900">{{ __('Dashboard') }}</a>
                        @else
                            <a href="{{ route('login') }}" class="font-semibold text-gray-600 hover:text-gray-900">{{ __('Login') }}</a>

                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="font-semibold text-white bg-blue-600 px-4 py-2 rounded-md hover:bg-blue-700 transition shadow-sm">
                                    {{ __('Register') }}
                                </a>
                            @endif
                        @endauth
                    </div>
                @endif
            </div>
        </div>
    </div>

        <div class="max-w-7xl mx-auto p-6 lg:p-8 w-full">
            
            <div class="text-center py-16">
                <h1 class="text-5xl font-extrabold text-gray-900 tracking-tight sm:text-6xl mb-6">
                    {{ __('Welcome Title') }}
                </h1>
                <p class="mt-4 text-xl text-gray-500 max-w-2xl mx-auto">
                    {{ __('Welcome Subtitle') }}
                </p>
                
                @guest
                <div class="mt-8">
                    <a href="{{ route('login') }}" class="inline-block bg-blue-600 border border-transparent rounded-md py-3 px-8 font-medium text-white hover:bg-blue-700 transition duration-150 ease-in-out">
                        {{ __('Get Started') }}
                    </a>
                </div>
                @endguest
            </div>

            <div class="mt-16 grid grid-cols-1 md:grid-cols-2 gap-8">
                
                <div class="relative group bg-white p-6 focus-within:ring-2 focus-within:ring-inset focus-within:ring-blue-500 rounded-2xl shadow-sm border border-gray-200 hover:shadow-lg transition duration-300">
                    <div>
                        <span class="rounded-lg inline-flex p-3 bg-blue-50 text-blue-700 ring-4 ring-white">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
                            </svg>
                        </span>
                    </div>
                    <div class="mt-8">
                        <h3 class="text-lg font-semibold leading-6 text-gray-900">
                            <a href="{{ route('register') }}" class="focus:outline-none">
                                <span class="absolute inset-0" aria-hidden="true"></span>
                                {{ __('For Students') }}
                            </a>
                        </h3>
                        <p class="mt-2 text-sm text-gray-500">
                            {{ __('Student Desc') }}
                        </p>
                    </div>
                </div>

                <div class="relative group bg-white p-6 focus-within:ring-2 focus-within:ring-inset focus-within:ring-blue-500 rounded-2xl shadow-sm border border-gray-200 hover:shadow-lg transition duration-300">
                    <div>
                        <span class="rounded-lg inline-flex p-3 bg-indigo-50 text-indigo-700 ring-4 ring-white">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                            </svg>
                        </span>
                    </div>
                    <div class="mt-8">
                        <h3 class="text-lg font-semibold leading-6 text-gray-900">
                            <a href="{{ route('login') }}" class="focus:outline-none">
                                <span class="absolute inset-0" aria-hidden="true"></span>
                                {{ __('For Professors') }}
                            </a>
                        </h3>
                        <p class="mt-2 text-sm text-gray-500">
                            {{ __('Professor Desc') }}
                        </p>
                    </div>
                </div>

            </div>

            <div class="mt-16 flex justify-center text-sm text-gray-500">
                &copy; {{ date('Y') }} Thesis Management System. All rights reserved.
            </div>
        </div>
    </div>
</body>
</html>