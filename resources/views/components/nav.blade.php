<nav x-data="{ open: false }" class="flex flex-col justify-between bg-gray-100 border-r border-gray-100 dark:border-gray-700 w-full min-w-[250px] max-w-[250px] relative">
    <div>
        <div class="flex gap-4 p-3 items-center justify-between">
            <img
                src="/img/avatar.png"
                alt="{{ auth()->user()->name }}"
                class="w-10 h-10 object-cover"
            />
            <div class="flex flex-col">
                <span>Bem vindo,</span>
                <strong>{{ auth()->user()->name }}</strong>
            </div>

            <a
                class="flex items-center justify-center w-[50px] h-10 rounded-md hover:bg-slate-100 hover:opacity-95"
                href="{{ route('profile.edit') }}"
            >
                <svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12.22 2.5H11.78C11.2496 2.5 10.7409 2.71071 10.3658 3.08579C9.99072 3.46086 9.78 3.96957 9.78 4.5V4.68C9.77964 5.03073 9.68706 5.37519 9.51154 5.67884C9.33602 5.98248 9.08374 6.23464 8.78 6.41L8.35 6.66C8.04596 6.83554 7.70108 6.92795 7.35 6.92795C6.99893 6.92795 6.65404 6.83554 6.35 6.66L6.2 6.58C5.74107 6.31526 5.19584 6.24344 4.684 6.38031C4.17217 6.51717 3.73555 6.85154 3.47 7.31L3.25 7.69C2.98526 8.14893 2.91345 8.69416 3.05031 9.206C3.18717 9.71783 3.52154 10.1544 3.98 10.42L4.13 10.52C4.43228 10.6945 4.68362 10.9451 4.85905 11.2468C5.03448 11.5486 5.1279 11.891 5.13 12.24V12.75C5.1314 13.1024 5.03965 13.449 4.86405 13.7545C4.68844 14.0601 4.43521 14.3138 4.13 14.49L3.98 14.58C3.52154 14.8456 3.18717 15.2822 3.05031 15.794C2.91345 16.3058 2.98526 16.8511 3.25 17.31L3.47 17.69C3.73555 18.1485 4.17217 18.4828 4.684 18.6197C5.19584 18.7566 5.74107 18.6847 6.2 18.42L6.35 18.34C6.65404 18.1645 6.99893 18.0721 7.35 18.0721C7.70108 18.0721 8.04596 18.1645 8.35 18.34L8.78 18.59C9.08374 18.7654 9.33602 19.0175 9.51154 19.3212C9.68706 19.6248 9.77964 19.9693 9.78 20.32V20.5C9.78 21.0304 9.99072 21.5391 10.3658 21.9142C10.7409 22.2893 11.2496 22.5 11.78 22.5H12.22C12.7504 22.5 13.2591 22.2893 13.6342 21.9142C14.0093 21.5391 14.22 21.0304 14.22 20.5V20.32C14.2204 19.9693 14.3129 19.6248 14.4885 19.3212C14.664 19.0175 14.9163 18.7654 15.22 18.59L15.65 18.34C15.954 18.1645 16.2989 18.0721 16.65 18.0721C17.0011 18.0721 17.346 18.1645 17.65 18.34L17.8 18.42C18.2589 18.6847 18.8042 18.7566 19.316 18.6197C19.8278 18.4828 20.2645 18.1485 20.53 17.69L20.75 17.3C21.0147 16.8411 21.0866 16.2958 20.9497 15.784C20.8128 15.2722 20.4785 14.8356 20.02 14.57L19.87 14.49C19.5648 14.3138 19.3116 14.0601 19.136 13.7545C18.9604 13.449 18.8686 13.1024 18.87 12.75V12.25C18.8686 11.8976 18.9604 11.551 19.136 11.2455C19.3116 10.9399 19.5648 10.6862 19.87 10.51L20.02 10.42C20.4785 10.1544 20.8128 9.71783 20.9497 9.206C21.0866 8.69416 21.0147 8.14893 20.75 7.69L20.53 7.31C20.2645 6.85154 19.8278 6.51717 19.316 6.38031C18.8042 6.24344 18.2589 6.31526 17.8 6.58L17.65 6.66C17.346 6.83554 17.0011 6.92795 16.65 6.92795C16.2989 6.92795 15.954 6.83554 15.65 6.66L15.22 6.41C14.9163 6.23464 14.664 5.98248 14.4885 5.67884C14.3129 5.37519 14.2204 5.03073 14.22 4.68V4.5C14.22 3.96957 14.0093 3.46086 13.6342 3.08579C13.2591 2.71071 12.7504 2.5 12.22 2.5V2.5Z" stroke="#757D8A" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M12 15.5C13.6569 15.5 15 14.1569 15 12.5C15 10.8431 13.6569 9.5 12 9.5C10.3431 9.5 9 10.8431 9 12.5C9 14.1569 10.3431 15.5 12 15.5Z" stroke="#757D8A" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </a>
        </div>

        <div class="flex flex-col p-3 gap-6">
            <x-nav-link
                :href="route('dashboard')"
                :active="request()->routeIs('dashboard')"
                class="flex gap-3 items-center"
                wire:navigate
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
                :href="route('previa')"
                :active="request()->routeIs('previa')"
                class="flex gap-3 items-center"
                wire:navigate
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
                href="#"
                class="flex gap-3 items-center"
                wire:navigate
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

            <x-nav-link
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
            </x-nav-link>
        </div>
    </div>

    <div class="mb-20 flex flex-col w-full p-3 gap-3">
        <x-nav-link
                href="#"
                onclick="event.preventDefault();this.closest('form').submit();"
                class="flex gap-3 font-normal p-1 items-center text-[16px] w-full"
            >
                <svg width="16" height="17" viewBox="0 0 16 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M8 5.8335C9.10457 5.8335 10 4.93807 10 3.8335C10 2.72893 9.10457 1.8335 8 1.8335C6.89543 1.8335 6 2.72893 6 3.8335C6 4.93807 6.89543 5.8335 8 5.8335Z" stroke="#757D8A" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M8 15.1668V5.8335" stroke="#757D8A" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M3.33325 8.5H1.33325C1.33325 10.2681 2.03563 11.9638 3.28587 13.214C4.53612 14.4643 6.23181 15.1667 7.99992 15.1667C9.76803 15.1667 11.4637 14.4643 12.714 13.214C13.9642 11.9638 14.6666 10.2681 14.6666 8.5H12.6666" stroke="#757D8A" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>


                {{ __('Suporte') }}
        </x-nav-link>

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
