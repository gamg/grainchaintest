<div>
    <section class="flex flex-col items-center space-y-4 py-12">
        <h1 class="text-3xl font-bold underline">
            Iluminar Habitación
        </h1>

        <form wire:submit.prevent="create" class="p-4 w-1/4">
            <div class="mb-4">
                <label class="block font-medium text-sm text-gray-700" for="file">
                    Subir archivo
                </label>

                <input wire:model="file" type="file" class="block mt-1 w-full" id="file">

                <div wire:loading wire:target="file" class="mt-1 w-full text-indigo-700">
                    Preparando archivo...
                </div>

                @error("file")<div class="mt-1 text-red-600 text-sm">{{ $message }}</div>@enderror
            </div>

            <button wire:loading.remove wire:target="file" type="submit" class="bg-indigo-700 text-white font-semibold uppercase w-full rounded shadow p-2">Crear Matríz</button>
        </form>

        @if(!empty($matrix))
            <button wire:click="illuminate" class="w-1/6 mt-5 items-center px-4 py-2 bg-red-500 border border-transparent rounded font-semibold text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150">
                Iluminar
            </button>

            <h3 class="font-semibold">Bombillos usados: {{ $totalLightbulbs }}</h3>
        @endif

        <table class="shadow-md w-1/3">
            <tbody class="text-gray-600 text-center">
                @forelse($matrix as $row)
                    <tr>
                        @foreach($row as $column)
                            <td class="px-4 py-4 border-gray-400 border-2 {{ $column == 1 ? 'bg-gray-300' : '' }} {{ $column == 2 ? 'bg-yellow-400' : '' }} {{ $column == 7 ? 'bg-yellow-200' : '' }}">
                                @if($column == 1)
                                    Pared
                                @elseif($column == 0)
                                    Espacio
                                @elseif($column == 2)
                                    <div class="flex justify-center">
                                        <x-icons.lightbulb/>
                                    </div>
                                @elseif($column == 7)
                                    Iluminado
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @empty
                    <h3>Debe cargar un archivo para mostrar los datos</h3>
                @endforelse
            </tbody>
        </table>
    </section>
</div>
