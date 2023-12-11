<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class admincontroller extends Controller
{
    function index()
    {
        return view('pointakses/admin/index');
    }
}
