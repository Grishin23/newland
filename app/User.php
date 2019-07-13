<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * App\User
 *
 * @property-read \App\Account $main_accounts
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \App\Role $role
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User query()
 * @mixin \Eloquent
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $user_role_id
 * @property string|null $caption
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCaption($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereUserRoleId($value)
 * @property int $crew
 * @property-read mixed $available_accounts
 * @property-read mixed $available_accounts_edit
 * @property-read \App\Account $main_account
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCrew($value)
 */
class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','crew'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function main_account(){
        return $this->hasOne(Account::class,'user_id');
    }
    public function role(){
        return $this->belongsTo(Role::class,'user_role_id');
    }
    public function getAvailableAccountsAttribute(){
        if($this->role && $this->role->accounts){
            return $this->role->accounts()->with(['user'])->orderBy('id','asc')->get();
        }
        return collect([]);
    }
    public function getAvailableAccountsEditAttribute(){
        if($this->role && $this->role->accounts){
            return $this->role->accounts()->where('edit','=',true)->with(['user'])->orderBy('id','asc')->get();
        }
        return collect([]);
    }
    public function checkAvailableEdit($accountID){
        if ($this->main_account->id == $accountID){
            return true;
        }
        $accounts = $this->available_accounts_edit->toArray();
        if ($accounts && in_array($accountID,array_column($accounts,'id'))){
            return true;
        }
        return false;
    }
    public function checkAvailable($accountID){
        if ($this->main_account->id == $accountID){
            return true;
        }
        $accounts = $this->available_accounts->toArray();
        if ($accounts && in_array($accountID,array_column($accounts,'id'))){
            return true;
        }
        return false;
    }
}
