<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\TransactionType
 *
 * @property int $id
 * @property string|null $name
 * @property int $account_id
 * @property string|null $message
 * @property int $show_only
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TransactionType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TransactionType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TransactionType query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TransactionType whereAccountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TransactionType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TransactionType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TransactionType whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TransactionType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TransactionType whereShowOnly($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TransactionType whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TransactionType extends Model
{
    protected $attributes = [
        'name'=>null,
        'account_id'=>0,
        'comment'=>null,
    ];
}
