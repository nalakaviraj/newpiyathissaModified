<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserContactAccess extends Model
{
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        // Set connection dynamically
        $this->setConnection(session('selected_db', config('database.default')));
    }
    //
}
