<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\PassengerRequest;
use App\Models\Passenger;
use Illuminate\Http\Request;

class PassengerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return Passenger::all();
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
    public function store(PassengerRequest $request)
    {
        //

        $validated = $request->validated();

        $passenger = Passenger::create($validated);

        return [
            'passenger' => $passenger,
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
    public function update(PassengerRequest $request, string $id)
    {
        //
        $validated = $request->validated();
        $passenger = Passenger::where('pass_id' , $id)
                    ->update($validated);

        return [
            'id' => $id,
            'passenger' => $passenger,
            'message' => 'successfully updated'
        ];

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $passenger = Passenger::findOrFail($id);
        $passenger->delete();
        return [
            'passenger' => $passenger,
            'message' => 'successfully deleted'
        ];
    }
}
