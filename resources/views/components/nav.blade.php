<nav x-data="{ open: false }" class="flex flex-col justify-between bg-gray-100 border-r border-gray-100 dark:border-gray-700 w-full min-w-[250px] max-w-[320px] relative h-full z-40">
    <div class="py-8">
        <div class="flex gap-4 p-3 items-center justify-between">
            <div class="flex gap-4">
                <img
                    src="/img/avatar.png"
                    alt="{{ auth()->user()->name }}"
                    class="w-10 h-10 object-cover"
                />

                <div class="flex flex-col">
                    <span>Bem vindo,</span>
                    <strong>{{ auth()->user()->name }}</strong>
                </div>
            </div>

            <div x-data="{dropdownMenu: false}" class="relative">
                <button @click="dropdownMenu = ! dropdownMenu" class="flex items-center bg-gray-100 rounded-md p-2">
                    <x-icons.engine />
                </button>

                <div x-show="dropdownMenu" class="border absolute flex flex-col right-0 top-7 mt-2 bg-white rounded-md shadow-xl w-44">
                    <a
                        class="flex items-center p-2.5 h-10 rounded-md hover:bg-slate-100 hover:opacity-95 w-full"
                        href="{{ route('profile.edit') }}"
                    >
                        Perfil
                    </a>

                    @if( auth()->user()->isAdmin() )
                    <a class="flex items-center w-full p-2.5 h-10 rounded-md hover:bg-slate-100 hover:opacity-95"
                        href="{{ route('category.parameters') }}">
                        Parâmetros
                    </a>
                    @endif
                </div>
            </div>
        </div>

        <div class="flex flex-col p-3 gap-6">
            <x-nav-link
                :href="route('dashboard')"
                :active="request()->routeIs('dashboard')"
                class="flex gap-3 items-center"
            >
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M6.66667 2H2V8H6.66667V2Z" stroke="#757D8A" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M13.9999 2H9.33325V5.33333H13.9999V2Z" stroke="#757D8A" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M13.9999 8H9.33325V14H13.9999V8Z" stroke="#757D8A" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M6.66667 10.6667H2V14.0001H6.66667V10.6667Z" stroke="#757D8A" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>

                <span class="text-lg">
                    {{ __('Dashboard') }}
                </span>
            </x-nav-link>

            <x-nav-link
                :href="route('preview')"
                :active="request()->routeIs('preview')"
                class="flex gap-3 items-center"
            >
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12.6667 2H3.33333C2.59695 2 2 2.59695 2 3.33333V12.6667C2 13.403 2.59695 14 3.33333 14H12.6667C13.403 14 14 13.403 14 12.6667V3.33333C14 2.59695 13.403 2 12.6667 2Z" stroke="#757D8A" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M2 6H14" stroke="#757D8A" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M2 10H14" stroke="#757D8A" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M8 2V14" stroke="#757D8A" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>


                <span class="text-lg">
                    {{ __('Previa') }}
                </span>
            </x-nav-link>

            <x-nav-link
                :href="route('preview.done')"
                :active="request()->routeIs('preview.done')"
                class="flex gap-3 items-center"
            >
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M9.66675 1.33325H4.00008C3.64646 1.33325 3.30732 1.47373 3.05727 1.72378C2.80722 1.97382 2.66675 2.31296 2.66675 2.66659V13.3333C2.66675 13.6869 2.80722 14.026 3.05727 14.2761C3.30732 14.5261 3.64646 14.6666 4.00008 14.6666H12.0001C12.3537 14.6666 12.6928 14.5261 12.9429 14.2761C13.1929 14.026 13.3334 13.6869 13.3334 13.3333V4.99992L9.66675 1.33325Z" stroke="#757D8A" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M9.33325 1.33325V5.33325H13.3333" stroke="#757D8A" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M10.6666 8.66675H5.33325" stroke="#757D8A" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M10.6666 11.3333H5.33325" stroke="#757D8A" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M6.66658 6H5.33325" stroke="#757D8A" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>

                <span class="text-lg">
                    {{ __('Realizado') }}
                </span>
            </x-nav-link>

            {{-- <x-nav-link
                href="#"
                class="flex gap-3 items-center"
                wire:navigate
            >

                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12.6667 2.66675H3.33333C2.59695 2.66675 2 3.2637 2 4.00008V13.3334C2 14.0698 2.59695 14.6667 3.33333 14.6667H12.6667C13.403 14.6667 14 14.0698 14 13.3334V4.00008C14 3.2637 13.403 2.66675 12.6667 2.66675Z" stroke="#757D8A" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M10.6667 1.33325V3.99992" stroke="#757D8A" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M5.33325 1.33325V3.99992" stroke="#757D8A" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M2 6.66675H14" stroke="#757D8A" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>

                <span class="text-lg">
                    {{ __('Apontamentos') }}
                </span>
            </x-nav-link> --}}
        </div>
    </div>

    <div class="mb-20 flex flex-col w-full p-3 gap-3">
        @if(Auth::user()->isAdmin())
            <x-nav-link
                href="{{ route('profiles.index') }}"
                class="flex gap-3 font-normal p-1 items-center text-[16px] w-full"
            >
                <svg xmlns="http://www.w3.org/2000/svg" width="16" viewBox="0 0 24 24" style="fill: #757D8A;transform: ;msFilter:;"><path d="M12 2a5 5 0 1 0 5 5 5 5 0 0 0-5-5zm0 8a3 3 0 1 1 3-3 3 3 0 0 1-3 3zm9 11v-1a7 7 0 0 0-7-7h-4a7 7 0 0 0-7 7v1h2v-1a5 5 0 0 1 5-5h4a5 5 0 0 1 5 5v1z"></path></svg>


                {{ __('Usuários') }}
        </x-nav-link>
        @endif

        {{--
        <x-nav-link
                href="#"
                class="flex gap-3 font-normal p-1 items-center text-[16px] w-full"
            >
                <svg width="16" height="17" viewBox="0 0 16 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M8 5.8335C9.10457 5.8335 10 4.93807 10 3.8335C10 2.72893 9.10457 1.8335 8 1.8335C6.89543 1.8335 6 2.72893 6 3.8335C6 4.93807 6.89543 5.8335 8 5.8335Z" stroke="#757D8A" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M8 15.1668V5.8335" stroke="#757D8A" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M3.33325 8.5H1.33325C1.33325 10.2681 2.03563 11.9638 3.28587 13.214C4.53612 14.4643 6.23181 15.1667 7.99992 15.1667C9.76803 15.1667 11.4637 14.4643 12.714 13.214C13.9642 11.9638 14.6666 10.2681 14.6666 8.5H12.6666" stroke="#757D8A" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>


                {{ __('Suporte') }}
        </x-nav-link>
        --}}

        <!-- Logout -->
        <form method="POST" action="{{ route('logout') }}" class="w-full">
            @csrf

            <x-nav-link
                :href="route('logout')"
                onclick="event.preventDefault();this.closest('form').submit();"
                class="flex gap-3 font-normal p-1 items-center text-[16px] w-full"
            >
                <svg width="16" height="17" viewBox="0 0 16 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12.2399 4.92676C13.0788 5.76595 13.6501 6.83505 13.8814 7.99888C14.1128 9.16272 13.9939 10.369 13.5397 11.4653C13.0855 12.5615 12.3165 13.4985 11.3298 14.1577C10.3431 14.8169 9.18319 15.1687 7.99658 15.1687C6.80998 15.1687 5.65002 14.8169 4.66337 14.1577C3.67671 13.4985 2.90768 12.5615 2.45349 11.4653C1.99931 10.369 1.88038 9.16272 2.11173 7.99888C2.34308 6.83505 2.91433 5.76595 3.75325 4.92676" stroke="#757D8A" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M8 1.8335V8.50016" stroke="#757D8A" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>

                {{ __('Sair') }}
            </x-nav-link>
        </form>
    </div>
</nav>
