<?php

namespace App\Models;

use App\Constants\TransactionStatuses;
use App\Models\Concerns\HasStatus;
use App\Models\Concerns\Repositories\TransactionRepository;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class Transaction extends Model
{
    use HasFactory;
    use HasStatus;
    use TransactionRepository;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function shoppingCarItems(): HasMany
    {
        return $this->hasMany(ShoppingCarItem::class);
    }

    public static function pendingPayments(string $date): Collection
    {
        return self::query()
            ->where('status', TransactionStatuses::PENDING)
            ->where('updated_at', '<=', $date)
            ->get();
    }
}
