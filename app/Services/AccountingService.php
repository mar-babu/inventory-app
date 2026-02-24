<?php

namespace App\Services;

use App\Models\Sale;
use App\Models\Product;
use App\Models\JournalEntry;

class AccountingService
{
    public function createSaleJournals(Sale $sale, Product $product)
    {
        $cogs = $sale->units * $product->purchase_price;

        $entries = [
            // debit Cash / receivable
            ['account' => 'cash',         'debit' => $sale->payment, 'credit' => 0, 'description' => 'Payment received'],
            ['account' => 'receivable',   'debit' => $sale->due_amount, 'credit' => 0, 'description' => 'Amount due'],

            // credit revenue & vat
            ['account' => 'sales',        'debit' => 0, 'credit' => $sale->net_amount, 'description' => 'Net sales revenue'],
            ['account' => 'vat_payable',  'debit' => 0, 'credit' => $sale->vat_amount, 'description' => 'Output VAT'],

            // cogs & inventory
            ['account' => 'cogs',         'debit' => $cogs, 'credit' => 0, 'description' => 'Cost of goods sold'],
            ['account' => 'inventory',    'debit' => 0, 'credit' => $cogs, 'description' => 'Inventory reduction'],
        ];

        foreach ($entries as $entry) {
            JournalEntry::create([
                'sale_id'     => $sale->id,
                'account'     => $entry['account'],
                'debit'       => $entry['debit'],
                'credit'      => $entry['credit'],
                'date'        => $sale->date,
                'description' => $entry['description'],
            ]);
        }
    }
}