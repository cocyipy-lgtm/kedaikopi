<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    public const CATEGORIES = ['Kopi', 'Non-Kopi', 'Makanan'];

    protected $fillable = [
        'name',
        'category',
        'description',
        'price',
        'image_path',
        'is_available',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'integer',
            'is_available' => 'boolean',
        ];
    }

    protected function imageUrl(): Attribute
    {
        return Attribute::get(fn (): string => match (true) {
            blank($this->image_path) => asset('images/main-1.jpg'),
            str_starts_with($this->image_path, 'images/') => asset($this->image_path),
            default => Storage::disk('public')->url($this->image_path),
        });
    }
}
