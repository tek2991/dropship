<?php

namespace App\Http\Livewire\Components;

use App\Models\Expense;
use App\Models\Invoice;
use App\Models\Vehicle;
use Livewire\Component;

class CreateExpense extends Component
{
    public $vehicles = [];
    public $invoices;

    public $vehicle_id;
    public $amount;
    public $remark;
    public $date;

    public $error = null;

    public function mount()
    {
        // $this->vehicles = Vehicle::orderBy('registration_number')->get();
    }

    public function rules()
    {
        return [
            'date' => 'required|date',
            'vehicle_id' => 'required|exists:vehicles,id',
            'amount' => 'required|integer',
            'remark' => 'required|string',
        ];
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);

        // If updated property is date
        if ($propertyName == 'date') {
            $vehicle_ids = Invoice::whereDate('date', $this->date)->pluck('vehicle_id')->toArray();
            $this->vehicles = Vehicle::whereIn('id', $vehicle_ids)->orderBy('registration_number')->get();
            $this->vehicle_id = null;
            $this->invoices = null;
            $this->amount = null;
            $this->remark = null;
        }

        // If vehicle_id is not null and date is not null
        if ($this->vehicle_id && $this->date) {
            // If updated property is vehicle_id or date
            if ($propertyName == 'vehicle_id' || $propertyName == 'date') {
                // Get invoices for the vehicle and date
                $this->invoices = Vehicle::find($this->vehicle_id)->invoices()->whereDate('date', $this->date)->get();
                // Check if expense already exists for the vehicle and date
                $this->checkDateVehicleAlreadyExists();
            }
        }
    }
    public function checkDateVehicleAlreadyExists()
    {
        $expense = Expense::where('vehicle_id', $this->vehicle_id)->whereDate('date', $this->date)->first();
        if ($expense) {
            $this->error = 'Expense already exists for this vehicle and date';
        } else {
            $this->error = null;
        }
    }

    public function store()
    {
        $validated = $this->validate();

        $this->checkDateVehicleAlreadyExists();

        if ($this->error) {
            return;
        }

        Expense::create($validated);

        return redirect()->route('admin.expenses.index')->with('message', 'Expense created successfully');
    }

    public function render()
    {
        return view('livewire.components.create-expense');
    }
}
