<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountTransactionsTable extends Migration
{
    public function up()
    {
        Schema::create('account_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->decimal('amount', 15, 2);
            $table->enum('type', ['debit', 'credit']); // debit or credit
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('account_transactions');
    }
}
