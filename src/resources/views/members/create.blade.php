@extends('layout.app')

@section('content')
    <user-form-component
        :model="{{ $model }}"
        :member="null"
        :button-text="'Créer'"
        :method="'store'"
    ></user-form-component>
@endsection
