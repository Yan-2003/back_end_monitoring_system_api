<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\FareRequest;
use App\Models\Fare;
use Illuminate\Http\Request;

class FareController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return Fare::all();
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
    public function store(FareRequest $request)
    {
        //
        $validated = $request->validated();

        $fare  = Fare::create($validated);

        return [
            'fare' => $fare,
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
    public function update(FareRequest $request, string $id , string $type)
    {
        //
        $validated = $request->validated();
        $fare = Fare::where('destination_id' , $id)
                    ->where('type' , $type)
                    ->update($validated);
        return [
            'fare' => $fare,
            'message' => 'successfully updated.'
        ];
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
