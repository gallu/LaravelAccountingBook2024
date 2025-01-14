{{-- top.blade.php --}}
@extends('layout')

@section('title', 'ログイン後TopPage')

@section('content')
<h1>出納帳 ログイン後TopPage</h1>

@if ($deposit_success == true) 
  <div class="alert alert-success" role="alert">
    入金が記録されました。
  </div>
@endif

@if ($withdrawal_success == true) 
  <div class="alert alert-primary" role="alert">
    出金が記録されました。
  </div>
@endif

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

<hr>
<h2>{{ $print_at }} の出納帳</h2>

<a href="/top?d={{ $next_month }}">翌月</a> 
|
<a href="/top?d={{ $last_month }}">前月</a> 
<table class="table table-striped">
<tr>
  <th>日時
  <th>科目
  <th>めも
  <th>入金額
  <th>出金額
@foreach ($list as $datum)
<tr>
  <td>{{ $datum->accounting_at }}
  <td>{{ $datum->subject }}
  <td>{{ $datum->detail }}
  <td>{{ $datum->deposit_amount }}
  <td>{{ $datum->withdrawal_amount }}
@endforeach

</table>








@endsection
