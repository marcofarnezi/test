<?php

namespace App\Infrastructure\Framework\Console\Commands;

use App\Domain\Repository\ProductRepository;
use Illuminate\Console\Command;
use Symfony\Component\Console\Command\Command as CommandAlias;

class AddProduct extends Command
{
    protected $signature = 'make:product {title} {price} {--description=}';
    protected $description = 'Include a new product';

    private $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
        parent::__construct();
    }

    public function handle(): int
    {
        $title = $this->argument('title');
        if (empty($title)) {
            $this->line('Title problem: make:product {string:title} {int:price} {string|null:description}');
        }

        $price = $this->argument('price');
        if (empty($price) || !is_int((int)$price)) {
            $this->line('Price problem: make:product {string:title} {int:price} {string|null:description}');
        }

        $description = $this->option('description');

        $product = $this->productRepository->new($title, $price, $description);

        $this->line('Success: product@'.$product->getId());
        return CommandAlias::SUCCESS;
    }
}
