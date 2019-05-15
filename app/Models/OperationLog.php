<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OperationLog extends Model
{
    protected $fillable = ['user_name', 'menu_name', 'sub_menu_name', 'input', 'ip', 'path', 'method', 'user_id', 'operate_name'];
}
