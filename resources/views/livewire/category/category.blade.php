@php
$is_page_realizadas = $realizadas;

$categories = [
    ["slug" => "faturamento", "label" => "Faturamento", "visible" => true],
    ["slug" => "eventos", "label" => "Eventos", "visible" => true],
    ["slug" => "mp", "label" => "MP", "visible" => true],
    ["slug" => "mo", "label" => "MO", "visible" => true],
    ["slug" => "he", "label" => "HE, Faltas e Atrasos", "visible" => true],
    ["slug" => "gd", "label" => "GD", "visible" => true],
    ["slug" => "investimento", "label" => "Investimento", "visible" => true],
    ["slug" => "sobra-limpa", "label" => "Sobra Limpa", "visible" => $is_page_realizadas ? true : false],
    ["slug" => "resto-ingesto", "label" => "Resto Ingesto", "visible" => $is_page_realizadas ? true : false],
];

$active = $_GET['filter'] ?? "faturamento";

$category = array_values(array_filter($categories, function($v, $k) use($active) {
    return $v['slug'] === $active;
}, ARRAY_FILTER_USE_BOTH))[0];

$action_publish = false;
$action_wait = false;
$action_approve_reprove = false;
$action_approved = false;

$log_status = isset($last_log['status']) ? $last_log['status'] : 'em-analise';
$log_level = isset($last_log['level']) ? $last_log['level'] : 1;

if (auth()->user()->isSupervisor()) {
    if (!$published_at || $log_status == 'recusado') {
        $action_publish = true;
    } else {
        $action_wait = true;
    }
}

if (auth()->user()->isCoordinator() && $published_at) {
    if ($log_level == '1' && $log_status == 'em-analise') {
        $action_approve_reprove = true;
    } else {
        $action_wait = true;
    }
}

if (auth()->user()->isDirector() && $published_at) {
    if ($log_level == '2' && $log_status == 'validado') {
        $action_approve_reprove = true;
    } else {
        $action_wait = true;
    }
}

if (auth()->user()->isManager() || auth()->user()->isAdmin()) {
    if ($published_at) {
        $action_approve_reprove = true;
    }
}

if ($log_level == '4' && $log_status == 'validado') {
    $action_publish = false;
    $action_wait = false;
    $action_approve_reprove = false;
    $action_approved = true;
}
@endphp

