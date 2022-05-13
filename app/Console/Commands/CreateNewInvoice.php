<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\InvoiceService;
use Illuminate\Console\Command;
use Exception;
use Illuminate\Support\Facades\DB;

class CreateNewInvoice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:newInvoice';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new invoice for each user';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            echo 'start process...' . PHP_EOL;
            $invoiceService = new InvoiceService();
            $users = User::has('expenses')->get();
            echo 'find ' . $users->count() . ' user(s)' . PHP_EOL;
            DB::transaction(function () use ($users, $invoiceService) {
                foreach ($users as $user) {
                    $invoiceService->setExpensesInvoice($user->id);
                }
            });
            return 1;
        } catch (Exception $e) {
            echo 'Erro: ' . $e->getMessage() . PHP_EOL;
            return 0;
        }
    }
}
