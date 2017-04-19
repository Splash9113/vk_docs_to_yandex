<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UploadFile extends Model
{
    public $fillable = ['title', 'doc_id', 'email', 'link'];
}