<div class="flex flex-col w-full h-full p-8">

    <livewire:category.category-header
        :title="'Inclusão de '. $category['label']"
    />

    <livewire:dashboard.dashboard-bar :active="$active" />

    <ul class="mt-10 flex flex-wrap text-sm font-medium text-center text-gray-500 border-b border-gray-200 dark:border-gray-700 dark:text-gray-400">
        @foreach($categories as $category)
            @if($category['visible'])
            <li>
                <a
                    href="?filter={{ $category['slug'] }}"
                    class="text-md inline-block p-2 px-4 rounded-t {{ $category['slug'] === $active ? 'bg-blue-500  hover:bg-blue-500 text-gray-100' : 'hover:bg-gray-100 hover:text-gray-600 transition-all duration-300' }}"
                >{{ $category['label'] }}</a>
            </li>
            @endif
        @endforeach
    </ul>

    <div class="flex flex-col w-full h-full relative">
        @if($active === 'faturamento')
            <livewire:category.category-invoicing />
        @endif

        @if($active === 'eventos')
        <livewire:category.category-event />
        @endif

        @if($active === 'mp')
        <livewire:category.category-m-p />
        @endif

        @if($active === 'mo')
        <livewire:category.category-m-o />
        @endif

        @if($active === 'he')
        <livewire:category.category-h-e />
        @endif

        @if($active === 'gd')
        <livewire:category.category-g-d />
        @endif

        @if($active === 'investimento')
        <livewire:category.category-investimento />
        @endif

        @if($is_page_realizadas && $active == 'sobra-limpa')
        <livewire:category.category-left />
        @endif

        @if($is_page_realizadas && $active == 'resto-ingesto')
        <livewire:category.category-rest />
        @endif

        {{-- @if ($action_approved && !$is_page_realizadas)
            <div class="absolute top-0 left-0 w-full h-full bg-white/50"></div>
        @endif --}}
    </div>

    @if(!$is_page_realizadas)
        <!-- Approve Area -->
        <div class="bg-white/50 w-full flex items-center justify-end gap-4 position fixed px-10 py-5 bottom-0 right-0 z-30"
            id="approve_area">

            @if ($action_wait)
            <span class="bg-black/90 px-6 py-2 text-white rounded-xl text-lg font-bold disabled:opacity-50 cursor-wait relative">
                Em análise, aguarde
            </span>
            @endif

            @if ($action_publish)
            <button
                class="bg-green-600 cursor-pointer px-6 py-2 text-white rounded-xl text-lg font-bold disabled:opacity-50 disabled:cursor-not-allowed relative"
                id="btn_approve"
                data-preview="{{ $id }}"
                data-cc="{{ session('preview')['cc'] }}"
                data-weekref="{{ session('preview')['week_ref'] }}"
                data-level="1"
                data-modal-target="approve-modal"
                data-modal-toggle="approve-modal"
            >
                Enviar para aprovação
                <x-status.loading />
            </button>
            @endif

            @if($action_approve_reprove)
            <button
                class="bg-green-600 cursor-pointer px-6 py-2 text-white rounded-xl text-lg font-bold disabled:opacity-50 disabled:cursor-not-allowed relative"
                id="btn_status_approve" data-preview="{{ $id }}" data-cc="{{ session('preview')['cc'] }}"
                data-weekref="{{ session('preview')['week_ref'] }}"
                data-level="{{ auth()->user()->level() }}">
                Aprovar

                <x-status.loading />
            </button>

            <button
                class="bg-red-600 cursor-pointer px-6 py-2 text-white rounded-xl text-lg font-bold disabled:opacity-50 disabled:cursor-not-allowed relative"
                id="btn_status_reprove" data-preview="{{ $id }}" data-cc="{{ session('preview')['cc'] }}"
                data-weekref="{{ session('preview')['week_ref'] }}"
                data-level="{{ auth()->user()->level() }}">
                Reprovar

                <x-status.loading />
            </button>
            @endif


            @if ($action_approved)
                <span class="bg-green-600/90 px-6 py-2 text-white rounded-xl text-lg font-bold disabled:opacity-50 cursor-none relative select-none z-30">Aprovado:
                    {{ \Carbon\Carbon::create($last_log['timestamp'])->format('H:i d/m/Y') }}
                </span>
            @endauth

            @if (sizeof($logs))
            <button class="flex p-2.5 bg-white items-center" id="btn_show_logs">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                    style="fill: rgba(0, 0, 0, .5);transform: ;msFilter:;">
                    <path
                        d="M12 6a3.939 3.939 0 0 0-3.934 3.934h2C10.066 8.867 10.934 8 12 8s1.934.867 1.934 1.934c0 .598-.481 1.032-1.216 1.626a9.208 9.208 0 0 0-.691.599c-.998.997-1.027 2.056-1.027 2.174V15h2l-.001-.633c.001-.016.033-.386.441-.793.15-.15.339-.3.535-.458.779-.631 1.958-1.584 1.958-3.182A3.937 3.937 0 0 0 12 6zm-1 10h2v2h-2z">
                    </path>
                    <path
                        d="M12 2C6.486 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.514 2 12 2zm0 18c-4.411 0-8-3.589-8-8s3.589-8 8-8 8 3.589 8 8-3.589 8-8 8z">
                    </path>
                </svg>
            </button>
            @endif
        </div>

        <!-- Modal Approve -->
        <div id="approve-modal" tabindex="-1"
            class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full bg-slate-100/50">
            <div class="relative p-4 w-full max-w-md max-h-full">
                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                    <button type="button"
                        class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-hide="approve-modal" id="approve-modal-close">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Fechar</span>
                    </button>

                    <div class="p-4 md:p-5 text-center">

                        <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>

                        <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">
                            Enviar a prévia para aprovação?<br />
                            <smalL>
                                Após envio, não será mais permitido a edição da prévia.
                            </smalL>
                        </h3>

                        <button data-modal-hide="approve-modal" type="button"
                            class="text-white bg-green-600 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 dark:focus:ring-green-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center me-2 disabled:opacity-50" id="approve-modal-confirm">
                            Sim, prosseguir
                        </button>

                        <button data-modal-hide="approve-modal" type="button"
                            class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600 disabled:opacity-50" id="approve-modal-cancel">Não, cancelar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal status approve -->
        <div id="approve-status-modal" tabindex="-1"
            class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full bg-slate-100/50">
            <div class="relative p-4 w-full max-w-md max-h-full">
                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                    <button type="button"
                        class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-hide="approve-status-modal" id="approve-status-modal-close">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Fechar</span>
                    </button>

                    <div class="p-4 md:p-5 text-center">

                        <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>

                        <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400" id="approve-status-modal-message">

                        </h3>


                        <textarea class="rounded-lg border-gray-400 w-full p-3 m-3 hidden" id="approve-status-modal-text" placeholder="Motivo..." required></textarea>

                        <button data-modal-hide="approve-modal" type="button"
                            class="text-white bg-green-600 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 dark:focus:ring-green-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center me-2 disabled:opacity-50"
                            id="approve-status-modal-confirm"
                            data-preview="{{ $id }}"
                    data-cc="{{ session('preview')['cc'] }}"
                    data-weekref="{{ session('preview')['week_ref'] }}"
                    data-level="{{ auth()->user()->level() }}">
                            Sim, prosseguir
                        </button>

                        <button data-modal-hide="approve-modal" type="button"
                            class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600 disabled:opacity-50"
                            id="approve-status-modal-cancel"
                            data-preview="{{ $id }}"
                    data-cc="{{ session('preview')['cc'] }}"
                    data-weekref="{{ session('preview')['week_ref'] }}"
                    data-level="{{ auth()->user()->level() }}"
                    >Não, cancelar</button>
                    </div>
                </div>
            </div>
        </div>

        @if (sizeof($logs))
        @php
            $log_status = [
                'em-analise' => 'Em análise',
                'recusado' => 'Recusado',
                'validado' => 'Validado',
            ];
            @endphp
        <div id="logs_area" class="hidden fixed flex-col gap-2 bottom-20 right-10 w-full max-w-[320px]">
            @foreach($logs as $log)
                <div class="bg-white flex flex-col w-full border shadow rounded-lg p-2.5">
                    <p class="flex gap-2 items-center mb-1">
                        <strong class="text-xs">
                            {{ $log['user_name'] }}
                        </strong>

                        <span class="text-xs">
                            {{ $log['timestamp']->isToday() ?  $log['timestamp']->diffForHumans() : $log['timestamp']->format('H:i d/m/Y') }}
                        </span>
                    </p>

                    <p class="text-xs mb-1">
                        @if ($log['status'] == 'em-analise' && $published_at)
                            <span>
                                Enviado para aprovação
                            </span>
                        @else
                            <span>
                                {{ $log_status[$log['status']] }}
                            </span>
                        @endif
                    </p>

                    @if(isset($log['text']))
                    <p class="text-xs"><strong>Motivo:</strong> {{ $log['text'] }}</p>
                    @endif
                </div>
            @endforeach
        </div>
        @endif
    @endif
</div>
