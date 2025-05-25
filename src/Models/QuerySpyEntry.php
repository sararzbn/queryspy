<?php

namespace QuerySpy\Models;

use Illuminate\Database\Eloquent\Model;

class QuerySpyEntry extends Model
{
    protected $guarded = [];
    protected $casts = [
        'bindings' => 'array',
    ];

}
