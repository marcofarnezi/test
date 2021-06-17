<?php

namespace App\Infrastructure\Framework\Console\Commands;

use Illuminate\Console\Command;
use App\Domain\Database\Transaction;
use App\Domain\Repository\StockRepository;
use App\Domain\Repository\ProductRepository;
use Symfony\Component\Console\Command\Command as CommandAlias;

class AddProductOnStock extends Command
{
    protected $signature = 'make:stock {product} {amount=1}';
    protected $description = 'Include a product on stock';

    private $transaction;
    private $productRepository;
    private $stockRepository;

    public function __construct(
        Transaction $transaction,
        StockRepository $stockRepository,
        ProductRepository $productRepository
    ) {
        $this->transaction = $transaction;
        $this->stockRepository = $stockRepository;
        $this->productRepository = $productRepository;
        parent::__construct();
    }

    public function handle(): int
    {
        $productId = $this->argument('product');
        if (empty($productId)) {
            $this->line('Product problem: make:stock {int:product_id} {int:amount}');
        }
        $product = $this->productRepository->find($productId);

        if (empty($product)) {
            $this->line('Product problem: Product does not exist.');
        }
        $amount = $this->argument('amount');
        $this->transaction->beginTransaction();
        try {
            $stocks = [];
            for ($i = 0; $i < $amount; ++$i) {
                $stock = $this->stockRepository->new($product->getId(), $product->getPrice());
                $stocks[] = $stock->getId();
            }
            $this->transaction->commit();

            $this->line('Success: stock@'.implode(', ', $stocks));
        } catch (\Exception $exception) {
            $this->transaction->rollBack();
        }

        return CommandAlias::SUCCESS;
    }
}
