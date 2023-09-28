<?php

namespace App\Http\Livewire\Policy;

use Livewire\Component;
use Laravel\Jetstream\Jetstream;

class Policy extends Component
{
    public function render()
    {
        $termsFile = Jetstream::localizedMarkdownPath('policy.md');

        return view('livewire.policy.policy', ['policy' => file_get_contents($termsFile)])->layout('layouts.public');
    }
}
