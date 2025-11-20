<x-app-layout>
    <h1>Create Project</h1>

    <form method="POST" action="{{ route('projects.store') }}">
        @csrf

        <input type="text" name="name" placeholder="Project Name"><br>
        <textarea name="description" placeholder="Description"></textarea><br>
        <input type="number" step="0.01" name="price" placeholder="Price"><br>
        <textarea name="completed_work" placeholder="Completed Work"></textarea><br>
        <input type="date" name="start_date"><br>
        <input type="date" name="end_date"><br>

        <label>Team members:</label><br>
        @foreach ($users as $user)
            <input type="checkbox" name="team_members[]" value="{{ $user->id }}"> {{ $user->name }} <br>
        @endforeach

        <button type="submit">Save</button>
    </form>
</x-app-layout>
