<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransactionSellLinesPurchaseLines extends Model
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

    public function purchase_line()
    {
        return $this->belongsTo(\App\PurchaseLine::class, 'purchase_line_id');
    }
}
