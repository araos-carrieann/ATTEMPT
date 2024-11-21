<?php

namespace App\Livewire;

use App\Models\eBooks;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Search extends Component
{
    public $search;
    public function render()
    {
        $results = [];
        if (strlen($this->search) > 2) {
            $results = eBooks::whereHas('tags', function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                ->orWhere('title', 'like', '%' . $this->search . '%')
                      ->orWhere('slug', 'like', '%' . $this->search . '%');
            })->get(); 
        }

        return view('livewire.search', compact('results'));
    }
}
