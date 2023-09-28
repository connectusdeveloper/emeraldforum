<?php

namespace App\Http\Livewire\Faq;

use App\Models\Faq;
use Livewire\Component;

class Show extends Component
{
    public Faq $faq;

    public function mount(?Faq $faq)
    {
        $this->faq = $faq;
    }

    public function render()
    {
        return view('livewire.faq.show')->layout('layouts.public');
    }
}
