{{-- index.blade.php --}}
@extends('layout')

@section('title', 'TopPage')

@section('content')
<h1>出納帳</h1>
<a href="/register">ユーザ登録</a><br>
<br>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="/login" method="post">
@csrf
  email:<input type="text" name="email" value="{{ old('email') }}"><br>
  pass:<input type="password" name="pw"><br>
  <button class="btn btn-primary">ログイン</button>
</form>
@endsection