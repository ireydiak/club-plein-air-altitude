@extends('layout.app')

@section('content')

    @if ($members != null && count($members) > 0)
        @foreach($members as $member)
            <p>{{ $member->firstName }}</p>
        @endforeach
    @else
        <p>Aucun membre à afficher.</p>
    @endif

    <example-component></example-component>

@endsection


