<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Saldo extends Model
{
    use HasFactory;
    protected $fillable = [
        "id",
        "jurnal_id",
        "saldo_awal_debit",
        "saldo_awal_kredit",
        "periode_saldo",
        "current_saldo_kredit",
        "current_saldo_debit",
        "created_at",
        "created_by",
        "updated_at",
        "updated_by",
        "is_deleted",
        "deleted_at",
        "deleted_by",
    ];
}
