<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GroupSubTax extends Model
{
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        // Set connection dynamically
        $this->setConnection(session('selected_db', config('database.default')));
    }
    public function tax_rate()
    {
        return $this->belongsTo(\App\TaxRate::class, 'group_tax_id');
    }
}
