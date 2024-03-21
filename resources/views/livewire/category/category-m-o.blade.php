<div class="flex flex-col justify-between w-full">
    @if ($modal_type == 'admin')
        <div class="text-center py-20 flex flex-col gap-6 items-center">
            <p>Centro de custo <strong>{{ $cc }}</strong> não possui parâmetros cadastrados. </p>

            <a href="{{ route('category.parameters') }}" class="flex w-[180px] justify-center text-sm bg-white rounded-lg border py-2.5 text-gray-700 hover:bg-gray-50 transition-all duration-300">Cadastrar parâmetros</a>
        </div>
    @elseif($modal_type == 'other')
        <div class="text-center py-20 flex flex-col gap-6 items-center">
            <p>Ainda não foram cadastrados parâmetros para o centro de custo <strong>{{ $cc }}</strong>.</p>

            <p>
                Aguarde o cadastro pela controladoria.
            </p>

            {{-- <a href="{{ route('preview') }}"
                class="flex w-[80px] justify-center text-sm bg-white rounded-lg border py-2.5 text-gray-700 hover:bg-gray-50 transition-all duration-300">Prévias</a> --}}
        </div>
    @else
        <category-mo></category-mo>

        <div class="bg-white/50 w-full flex items-center justify-end gap-4 position fixed px-10 py-5 bottom-0 right-0">
            <textarea id="mo_json" name="mo_json" class="hidden">{{ json_encode($mo) }}</textarea>
        </div>

        <div class="h-20"></div>
    @endif

		@if($edit)
		<div class="fixed top-5 left-1/2 -translate-x-1/2 bg-gray-900 text-gray-50 p-2 rounded-xl z-50 transition-all duration-300 text-md shadow" wire:loading>
			Salvando dados...
		</div>
		@endif

    <div class="h-20"></div>
</div>
