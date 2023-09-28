<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Maintenance extends Component
{
    public function mount()
    {
        if ('Maintenance' != site_config('mode')) {
            return to_route('threads');
        }
    }

    public function render()
    {
        return view('livewire.maintenance')->layout('layouts.guest');
    }
}
