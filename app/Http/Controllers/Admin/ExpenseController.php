<?php

namespace App\Http\Controllers\Admin;

use App\Models\Expense;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.expenses.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $vehicles = Vehicle::orderBy('registration_number')->get();
        return view('admin.expenses.create', [
            'vehicles' => $vehicles,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'amount' => 'required|integer',
            'remark' => 'required|string',
        ]);

        // dd($validated);

        Expense::create($validated);

        return redirect()->route('admin.expenses.index')->with('message', 'Expense created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function show(Expense $expense)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function edit(Expense $expense)
    {
        $vehicles = Vehicle::orderBy('registration_number')->get();
        return view('admin.expenses.edit', [
            'expense' => $expense,
            'vehicles' => $vehicles,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Expense $expense)
    {
        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'amount' => 'required|integer',
            'remark' => 'required|string',
        ]);
        $expense->update($validated);
        return redirect()->route('admin.expenses.index')->with('message', 'Expense updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function destroy(Expense $expense)
    {
        //
    }
}
