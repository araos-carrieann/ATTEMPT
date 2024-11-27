<?php

namespace App\Livewire;

use App\Models\eBooks;
use Livewire\Attributes\Url;
use Livewire\Component;

class Search extends Component
{
    #[Url()]
    public $search = '';
    public function render()
    {
        $results = [];
        if (strlen($this->search) > 1) {
            $results = eBooks::whereHas('tags', function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                ->orWhere('title', 'like', '%' . $this->search . '%')
                      ->orWhere('slug', 'like', '%' . $this->search . '%');
            })->get(); 
        }

        return view('livewire.search', compact('results'));
    }
}
