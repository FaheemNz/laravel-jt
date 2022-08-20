<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Transaction;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Transaction::create([
            'amount' => 50.5,
            'currency_id' => 1,
            'source' => 'creditcard',
            'transaction_details' => "{}",
            'status' => 'settled',
            'ref_no' => '2234.',
            'user_id' => 18,
        ]);
        Transaction::create([
            'amount' => 500,
            'currency_id' => 87,
            'source' => 'creditcard',
            'transaction_details' => "{}",
            'status' => 'settled',
            'ref_no' => '223451',
            'user_id' => 18,
        ]);
        Transaction::create([
            'amount' => 500,
            'currency_id' => 87,
            'source' => 'creditcard',
            'transaction_details' => "{}",
            'status' => 'settled',
            'ref_no' => '22342',
            'user_id' => 18,
        ]);

        Transaction::create([
            'amount' => 500,
            'currency_id' => 1,
            'source' => 'creditcard',
            'transaction_details' => "{}",
            'status' => 'settled',
            'ref_no' => '32343',
            'user_id' => 18,
        ]);
        Transaction::create([
            'amount' => 500,
            'currency_id' => 87,
            'source' => 'creditcard',
            'transaction_details' => "{}",
            'status' => 'settled',
            'ref_no' => '32344',
            'user_id' => 18,
        ]);
        Transaction::create([
            'amount' => 500,
            'currency_id' => 87,
            'source' => 'creditcard',
            'transaction_details' => "{}",
            'status' => 'settled',
            'ref_no' => '332345',
            'user_id' => 18,
        ]);
    }
}
