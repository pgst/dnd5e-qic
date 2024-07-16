<?php

namespace App\Livewire;

use Livewire\Component;

class Selector extends Component
{
    public $userExamination;

    public function mount($userExamination)
    {
        $this->userExamination = $userExamination;
    }

    public function select($answer)
    {
        $this->userExamination->selected_answer = $answer;
    }

    public function render()
    {
        return view('livewire.selector');
    }
}
