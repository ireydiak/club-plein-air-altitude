@extends('layout.app')

@section('content')
    <user-form-component
        :model="{{ json_encode($member->form()) }}"
        :member="{{ json_encode($member) }}"
        :button-text="'Modifier'"
        :method="'update'"
    ></user-form-component>
@endsection
