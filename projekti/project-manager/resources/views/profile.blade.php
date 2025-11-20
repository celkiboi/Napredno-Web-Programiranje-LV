<x-app-layout>
    <h2>Your Managed Projects</h2>
    @foreach ($managed as $p)
        <p>{{ $p->name }}</p>
    @endforeach

    <h2>Projects You Are a Team Member Of</h2>
    @foreach ($team as $p)
        <p>{{ $p->name }}</p>
    @endforeach

</x-app-layout>