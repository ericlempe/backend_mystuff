<?php

use App\Models\{Attachment, Expense, Invoice};
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expense_invoice', function (Blueprint $table) {
            $table->id();
            $table->decimal("value", 8, 2)->nullable();
            $table->dateTime("paid_in")->nullable();
            $table->foreignIdFor(Expense::class)->constrained();
            $table->foreignIdFor(Invoice::class)->constrained();
            $table->foreignIdFor(Attachment::class)->nullable()->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('expense_invoices');
    }
};
