<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $outlet_name
 * @property string $outlet_slug
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $user
 * @method static \Database\Factories\OutletFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Outlet newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Outlet newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Outlet query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Outlet whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Outlet whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Outlet whereOutletName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Outlet whereUpdatedAt($value)
 * @mixin \Eloquent
 */

class Outlet extends Model
{
    /** @use HasFactory<\Database\Factories\OutletFactory> */
    use HasFactory;

    protected $guarded = ['id'];

    public function user() {
        return $this->hasOne(User::class, 'id_outlet', 'id');
    }

    public function getRouteKeyName() {
        return 'outlet_slug';
    } 
}
