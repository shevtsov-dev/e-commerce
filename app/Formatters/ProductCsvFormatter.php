<?php

declare(strict_types=1);

namespace App\Formatters;

use App\Formatters\Interfaces\DataFormatterInterface;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

class ProductCsvFormatter implements DataFormatterInterface
{
    /**
     * @const HEADERS
     */
    private const HEADERS = ['ID', 'Name', 'Price'];

    /**
     * @const DELIMITER
     */
    private const DELIMITER = ';';

    /**
     * @param Collection<int, Product> $products
     *
     * @return string
     */
    public function format(Collection $products): string
    {
        $csvData = implode(self::DELIMITER, self::HEADERS) . "\n";

        foreach ($products as $product) {
            $csvData .= implode(self::DELIMITER, [
                    $product->id,
                    $product->name,
                    $product->price,
                ]) . "\n";
        }

        return $csvData;
    }
}
