<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Available Tasks') }}
            </h2>
            <div>
                <span class="text-gray-500">{{ __('Language') }}:</span>
                <a href="/lang/hr" class="text-blue-600 hover:underline">HR</a> |
                <a href="/lang/en" class="text-blue-600 hover:underline">EN</a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            @foreach($tasks as $task)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-4 border border-gray-200">
                <div class="flex justify-between items-start">
                    <div class="w-3/4">
                        <h3 class="text-xl font-bold text-gray-800">{{ $task->name }}</h3>
                        
                        <p class="text-sm text-blue-600 font-semibold mt-1">
                            {{ __('Professor') }}: {{ $task->professor->name }}
                        </p>

                        <p class="text-xs text-gray-500 uppercase tracking-wide mt-1">
                            {{ __('Study Type') }}: {{ __($task->study_type->value) }}
                        </p>

                        <p class="text-gray-600 mt-3 text-sm leading-relaxed">{{ $task->description }}</p>
                    </div>
                    
                    <div class="ml-4">
                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-50 text-blue-700 border border-blue-200">
                            {{ __($task->study_type->value) }}
                        </span>
                    </div>
                </div>

                @php
                    $applicationCount = Auth::user()->applications->count();
                    $myApp = Auth::user()->applications->where('task_id', $task->id)->first();
                    $hasThesis = Auth::user()->assignedTask()->exists();
                @endphp

                <div class="mt-6 pt-4 border-t border-gray-100">
                    <form action="{{ route('applications.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="task_id" value="{{ $task->id }}">

                        @if($myApp)
                            <span class="text-green-600 font-bold border border-green-600 px-4 py-2 rounded inline-block text-sm">
                                ✓ {{ __('Applied') }} - {{ __('Priority') }}: {{ $myApp->priority }}
                            </span>
                        @elseif($hasThesis)
                            <button type="button" disabled class="bg-green-100 text-green-800 border border-green-300 px-4 py-2 rounded cursor-not-allowed text-sm font-medium">
                                {{ __('You already have a thesis.') }}
                            </button>

                        @elseif($applicationCount >= 5)
                            <button type="button" disabled class="bg-gray-300 text-white px-4 py-2 rounded cursor-not-allowed text-sm font-medium">
                                {{ __('Application Limit Reached') }}
                            </button>

                        @else
                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition shadow-sm text-sm font-medium">
                                {{ __('Apply') }} 
                                ({{ __('Rank') }} {{ $applicationCount + 1 }})
                            </button>
                        @endif
                    </form>
                </div>
            </div>
            @endforeach

        </div>
    </div>
</x-app-layout>