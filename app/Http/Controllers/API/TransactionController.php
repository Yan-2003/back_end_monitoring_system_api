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
        $transaction_id = Transaction::findOrFail($id);
        $transaction_id = Transaction::select('transactions.id', 'transactions.origin', 'transactions.user_id', 'transactions.bus_id', 'hino.name', 'transactions.created_at')
            ->join('hino', 'hino.id', '=', 'transactions.bus_id')
            ->where('transactions.id', $transaction_id['id'])
            ->where('transactions.user_id',$user->id )
            ->first();
        return $transaction_id;
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
