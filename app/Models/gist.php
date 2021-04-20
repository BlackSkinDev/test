<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\SoftDeletes;


class Gist extends Model
{
    use HasFactory,Uuids;

    use SoftDeletes;

    protected $primaryKey = "uuid";
    protected $keyType = 'string';
    public $incrementing = false;



    protected $fillable = [
        'title',
        'body',
        'user_id'
    ];



    public function user()
    {
        return $this->belongsTo(User::class);
    }




}
