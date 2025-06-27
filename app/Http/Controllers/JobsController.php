<?php

namespace App\Http\Controllers;

use App\Models\Trabajo;
use Illuminate\Http\Request;

class JobsController extends Controller
{

    public function index()
    {
        $trabajo = Trabajo::all();
        return $trabajo;
    }

    public function create()
    {
    }

    public function store(Request $request)
    {
    }

    public function show(Trabajo $trabajos)
    {
        return response()->json($trabajos);
    }

    public function edit(Trabajo $trabajos)
    {
    }

    public function update(Request $request, Trabajo $trabajos)
    {
    }

    public function destroy(Trabajo $trabajos)
    {
    }
}
