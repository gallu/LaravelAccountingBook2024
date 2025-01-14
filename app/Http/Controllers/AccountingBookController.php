<?php  // AccountingBookController.php

namespace App\Http\Controllers;

use App\Http\Requests\DepositRequest;
use App\Http\Requests\WithdrawalRequest;
use App\Models\AccountingBook as AccountingBookModel;
use App\Models\AccountingBookDeposit as AccountingBookDepositModel;
use App\Models\AccountingBookWithdrawal as AccountingBookWithdrawalModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class AccountingBookController extends Controller
{
	// Top
	public function top(Request $req) {
		//
		$d = $req->get('d', '');
		// var_dump($d);
		
		// 月を把握
		$base_at = (new \DateTime($d))->format("F Y");
		// var_dump($base_at);	
		
		// 今月の1日と末日を取得
		$fd_obj = new \DateTimeImmutable("first day of {$base_at}");
		$dStart = $fd_obj->format("Y-m-d");
		// var_dump( $dStart );
		$dLast = (new \DateTimeImmutable("last day of {$base_at}"))->format("Y-m-d");
		// var_dump( $dLast );
		// 表示用の日付
		$print_at = $fd_obj->format("Y年m月");
		
		// 前月の取得
		$last_obj = $fd_obj->sub(new \DateInterval("P1M"));
		$last_month = $last_obj->format("Y-m-d");
		// var_dump($last_month);
		// 翌月の取得
		$next_obj = $fd_obj->add(new \DateInterval("P1M"));
		$next_month = $next_obj->format("Y-m-d");
		// var_dump($next_month);
		
		//
		$acount_book_tn = (new AccountingBookModel())->getTable();
		// var_dump($acount_book_tn);
		$acount_book_deposit_tn = (new AccountingBookDepositModel())->getTable();
		// var_dump($acount_book_deposit_tn);
		$acount_book_withdrawal_tn = (new AccountingBookWithdrawalModel())->getTable();
		// var_dump($acount_book_withdrawal_tn);
		
		// var_dump( Auth::user()->id );
		$list = AccountingBookModel::query()
				->select("{$acount_book_tn}.*",
						"{$acount_book_deposit_tn}.deposit_amount",
						"{$acount_book_withdrawal_tn}.withdrawal_amount")
				->leftJoin("{$acount_book_deposit_tn}", "{$acount_book_tn}.id",
							'=', "{$acount_book_deposit_tn}.accounting_book_id")
				->leftJoin("{$acount_book_withdrawal_tn}", "{$acount_book_tn}.id"	,
							'=', "{$acount_book_withdrawal_tn}.accounting_book_id")				
				->where('user_id', Auth::user()->id)
				->whereBetween("accounting_at", [$dStart, $dLast])
				->orderBy("accounting_at")
				->orderBy("{$acount_book_tn}.id")
				->get();
		// var_dump($list->toArray());

		// \Log::debug( Auth::user()->toArray() );
		// \Log::debug( Auth::user()::class );
		$deposit_success = $req->session()->get('deposit_success', false);
		// var_dump($deposit_success);

		$withdrawal_success = $req->session()->get('withdrawal_success', false);

		$context = [
			'deposit_success' => $deposit_success,
			'withdrawal_success' => $withdrawal_success,
			'list' => $list,
			'print_at' => $print_at,
			'last_month' => $last_month,
			'next_month' => $next_month,
		];

		return view('top', $context);
	}

	// 入金
	public function deposit(DepositRequest $req) {
		return $this->accountingBookCreate($req, "depositCreate", "deposit_success");
	}

	// 出金
	public function withdrawal(WithdrawalRequest $req) {
		return $this->accountingBookCreate($req, "withdrawalCreate", "withdrawal_success");
	}

	// 出納帳入金テーブルのレコード作成
	// XXX 余裕あったら共通化
	protected function depositCreate(AccountingBookModel $abm, array $validated) {
		$abdDatum = [];
		$abdDatum['accounting_book_id'] = $abm->id;
		$abdDatum['deposit_amount'] = $validated['deposit_amount'];
		\Log::debug($abdDatum);
		$abdm = AccountingBookDepositModel::create($abdDatum);
		\Log::debug($abdm->toArray());
		
		return $abdm;
	}

	// 出納帳出金テーブルのレコード作成
	protected function withdrawalCreate(AccountingBookModel $abm, array $validated) {
		$abdDatum = [];
		$abdDatum['accounting_book_id'] = $abm->id;
		$abdDatum['withdrawal_amount'] = $validated['withdrawal_amount'];
		\Log::debug($abdDatum);
		$abdm = AccountingBookWithdrawalModel::create($abdDatum);
		\Log::debug($abdm->toArray());
		
		return $abdm;
	}

	// 入出金
	protected function accountingBookCreate(Request $req, string $createMethod, string $sessionKeyName) {
		$validated = $req->validated();
		\Log::debug('deposit', $validated);
		// exit;

		try {
			// トラン開始
			DB::beginTransaction();

			// 出納帳テーブル
			$abDatum = [];
			$abDatum['user_id'] = Auth::user()->id;
			$abDatum['accounting_at'] = $validated['accounting_at'];
			$abDatum['subject'] = $validated['subject'];
			$abDatum['detail'] = $validated['detail'] ?? '';
			\Log::debug($abDatum);
			$abm = AccountingBookModel::create($abDatum);
			\Log::debug($abm->toArray());

			// 出納帳入出金テーブル
			$abdm = $this->$createMethod($abm, $validated);

			// コミット
			DB::commit();
		} catch (\RuntimeException $e) {
			// ロールバック
			DB::rollBack();
			\Log::debug($e->getMessage());
		}
		
		// echo 'おわた';
		$req->session()->flash($sessionKeyName, true);
		// return redirect('/top');
		return redirect()->route('top');
	}

}
