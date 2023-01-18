<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Folder extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'user_id'];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function vaults(){
        return $this->hasMany(Vault::class);
    }
}
