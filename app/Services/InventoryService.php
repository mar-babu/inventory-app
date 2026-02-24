<?php

namespace App\Services;

use App\Repositories\ProductInterface;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;

class InventoryService
{
    public function __construct(
        protected ProductInterface $productRepository,
        protected AccountingService $accountingService
    ) {}

    public function recordSale(array $data): Sale
    {
        return DB::transaction(function () use ($data) {
            $product = $this->productRepository->findById($data['product_id']);

            if (!$product || $product->stock < $data['units']) {
                throw new \Exception('Insufficient stock');
            }

            // create sale record
            $sale = Sale::create([
                'product_id'     => $data['product_id'],
                'units'          => $data['units'],
                'discount'       => $data['discount'] ?? 0,
                'vat_rate'       => 5,
                'payment'        => $data['payment'] ?? 0,
                'date'           => now(),
            ]);

            // calculate amounts
            $gross = $sale->units * $product->sell_price;
            $net   = $gross - $sale->discount;
            $vat   = $net * ($sale->vat_rate / 100);
            $total = $net + $vat;
            $due   = $total - $sale->payment;

            $sale->update([
                'gross_amount' => $gross,
                'net_amount'   => $net,
                'vat_amount'   => $vat,
                'due_amount'   => $due,
            ]);

            // update stock
            $this->productRepository->updateStock($product->id, -$sale->units);

            // create journal entries
            $this->accountingService->createSaleJournals($sale, $product);

            return $sale;
        });
    }

}
