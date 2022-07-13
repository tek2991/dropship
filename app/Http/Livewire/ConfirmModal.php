<?php

namespace App\Http\Livewire;

use LivewireUI\Modal\ModalComponent;

class ConfirmModal extends ModalComponent
{
    public function render()
    {
        return view('livewire.confirm-modal');
    }
}
