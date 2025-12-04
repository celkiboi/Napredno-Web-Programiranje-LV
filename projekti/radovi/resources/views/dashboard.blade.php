<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @role('teacher')
                <div class="flex justify-end mb-6">
                    <a href="{{ route('tasks.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700">
                        + {{ __('Create New Task') }}
                    </a>
                </div>

                <h3 class="text-lg font-bold mb-4">{{ __('My Tasks') }}</h3>

                @foreach($myTasks as $task)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 p-6 border-l-4 {{ $task->assigned_student_id ? 'border-green-500' : 'border-blue-500' }}">
                        
                        <div class="flex justify-between items-start">
                            <div>
                                <h4 class="text-xl font-bold">{{ $task->name }}</h4> <p class="text-gray-600">{{ $task->description }}</p>
                            </div>
                            @if($task->assigned_student_id)
                                <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded">
                                    {{ __('Assigned') }}
                                </span>
                            @else
                                <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded">
                                    {{ __('Open') }}
                                </span>
                            @endif
                        </div>

                        <div class="mt-4 bg-gray-50 p-4 rounded">
                            <h5 class="font-bold text-sm text-gray-500 uppercase mb-2">{{ __('Applicants') }}</h5>
                            
                            @if($task->applications->isEmpty())
                                <p class="text-sm text-gray-400 italic">{{ __('No applicants yet.') }}</p>
                            @else
                                <ul class="space-y-2">
                                    @foreach($task->applications as $app)
                                        <li class="flex justify-between items-center bg-white p-2 rounded shadow-sm">
                                            <span>
                                                <strong>{{ $app->student->name }}</strong> 
                                                <span class="text-xs text-gray-500">({{ $app->student->email }})</span>
                                            </span>
                                            
                                            <div class="flex items-center gap-4">
                                                <span class="px-2 py-1 text-xs rounded {{ $app->priority == 1 ? 'bg-yellow-100 text-yellow-800 font-bold border border-yellow-300' : 'bg-gray-100 text-gray-600' }}">
                                                    {{ __('Priority') }}: {{ $app->priority }}
                                                </span>

                                                @if(!$task->assigned_student_id)
                                                    @if($app->priority === 1)
                                                        <form action="{{ route('tasks.accept', ['task' => $task->id, 'student' => $app->student->id]) }}" method="POST">
                                                            @csrf
                                                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white text-xs px-3 py-1 rounded transition">
                                                                {{ __('Accept') }}
                                                            </button>
                                                        </form>
                                                    @else
                                                        <span class="text-xs text-gray-400 cursor-not-allowed" title="{{ __('Only First Priority') }}">
                                                            {{ __('Wait for Priority 1') }}
                                                        </span>
                                                    @endif
                                                @endif
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                @endforeach
            @endrole

            @role('student')
                <div class="flex justify-end mb-6">
                    <a href="{{ route('tasks.index') }}" class="bg-indigo-600 text-white px-4 py-2 rounded shadow hover:bg-indigo-700">
                        {{ __('Browse Available Tasks') }}
                    </a>
                </div>

                @if($assignedTask)
                    <div class="bg-green-50 border-l-4 border-green-500 p-6 shadow-sm rounded-r-lg">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg leading-6 font-medium text-green-900">
                                    {{ __('Congratulations!') }}
                                </h3>
                                <p class="text-sm text-green-700 mt-1">
                                    {{ __('You have been accepted for the following thesis subject:') }}
                                </p>
                            </div>
                        </div>

                        <div class="mt-6 bg-white p-6 rounded shadow border border-green-200">
                            <h2 class="text-2xl font-bold text-gray-800">{{ $assignedTask->name }}</h2>
                            <p class="text-gray-600 mt-2">{{ $assignedTask->description }}</p>
                            
                            <div class="mt-4 flex gap-4 text-sm text-gray-500">
                                <span><strong>{{ __('Professor') }}:</strong> {{ $assignedTask->professor->name }}</span>
                                <span><strong>{{ __('Email') }}:</strong> {{ $assignedTask->professor->email }}</span>
                            </div>
                            
                            <div class="mt-4">
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 uppercase tracking-wide">
                                    {{ __($assignedTask->study_type->value) }}
                                </span>
                            </div>
                        </div>
                    </div>
                @else

                <h3 class="text-lg font-bold mb-4">{{ __('My Application Status') }}</h3>

                @if($myApplications->isEmpty())
                    <div class="bg-white p-6 rounded shadow text-center text-gray-500">
                        {{ __('You have not applied to any subjects yet.') }}
                    </div>
                @else
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Priority') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Task Name') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Professor') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Status') }}</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($myApplications as $app)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                                        #{{ $app->priority }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                        {{ $app->task->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $app->task->professor->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($app->task->assigned_student_id == Auth::id())
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                {{ __('Accepted') }}
                                            </span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                {{ __('Pending') }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <form action="{{ route('applications.destroy', $app->id) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure?') }}');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 font-bold hover:underline">
                                                {{ __('Cancel') }}
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    </div>
                @endif
            @endif
            @endrole

            @role('admin')
                <div class="bg-white p-6 rounded shadow">
                    Welcome Admin
                </div>
            @endrole

        </div>
    </div>
</x-app-layout>