<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Sale;
use App\Models\JournalEntry;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class InventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            // ────────────────────────────────────────────────
            // 1. create the product exactly as per instructions
            // ────────────────────────────────────────────────
            $product = Product::create([
                'name'           => 'Sample Product (Task 2)',
                'purchase_price' => 100.00,
                'sell_price'     => 200.00,
                'stock'          => 50,           // opening Stock: 50 units
            ]);

            $this->command->info("Product created: {$product->name} (ID: {$product->id})");

            // ────────────────────────────────────────────────
            // 2. create the sale exactly as per instructions
            // ────────────────────────────────────────────────
            $saleDate = Carbon::parse('2026-02-20'); // example date, adjust if needed

            $sale = Sale::create([
                'product_id'     => $product->id,
                'units'          => 10,
                'discount'       => 50.00,
                'vat_rate'       => 5.00,
                'payment'        => 1000.00,
                'date'           => $saleDate,
            ]);

            // calculate amounts (as per instructions logic)
            $gross     = 10 * 200.00;               // 2000
            $net       = $gross - 50.00;            // 1950
            $vat       = $net * 0.05;               // 97.50
            $totalDue  = $net + $vat;               // 2047.50
            $due       = $totalDue - 1000.00;       // 1047.50

            $sale->update([
                'gross_amount' => $gross,
                'net_amount'   => $net,
                'vat_amount'   => $vat,
                'due_amount'   => $due,
            ]);

            $this->command->info("Sale created: {$sale->units} units, Due: {$due} TK");

            // ────────────────────────────────────────────────
            // 3. create double-entry journal entries
            // ────────────────────────────────────────────────
            $cogs = 10 * 100.00; // 1000

            $journalEntries = [
                // cash & Receivable (debits)
                [
                    'sale_id'     => $sale->id,
                    'account'     => 'cash',
                    'debit'       => 1000.00,
                    'credit'      => 0,
                    'date'        => $saleDate,
                    'description' => 'Customer payment received',
                ],
                [
                    'sale_id'     => $sale->id,
                    'account'     => 'receivable',
                    'debit'       => $due,
                    'credit'      => 0,
                    'date'        => $saleDate,
                    'description' => 'Amount due from customer',
                ],

                // sales revenue & vat (credits)
                [
                    'sale_id'     => $sale->id,
                    'account'     => 'sales',
                    'debit'       => 0,
                    'credit'      => $net,
                    'date'        => $saleDate,
                    'description' => 'Net sales revenue',
                ],
                [
                    'sale_id'     => $sale->id,
                    'account'     => 'vat_payable',
                    'debit'       => 0,
                    'credit'      => $vat,
                    'date'        => $saleDate,
                    'description' => 'Output VAT 5%',
                ],

                // cogs & inventory
                [
                    'sale_id'     => $sale->id,
                    'account'     => 'cogs',
                    'debit'       => $cogs,
                    'credit'      => 0,
                    'date'        => $saleDate,
                    'description' => 'Cost of goods sold',
                ],
                [
                    'sale_id'     => $sale->id,
                    'account'     => 'inventory',
                    'debit'       => 0,
                    'credit'      => $cogs,
                    'date'        => $saleDate,
                    'description' => 'Inventory reduction',
                ],
            ];

            foreach ($journalEntries as $entry) {
                JournalEntry::create($entry);
            }

            $this->command->info('Journal entries created (double-entry accounting)');
        });

        $this->command->info('Inventory sample data seeded successfully!');
    }
}
