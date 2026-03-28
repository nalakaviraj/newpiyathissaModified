<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccountType extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        // Set connection dynamically
        $this->setConnection(session('selected_db', config('database.default')));
    }
    public function sub_types()
    {
        return $this->hasMany(\App\AccountType::class, 'parent_account_type_id');
    }

    public function parent_account()
    {
        return $this->belongsTo(\App\AccountType::class, 'parent_account_type_id');
    }
}
