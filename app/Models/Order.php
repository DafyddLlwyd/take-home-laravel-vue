<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total_price',
        'status',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getPdfAssetUrlAttribute()
    {
        $filePath = 'pdfs/' . $this->id . '_invoice.pdf';

        // Use 'public' disk to generate the public URL
        return Storage::disk('public')->url($filePath);
    }
}
