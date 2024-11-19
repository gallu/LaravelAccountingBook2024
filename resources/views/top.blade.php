{{-- top.blade.php --}}
@extends('layout')

@section('title', 'ログイン後TopPage')

@section('content')
<h1>出納帳 ログイン後TopPage</h1>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<form action="/deposit" method="post">
@csrf
<input type="date" name="accounting_at">
<input type="text" placeholder="科目" name="subject">
<input type="text" placeholder="めも" name="detail">
<input type="text" placeholder="入金額" name="deposit_amount" value="{{ old('deposit_amount') }}">
<button class="btn btn-primary">入金登録</button>
</form>
<br>
<form action="/withdrawal" method="post">
@csrf
<input type="date" name="accounting_at">
<input type="text" placeholder="科目" name="subject">
<input type="text" placeholder="めも" name="detail">
<input type="text" placeholder="出金額" name="withdrawal_amount" value="{{ old('withdrawal_amount') }}">
<button class="btn btn-primary">出金登録</button>
</form>

@endsection
