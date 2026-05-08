<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WikiCategories extends Model
{
    use HasFactory;
    protected $table = "x_wiki_categories";
    protected $primaryKey = 'id';
    protected $fillable = ['id','name','slug'];
    public $timestamps = false;

    public function posts()
    {
        return $this->hasMany(WikiPost::class, 'category_id');
    }

}