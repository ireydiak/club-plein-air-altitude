@extends('layout.app')

@section('content')
    <user-form-component
        :model="{{ $model }}"
    ></user-form-component>
@endsection
