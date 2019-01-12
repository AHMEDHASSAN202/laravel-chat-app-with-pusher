@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="messaging">
            <online-users-component :auth="{{ auth()->user() }}"></online-users-component>
        </div>
    </div>
@endsection