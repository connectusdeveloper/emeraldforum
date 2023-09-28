<?php

namespace App\Http\Livewire\Policy;

use Livewire\Component;
use Laravel\Jetstream\Jetstream;

class Terms extends Component
{
    public function render()
    {
        $termsFile = Jetstream::localizedMarkdownPath('terms.md');

        return view('livewire.policy.terms', ['terms' => file_get_contents($termsFile)])->layout('layouts.public');
    }
}
