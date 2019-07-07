<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Transaction
 *
 * @property-read \App\Account $account_init
 * @property-read \App\Account $account_target
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Transaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Transaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Transaction query()
 * @mixin \Eloquent
 * @property int $id
 * @property int $account_init_id
 * @property int $account_target_id
 * @property float $amount
 * @property float $balance_before
 * @property float $balance_after
 * @property string|null $message
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Transaction whereAccountInitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Transaction whereAccountTargetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Transaction whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Transaction whereBalanceAfter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Transaction whereBalanceBefore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Transaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Transaction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Transaction whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Transaction whereUpdatedAt($value)
 */
class Transaction extends Model
{
    protected $attributes = [
        'account_init_id'=>0,
        'account_target_id'=>0,
        'amount'=>0,
        'balance_before'=>0,
        'balance_after'=>0,
        'message'=>null,
    ];
    protected $with = [
        'account_init',
        'account_target'
    ];
    public function account_init(){
        return $this->belongsTo(Account::class,'account_init_id');
    }
    public function account_target(){
        return $this->belongsTo(Account::class,'account_target_id');
    }

}
