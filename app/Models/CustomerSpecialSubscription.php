<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\CustomerSpecialSubscription
 *
 * @property int $id
 * @property int $customer_id
 * @property int $special_subscription_id
 * @property string $start_date
 * @property string $end_date
 */
class CustomerSpecialSubscription extends Model
{
    use HasFactory;
    protected $fillable = ['customer_id', 'special_subscription_id', 'start_date', 'end_date'];

    public function specialSubscription()
    {
        return $this->belongsTo(SpecialSubscription::class);
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id', 'id');
    }
}
