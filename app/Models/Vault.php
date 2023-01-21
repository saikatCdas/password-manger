<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vault extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'folder_id', 'category', 'email', 'name', 'user_name' , 'password', 'url', 'card_holder_name','card_number',
    'card_expiration_date', 'card_security_code', 'notes'];

    // Relation with User Class
    public function user (){
        return $this->belongsTo(User::class);
    }

    // Relation with Folder
    public function folder(){
        return $this->belongsTo(Folder::class);
    }
}
