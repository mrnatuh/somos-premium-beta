<div class="p-8 w-full">
    <div>
        <strong class="text-2xl">Prévias</strong>
        <p class="text-gray-600">Crie uma nova prévia</p>
    </div>

    <form wire:submit="save">
        <div class="mt-14">
            <label for="countries" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Escolha o ano</label>
            <select wire:model="year" wire:change="getWeeks" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                @foreach($years as $option)
                <option value="{{ $option }}" {{ $option === $year ? 'selected' : '' }}>{{ $option }}</option>
                @endforeach
            </select>
        </div>

        <div class="mt-6">
            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Escolha o mês</label>
            <select wire:model="month" wire:change="getWeeks" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                @foreach($months as $option)
                <option value="{{ $loop->index }}" {{ $month === $loop->index ? 'selected' : '' }}>{{ $option }}</option>
                @endforeach
            </select>
        </div>

        <div class="mt-6">
            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Escolha a semana do mês</label>
            <select wire:model="week" wire:change="getWeeks" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                @for($s = 1; $s <= $weeks; $s++)
                <option value="{{ $s }}" {{ $s === $week ? 'selected' : '' }}>Semana {{ $s }}</option>
                @endfor
            </select>
        </div>

        <div class="border-t py-10 mt-10 flex justify-end">
            <button
                type="submit"
                class="text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700
                transition-all relative"
                wire:loading.attr="disabled"
            >
                Criar prévia

                <x-status.loading />
            </button>
        </div>
    </form>
</div>
