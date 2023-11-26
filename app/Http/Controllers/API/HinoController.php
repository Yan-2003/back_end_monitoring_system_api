<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\HinoRequest;
use App\Models\Hino;
use Illuminate\Http\Request;

class HinoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return Hino::all();
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
    public function store(HinoRequest $request)
    {
        //
        $validated = $request->validated();
        $hino = Hino::create($validated);
        return [
            'hino'  => $hino,
            'message' => 'successfully created'
        ];
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $hino = Hino::findOrFail($id);

        $hino->delete();

        return [
            'hino' => $hino,    
            'message' => 'successfully deleted'
        ];
    }
}
