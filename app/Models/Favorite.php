<?php

namespace App\Models;

use App\RecordsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory , RecordsActivity;

    protected $fillable = ['user_id', 'favorited_id', 'favorited_type'];

    public function favorited()
    {
        return $this->morphTo();
    }
}
