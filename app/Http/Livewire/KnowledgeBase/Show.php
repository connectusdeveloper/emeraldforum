<?php

namespace App\Http\Livewire\KnowledgeBase;

use Livewire\Component;
use App\Models\KnowledgeBase;

class Show extends Component
{
    public KnowledgeBase $knowledge_base;

    public function mount(?KnowledgeBase $knowledge_base)
    {
        $this->knowledge_base = $knowledge_base;
    }

    public function render()
    {
        return view('livewire.knowledgebase.show')->layout('layouts.public');
    }
}
