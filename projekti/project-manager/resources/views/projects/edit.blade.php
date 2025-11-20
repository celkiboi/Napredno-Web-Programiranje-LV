<x-app-layout>
    <h1>Edit Project</h1>

    <form method="POST" action="{{ route('projects.update', $project) }}">
        @csrf
        @method('PUT')

        @if (auth()->id() === $project->user_id)
            <!-- manager sees all inputs -->
            <input type="text" name="name" value="{{ $project->name }}"><br>
            <textarea name="description">{{ $project->description }}</textarea><br>
            <input type="number" step="0.01" name="price" value="{{ $project->price }}"><br>
        @endif

        <!-- everyone can update completed_work -->
        <textarea name="completed_work">{{ $project->completed_work }}</textarea><br>

        @if (auth()->id() === $project->user_id)
            <label>Team members:</label><br>
            @foreach ($users as $user)
                <input type="checkbox" name="team_members[]" value="{{ $user->id }}"
                    @checked($project->teamMembers->contains($user->id))>
                {{ $user->name }}<br>
            @endforeach
        @endif

        <button type="submit">Save</button>
    </form>
</x-app-layout>
