<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $uuid_outlet
 * @property string $title
 * @property string $link
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\OutletLinkFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OutletLink newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OutletLink newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OutletLink query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OutletLink whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OutletLink whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OutletLink whereUpdatedAt($value)
 * @mixin \Eloquent
 */

class OutletLink extends Model
{
    /** @use HasFactory<\Database\Factories\OutletLinkFactory> */
    use HasFactory;

    protected $guarded = ['id'];

    public function getRouteKeyName() {
        return 'uuid';
    }

    public static function findBySlug(String $outlet_slug) {
        $result = self::where('link', $outlet_slug)->exists();

        return $result;
    }

    public function outletLinkClicks() {
        return $this->hasMany(OutletLinkClick::class, 'uuid_outlet_link', 'uuid');
    }
}
