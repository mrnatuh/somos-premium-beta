<div class="p-8 w-full">
    <div class="flex flex-col border-b pb-10 mb-10">
        <strong class="text-2xl">Usuários</strong>
    </div>

    <form wire:submit="save">
        @csrf

        <div class="mt-6">
            <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nome</label>

            <input type="text" id="name" wire:model="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />

            @error('name')
                <span class="text-red-500 text-xs mt-2">{{ $message }}</span>
            @enderror
        </div>

        <div class="mt-6">
            <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">E-mail</label>

            <input type="email" id="email" wire:model="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />

            @error('email')
                <span class="text-red-500 text-xs mt-2">{{ $message }}</span>
            @enderror
        </div>

        <div class="mt-6">
            <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Senha</label>

            <div class="flex w-full gap-2" x-data="{ show: false }">
                <div class="flex flex-shrink flex-grow w-[80%] relative ">
                    <input :type="show ? 'text' : 'password'" id="password"  wire:model="password" class="bg-gray-50 border border-gray-300 flex text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 flex-shrink flex-grow w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />

                    <button type="button" class="flex absolute inset-y-0 right-0 items-center p-3" @click="show = !show" :class="{'hidden': !show, 'block': show }">
                        <!-- Heroicon name: eye -->
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </button>
                    <button type="button" class="flex absolute inset-y-0 right-0 items-center p-3" @click="show = !show" :class="{'block': !show, 'hidden': show }">
                        <!-- Heroicon name: eye-slash -->
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                        </svg>
                    </button>
                </div>

                <x-primary-button type="button" class="flex w-[20%]" wire:click="generatePassword">Gerar senha</x-primary-button>
            </div>

            @error('password')
                <span class="text-red-500 text-xs mt-2">{{ $message }}</span>
            @enderror
        </div>

        <div class="mt-6">
            <label for="access" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Regra</label>

            <div>
                <select id="access" wire:model="access" class="bg-gray-50 border border-gray-300 flex text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 flex-shrink flex-grow w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option value="" selected></option>
                    <option value="admin">Admin</option>
                    <option value="manager">Gestor</option>
                    <option value="coordinator">Coordenador</option>
                    <option value="director">Diretor</option>
                    <option value="user">Supervisor</option>
                </select>
            </div>

            @error('access')
                <span class="text-red-500 text-xs mt-2">{{ $message }}</span>
            @enderror
        </div>

        <div class="mt-6">
            <label for="cc" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Centro de Custo</label>

            <input type="text" id="cc"  wire:model="cc" class="bg-gray-50 border border-gray-300 flex text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 flex-shrink flex-grow w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />

            @error('cc')
                <span class="text-red-500 text-xs mt-2">{{ $message }}</span>
            @enderror
        </div>

        <div class="mt-14">
            <x-primary-button type="submit" class="relative">
                Salvar

                <x-status.loading />
            </x-primary-button>
        </div>
    </form>
</div>
