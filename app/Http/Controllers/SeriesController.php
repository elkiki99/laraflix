<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SeriesController extends Controller
{
    public function index()
    {
        return view('series.index');
    }

    public function show($id)
    {
        return view('series.show', [
            'id' => $id
        ]);
    }
}
