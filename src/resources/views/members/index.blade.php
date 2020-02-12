@extends('layout.app')

@section('content')

    @if ($members != null && count($members) > 0)
        <member-table :users="{{ $members }}"></member-table>
    @else
        <p>Aucun membre à afficher.</p>
    @endif

@endsection
