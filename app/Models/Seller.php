<?php

namespace App\Models;

use App\Models\Concerns\Repositories\SellerRepository;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seller extends Model
{
    use HasFactory;
    use SellerRepository;

    protected $fillable = [
        'name',
        'logo'
    ];
}
