<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AkutansiTingkatSatu extends Model
{
    use HasFactory;
    protected $fillable = [
        "name",
        "created_at",
        "created_by",
        "updated_at",
        "updated_by",
        "is_deleted",
        "deleted_at",
        "deleted_by",
    ];
}
