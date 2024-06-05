<?php

namespace App\Http\Controllers;

use App\Models\AkutansiTingkatSatu as ModelsAkutansiTingkatSatu;
use Illuminate\Http\Request;

class AkutansiTingkatSatu extends Controller
{
    public function index()
    {
        return ModelsAkutansiTingkatSatu::all();
    }
}
