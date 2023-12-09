<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\DestinationRequest;
use App\Models\Destination;
use App\Models\Fare;
use Illuminate\Http\Request;

class DestinationController extends Controller
{

     /**
     * Display a listing of the resource.
     */
    public function all()
    {
        //
        $destination = Destination::select('*')
                        ->orderBy('created_at' , 'ASC')
                        ->get();
        return $destination;
    }
    /**
     * Display a listing of the resource.
     */
    public function index($origin, $type)
    {
        //
        return Destination::select()
                ->join('fare' , 'fare.destination_id' , '=', 'destination.id')
                ->where('fare.type' , $type)
                ->where('origin', $origin)
                ->get();
         
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
    public function store(DestinationRequest $request)
    {
        //
        $validated = $request->validated();

        $destination = Destination::create($validated);

        return [
            'destination' => $destination,
            'message' => 'successfully created' 
        ];

    
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $destination = Destination::select('*')
        ->addSelect([
            'regular' => Fare::select('fare')
                ->where('type', 'regular')
                ->whereColumn('destination_id', 'destination.id')
                ->limit(1),
            'sp' => Fare::select('fare')
                ->where('type', 'sp')
                ->whereColumn('destination_id', 'destination.id')
                ->limit(1),
        ])
        ->where('id', $id)
        ->first();
        return $destination;
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
    public function update(DestinationRequest $request, string $id)
    {
        //
        $validated = $request->validated();
        $destination = Destination::where('id' , $id)
                        ->update($validated);
        return [
            'destination' => $destination,
            'message' => 'successfully updated.'
        ];
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $fare = Fare::select()
                        ->where('destination_id' , $id)
                        ->delete();      
        $destination = Destination::findOrFail($id);
        $destination->delete();

        return [
            'fare' => $fare,
            'destination' => $destination,
            'message' => 'successfully deleted.'
        ];
    }
}
