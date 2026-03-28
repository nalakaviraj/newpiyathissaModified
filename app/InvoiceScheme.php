<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvoiceScheme extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * Returns list of invoice schemes in array format
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        // Set connection dynamically
        $this->setConnection(session('selected_db', config('database.default')));
    }
    public static function forDropdown($business_id)
    {
        $dropdown = InvoiceScheme::where('business_id', $business_id)
                                ->pluck('name', 'id');

        return $dropdown;
    }

    /**
     * Retrieves the default invoice scheme
     */
    public static function getDefault($business_id)
    {
        $default = InvoiceScheme::where('business_id', $business_id)
                                ->where('is_default', 1)
                                ->first();

        return $default;
    }
}
