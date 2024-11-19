<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserLoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    //
	public function index() {
		// return view('welcome');
		return view('index');
	}

	public function top() {
		// echo "top";
		// var_dump( Auth::user()->toArray() );
		return view('top');
	}

	public function login(UserLoginRequest $req) {
		// echo "ログイン";
		$credential = $req->validated();
		$credential["password"] = $credential["pw"];
		unset($credential["pw"]);

        // 認証の確認
        $r = Auth::attempt($credential);
        // var_dump($r);

        // errorなら入力画面に突っ返す
        if (false === $r) {
            return back()
                   ->withInput() // 入力値の保持
                   ->withErrors(['auth' => 'emailかパスワードに誤りがあります。',]) // エラーメッセージの出力
                   ;
        }

        // OKなら「ログイン後画面」に遷移
        $req->session()->regenerate(); // セキュリティ対策
        return redirect()->route('top');
	}

}
