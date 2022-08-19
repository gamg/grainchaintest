<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use App\Http\Livewire\Traits\WithIlluminateMatrix;

class Matrix extends Component
{
    use WithFileUploads, WithIlluminateMatrix;

    public $file = null;
    public $matrix = [];

    protected $rules = [
        'file' => 'required|mimes:txt|max:1024',
    ];

    public function create()
    {
        $this->validate();
        $this->matrix = [];
        $this->reset('totalLightbulbs');

        $nameFile = $this->file->store('/', 'files');
        $file = fopen(Storage::disk('files')->path($nameFile), 'r');

        // Fill Matrix from file
        $row = 0;
        while(!feof($file)) {
            $line = trim(fgets($file));
            $spaces = explode('|', $line);
            $column = 0;

            foreach ($spaces as $space) {
                $this->matrix[$row][$column] = (int)$space;
                $column++;
            }
            $row++;
        }
        fclose($file);
    }

    public function illuminate()
    {
        $this->setMaxValues();
        $this->firstStageToIlluminateMatrix();
        $this->secondStageToIlluminateMatrix();
        $this->lastStageToIlluminateMatrix();
    }

    public function render()
    {
        return view('livewire.matrix');
    }
}
