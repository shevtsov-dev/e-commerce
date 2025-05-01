<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int    $id
 * @property string $currency
 * @property float  $rate
 */
class CurrencyRate extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'currency_rates';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'currency',
        'rate',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'rate' => 'float',
    ];
}
