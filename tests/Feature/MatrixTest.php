<?php

namespace Tests\Feature;

use App\Http\Livewire\Matrix;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class MatrixTest extends TestCase
{
    /** @test  */
    public function component_can_be_rendered()
    {
        $this->get('/')->assertStatus(200)->assertSeeLivewire('matrix');
    }

    /** @test  */
    public function component_can_create_the_matrix()
    {
        $file = UploadedFile::fake()->create('room.txt');
        Storage::fake('files');

        Livewire::test(Matrix::class)
            ->set('file', $file)
            ->call('create');
    }

    /** @test  */
    public function component_can_illuminate_the_matrix()
    {
        $matrix = [
            [0,0,0],
            [1,0,0],
            [0,0,0],
            [0,0,1]
        ];
        Livewire::test(Matrix::class)
            ->set('matrix', $matrix)
            ->call('illuminate');
    }
}
