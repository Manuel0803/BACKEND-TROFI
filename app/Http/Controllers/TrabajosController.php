<?php

namespace App\Http\Controllers;

use App\Models\Trabajos;
use Illuminate\Http\Request;

class TrabajosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $trabajos = Trabajos::all();
        return $trabajos;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Trabajos $trabajos)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Trabajos $trabajos)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Trabajos $trabajos)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Trabajos $trabajos)
    {
        //
    }
}
