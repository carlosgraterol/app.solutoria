<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Pago extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'fecha', 
        'monto'
    ];

    protected $table = 'pagos';

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
   }
}
