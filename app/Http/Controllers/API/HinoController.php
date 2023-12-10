<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\HinoRequest;
use App\Models\Hino;
use App\Models\Passenger;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HinoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $results = Hino::select('hino.*', DB::raw('COUNT(transactions.*) as trip'))
        ->leftJoin('transactions', function ($join) {
            $join->on('hino.id', '=', 'transactions.bus_id')
                ->where(DB::raw('DATE(transactions.created_at)'), '=', DB::raw('CURRENT_DATE'));
        })
        ->groupBy('hino.id')
        ->orderBy('trip', 'DESC')
        ->get();

        return $results;
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
        $hino = Hino::findOrFail($id);

        return $hino;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function transactions(string $id)
    {
        //
        $hino = Hino::findOrFail($id);
        $hinoId = $hino->id;

        $results = DB::table(DB::raw("(SELECT h.id, h.name, DATE(t.created_at) as trip_date, COUNT(p.pass_id) as total_pass, SUM(f.fare) as total_collection
        FROM hino h
        INNER JOIN transactions t ON h.id = t.bus_id
        INNER JOIN passengers p ON p.transaction_id = t.id
        INNER JOIN destination d ON d.id = p.destination_id
        INNER JOIN fare f ON f.destination_id = d.id AND p.type = f.type
        GROUP BY h.id, trip_date, t.bus_id, t.id
        HAVING h.id = :hinoId) AS subquery"))
        ->select('id', 'name', DB::raw('COUNT(trip_date) as total_trips'), DB::raw('SUM(total_pass) as total_pass'), 'trip_date', DB::raw('SUM(total_collection) as total_collection'))
        ->groupBy('trip_date', 'id', 'name')
        ->orderBy('trip_date', 'desc')
        ->addBinding($hinoId, 'select')
        ->get();
        
        return $results;
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
    public function update(HinoRequest $request, string $id)
    {
        //
        $validated = $request->validated();
        $hino = Hino::where('id' , $id)
                            ->update($validated);
        return [
            'hino' => $hino,
            'message' => 'successfully updated'
        ];

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
