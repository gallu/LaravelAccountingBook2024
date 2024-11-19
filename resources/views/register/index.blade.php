{{-- register/index.blade.php --}}
@extends('layout')

@section('title', 'ユーザ登録')

@section('content')
<h1>出納帳 ユーザ登録</h1>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="/register" method="post">
@csrf
  お名前:<input type="text" name="name" value="{{ old('name') }}"><br>
  email:<input type="text" name="email" value="{{ old('email') }}"><br>
  pass:<input type="password" name="pw"><br>
  pass(再):<input type="password" name="pw_confirmation"><br>
  <button>登録</button>
</form>
@endsection