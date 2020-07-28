@extends('layouts.app')

@section('css')
	<link rel="stylesheet" href="{{asset('css/chat.css')}}">
@endsection

@section('content')
	<div class="container">
		<div class="justify-content-center rtl">
			<chat-application></chat-application>
		</div>
	</div>
@endsection

@section('js')
	<script src="{{asset('js/chat.js')}}"></script>
@endsection
