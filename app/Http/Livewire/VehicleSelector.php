<?php

namespace App\Http\Livewire;

use Livewire\Component;

class VehicleSelector extends Component
{
    public $vehicles;

    public $vehicle_id;

    public function mount()
    {
        $this->vehicles = \App\Models\Vehicle::all();
    }

    public function updatedVehicleId($value)
    {
         $this->emit('pg:eventRefresh-default');
    }

    public function render()
    {
        return view('livewire.vehicle-selector');
    }
}
