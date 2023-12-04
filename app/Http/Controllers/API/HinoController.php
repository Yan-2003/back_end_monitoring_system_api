<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\HinoRequest;
use App\Models\Hino;
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
