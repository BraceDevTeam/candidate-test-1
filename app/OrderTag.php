<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderTag extends Model
{
    use SoftDeletes;

    protected $table = "orders_tags";
}
