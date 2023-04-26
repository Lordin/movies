@extends('layouts.app')

@section('content')
<div class="container">

    <movies :current-user="{{$currentUser}}"></movies>

</div>
@endsection
