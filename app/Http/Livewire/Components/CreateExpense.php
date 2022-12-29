<?php

namespace App\Http\Livewire\Components;

use App\Models\Expense;
use App\Models\Vehicle;
use Livewire\Component;

class CreateExpense extends Component
{
    public $vehicles;
    public $invoices;

    public $vehicle_id;
    public $amount;
    public $remark;
    public $date;

    public function mount()
    {
        $this->vehicles = Vehicle::orderBy('registration_number')->get();
    }

    public function rules()
    {
        return [
            'vehicle_id' => 'required|exists:vehicles,id',
            'amount' => 'required|integer',
            'remark' => 'required|string',
            'date' => 'required|date',
        ];
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);

        // If vehicle_id is not null and date is not null
        if ($this->vehicle_id && $this->date) {
            // If updated property is vehicle_id or date
            if ($propertyName == 'vehicle_id' || $propertyName == 'date') {
                // Get invoices for the vehicle and date
                $this->invoices = $this->vehicles->find($this->vehicle_id)->invoices()->whereDate('date', $this->date)->get();
            }
        }
    }

    public function store(){
        $validated = $this->validate();

        // dd($validated);

        Expense::create($validated);

        return redirect()->route('admin.expenses.index')->with('message', 'Expense created successfully');
    }

    public function render()
    {
        return view('livewire.components.create-expense');
    }
}