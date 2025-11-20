<x-app-layout>
    <h1>Your Managed Projects</h1>

    @foreach ($projects as $project)
        <p>
            {{ $project->name }} —
            <a href="{{ route('projects.edit', $project) }}">Edit</a>
        </p>
    @endforeach

    <a href="{{ route('projects.create') }}">Create New Project</a>
</x-app-layout>