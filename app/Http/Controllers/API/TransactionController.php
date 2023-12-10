<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\TransactionRequest;
use App\Models\Hino;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function all()
    {
        //
        $results = Transaction::select('transactions.id', 'hino.name', 'transactions.origin', 
                DB::raw("TO_CHAR(transactions.created_at, 'Mon DD YYYY | HH:MI AM') as date_time"),
                DB::raw("CONCAT(users.first_name, ' ', users.last_name) as full_name"))
                ->join('users', 'users.id', '=', 'transactions.user_id')
                ->join('hino', 'hino.id', '=', 'transactions.bus_id')
                ->whereNull('hino.deleted_at')
                ->get();
        return $results;
    }





    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $user = Auth::user();
        $results = DB::table('transactions as t')
        ->join('hino as h', 't.bus_id', '=', 'h.id')
        ->select('t.id', 't.origin', 't.user_id', 'h.name', 't.bus_id', 
                 DB::raw("TO_CHAR(t.created_at, 'Mon DD YYYY') as date"),
                 DB::raw("TO_CHAR(t.created_at, 'HH:MI AM') as time"))
        ->where('t.user_id' , $user->id)
        ->orderBy('date', 'DESC')
        ->orderBy('time', 'DESC')
        ->get();
        return $results;
    }

    /**
     * Display a listing of the resource.
     */
    public function index_bus($id)
    {
        //
        $results = DB::table('transactions as t')
        ->join('hino as h', 't.bus_id', '=', 'h.id')
        ->join('users as u' , 'u.id' , '=' , 't.user_id')
        ->select('t.id', 't.origin', 't.user_id', 'h.name', 't.bus_id', 
                DB::raw("CONCAT(u.first_name, ' ' , u.last_name) as full_name"),
                DB::raw("TO_CHAR(t.created_at, 'Mon DD YYYY') as date"),
                DB::raw("TO_CHAR(t.created_at, 'HH:MI AM') as time"))
        ->where('h.id' , $id)
        ->where(DB::raw('DATE(t.created_at)'), '=', DB::raw('CURRENT_DATE'))
        ->orderBy('date', 'DESC')
        ->orderBy('time', 'DESC')
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
    public function store(TransactionRequest $request)
    {
        //
        $user = Auth::user();
        $validated = $request->validated();

        $validated['user_id'] = $user->id;

        $transaction = Transaction::create($validated);

        return [
            'transaction' => $transaction,
            'message' => 'successfully created'
        ];


    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $user = Auth::user();
        $transaction = Transaction::select(
            'transactions.id', 
            'hino.name',
            'transactions.origin', 
            DB::raw("CONCAT(TO_CHAR(transactions.created_at, 'Mon DD YYYY'), ' ', TO_CHAR(transactions.created_at, 'HH:MI AM')) as date_time"),
            DB::raw("CONCAT(users.first_name, ' ', users.last_name) as dis_name"),
            DB::raw("COUNT(passengers.*) as total_passengers"),
            DB::raw("SUM(fare.fare) as total_collection"),
            DB::raw("(SELECT COUNT(passengers.type) FROM passengers WHERE passengers.transaction_id = transactions.id AND passengers.type = 'regular') as regular"),
            DB::raw("(SELECT COUNT(passengers.type) FROM passengers WHERE passengers.transaction_id = transactions.id AND passengers.type = 'sp') as sp")
        )
        ->join('hino', 'transactions.bus_id', '=', 'hino.id')
        ->join('users', 'transactions.user_id', '=', 'users.id')
        ->join('passengers', 'transactions.id', '=', 'passengers.transaction_id')
        ->join('fare', function ($join) {
            $join->on('passengers.type', '=', 'fare.type')
                ->on('passengers.destination_id', '=', 'fare.destination_id');
        })
        ->join('destination', function ($join) {
            $join->on('passengers.destination_id', '=', 'destination.id')
                ->on('fare.destination_id', '=', 'destination.id');
        })
        ->where('transactions.id', $id)
        ->where('users.id', $user->id)
        ->groupBy('transactions.id', 'hino.name', 'users.first_name', 'users.last_name')
        ->orderBy('date_time' , 'DESC')
        ->get();

        return $transaction; 
    }

    /**
     * Display the specified resource.
     */
    public function showbus(string $id)
    {
        //
        $user = Auth::user();
        $transaction = Transaction::findOrFail($id);
        
        $transaction = Transaction::select()->join('hino' , 'hino.id' , '=' , 'transactions.bus_id')
        ->where('transactions.id' ,$transaction['id'])
        ->where('transactions.user_id' , $user->id)
        ->first();

        return $transaction;
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
        $transaction = Transaction::findOrFail($id);
        $transaction->delete();
        return [
            'transaction' => $transaction,
            'message' => 'successfully deleted.'
        ];
    }
}
