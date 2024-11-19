<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
		// 出納帳テーブル
        Schema::create('accounting_books', function (Blueprint $table) {
            $table->id();
			// XXX user_id
			$table->date('accounting_at')->comment('入出金日');
			$table->string('subject', length: 255)->comment('科目');
			$table->text('detail')->comment('詳細/めも');
            $table->datetimes();
			$table->comment('1レコードが「1つの取引(入金または出金)」を意味するテーブル');
        });
		// 出納帳入金テーブル
        Schema::create('accounting_book_deposits', function (Blueprint $table) {
            $table->id();
			$table->foreignId('accounting_book_id')->constrained();
			$table->integer('deposit_amount')->comment('入金額');
            $table->datetimes();
			$table->comment('1レコードが「1つの取引の入金情報」を意味するテーブル');
        });
		// 出納帳出金テーブル
        Schema::create('accounting_book_withdrawals', function (Blueprint $table) {
            $table->id();
			$table->foreignId('accounting_book_id')->constrained();
			$table->integer('withdrawal_amount')->comment('出金額');
            $table->datetimes();
			$table->comment('1レコードが「1つの取引の出金情報」を意味するテーブル');
        });
    }

    /**
        Schema::create('accounting_books', function (Blueprint $table) {
            $table->id();
            $table->datetimes();
        });
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounting_book_withdrawals');
        Schema::dropIfExists('accounting_book_deposits');
        Schema::dropIfExists('accounting_books');
    }
};
