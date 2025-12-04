<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create New Task') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <form method="POST" action="{{ route('tasks.store') }}">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="block font-bold">{{ __('Task Name (HR)') }}</label>
                        <input type="text" name="name_hr" class="w-full border-gray-300 rounded" required>
                    </div>
                    <div class="mb-4">
                        <label class="block font-bold">{{ __('Description (HR)') }}</label>
                        <textarea name="description_hr" class="w-full border-gray-300 rounded" required></textarea>
                    </div>

                    <hr class="my-4">

                    <div class="mb-4">
                        <label class="block font-bold">{{ __('Task Name (EN)') }}</label>
                        <input type="text" name="name_en" class="w-full border-gray-300 rounded" required>
                    </div>
                    <div class="mb-4">
                        <label class="block font-bold">{{ __('Description (EN)') }}</label>
                        <textarea name="description_en" class="w-full border-gray-300 rounded" required></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="block font-bold">{{ __('Study Type') }}</label>
                        <select name="study_type" class="w-full border-gray-300 rounded">
                            @foreach($types as $type)
                                <option value="{{ $type->value }}">{{ $type->label() }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">
                        {{ __('Create') }}
                    </button>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>