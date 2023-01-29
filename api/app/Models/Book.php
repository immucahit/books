<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Database\Eloquent\Builder;
use App\Models\Author;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'author_id',
        'title',
        'description',
        'isbn',
        'price',
        'is_deleted'
    ];

    public $timestamps = false;

    public function scopeByUser(Builder $query,int $user){
        return $query->where('user_id',$user);
    }

    public function scopeIsDeleted(Builder $query,bool $isDeleted){
        return $query->where('is_deleted',$isDeleted);
    }

    public function can($user){
        return $this->user_id == $user;
    }

    public function author(){
        return $this->belongsTo(Author::class,'author_id','id');
    }
}
