<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MovieController extends Controller
{
    public function index()
    {
        return view('movies.index');
    }

    public function show($id)
    {
        return view('movies.show', [
            'id' => $id
        ]);
    }
}
