<?php

namespace App\Http\Livewire\Category;

use Livewire\Component;
use App\Models\Category;

class Show extends Component
{
    public Category $category;

    public function mount(?Category $category)
    {
        if (auth()->user()->cant('read-categories')) {
            return redirect()->to(url()->previous())->with('error', __('You do not have permissions to perform this action.'));
        }
        $this->category = $category;
    }

    public function render()
    {
        return view('livewire.category.show');
    }
}
