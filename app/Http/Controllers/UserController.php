<?php  // UserController.php

namespace App\Http\Controllers;

use App\Http\Requests\UserRegisterRequest;
use App\Models\User as UserModel;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
// use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    //
	public function register() {
		return view('register/index');
	}

	public function registerPost(UserRegisterRequest $req) {
		$valid = $req->validated();
		// var_dump($valid);

		try {
			$datum = [];
			$datum["name"] = $valid["name"];
			$datum["email"] = $valid["email"];
			$datum["password"] = Hash::make($valid["pw"]);
			// var_dump($datum);

			$obj = UserModel::create($datum);

			// email確認を成功させる
			$obj->email_verified_at = date(DATE_ATOM);
			$obj->save();
		} catch (\RuntimeException $e) {
			// echo $e->getMessage();
			\Log::notice($e->getMessage());
			echo "登録エラー";
			return;
		}

		// 登録完了ページに遷移
		// return redirect('/register/fin');
		return redirect()->route('register.fin');
	}

	public function registerFin() {
		return view('register/fin');
	}

}






