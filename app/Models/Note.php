<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Note extends Model {
    protected $fillable = [
        'title',
        'content',
        'is_archived'
    ];
    protected $casts = [
        'is_archived' => 'boolean'
    ]; 

    public function tags() {
        return $this->belongsToMany(Tag::class);
    }
}