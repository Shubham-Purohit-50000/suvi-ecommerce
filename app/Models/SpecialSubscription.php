<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\SpecialSubscription
 *
 * @property int $id
 * @property string $subscription_type
 * @property float $discount
 */
class SpecialSubscription extends Model
{
    use HasFactory;
    protected $fillable = ['subscription_type', 'discount', 'amount', 'description'];

    public function customerSubscriptions()
    {
        return $this->hasMany(CustomerSpecialSubscription::class);
    }
}
