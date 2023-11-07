<div class="flex flex-col justify-between w-full">
    <category-mo></category-mo>

    <div class="bg-white/50 w-full flex items-center justify-end gap-4 position fixed px-10 py-5 bottom-0 right-0">

        <form method="post" action="{{ route('mo.store') }}" class="flex flex-col">
            @csrf

            <textarea id="mo_json" name="mo_json" class="hidden">{{ json_encode($mo) }}</textarea>

            <button
                class="bg-green-600 cursor-pointer px-6 py-2 text-white rounded-xl text-xl font-bold relative"
                type="submit"
            >
                Salvar

                <x-status.loading />
            </button>
        </form>

        <a href="{{ route('preview') }}" class="bg-red-600 px-6 py-2 text-white rounded-xl text-xl font-bold relative">
            Cancelar

            <x-status.loading />
        </a>
    </div>

    <div class="h-20"></div>
</div>
