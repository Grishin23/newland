<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Account
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Transaction[] $transactions
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Account newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Account newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Account query()
 * @mixin \Eloquent
 * @property int $id
 * @property int $user_id
 * @property float $balance
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Account whereBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Account whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Account whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Account whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Account whereUserId($value)
 * @property string|null $name
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Account whereName($value)
 */
class Account extends Model
{
    protected $attributes = [
        'user_id'=>0,
        'balance'=>0,
    ];

    public function getTransactionsAttribute(){
        return Transaction::where('account_init_id',$this->id)
            ->orWhere('account_target_id',$this->id)
            ->orderBy('created_at','desc')->paginate();
    }
    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
}
