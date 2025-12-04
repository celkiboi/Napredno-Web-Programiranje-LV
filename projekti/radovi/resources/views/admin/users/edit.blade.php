<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit User') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <form action="{{ route('admin.users.update', $user) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700">{{ __('Name') }}</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full" required>
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700">{{ __('Email') }}</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full" required>
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700 mb-2">{{ __('Role') }}</label>
                        <select name="role" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full">
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                                    {{ ucfirst($role->name) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <a href="{{ route('admin.users.index') }}" class="underline text-sm text-gray-600 hover:text-gray-900 mr-4">
                            {{ __('Cancel') }}
                        </a>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
                            {{ __('Update') }}
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>