<div class="w-full">
    <x-dashboard.container>
        <div class="flex border-b items-center justify-between pb-10 mb-10">
            <div>
                <strong class="text-2xl">Usuários</strong>
                <p class="text-gray-600">{{ $total }} {{ $total > 1 ? 'resultados encontrados' : 'resultado encontrado' }}</p>
            </div>

            <div class="flex w-full relative max-w-[470px] gap-4">
                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                    <svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M11.5291 19C15.9473 19 19.5291 15.4183 19.5291 11C19.5291 6.58172 15.9473 3 11.5291 3C7.11077 3 3.52905 6.58172 3.52905 11C3.52905 15.4183 7.11077 19 11.5291 19Z" stroke="#757D8A" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M21.5291 21L17.1791 16.65" stroke="#757D8A" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>

                <input type="search" id="preview-search" class="flex w-[470px] p-4 pl-14 text-lg text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-4 focus:ring-blue-100 dark:bg-gray-700 dark:border-gray-600 placeholder-gray-300 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Buscar usuários..." required wire:model.lazy="search" />

                <button type="submit" class="text-white bg-[#5B6AD0] hover:opacity-90 focus:ring-4 focus:outline-none focus:ring-blue-100 font-medium rounded-[10px] text-[16px] px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 transition-all relative">
                    Buscar

                    <x-status.loading />
                </button>
            </div>

            <div class="flex">
                <x-notification.icon />
            </div>
        </div>

        <div class="flex mb-5 mt-5">
            <a
                wire:navigate
                href="{{ route('profiles.create') }}"
                class="py-2.5 px-5 mr-2 mb-2 text-[16px] font-medium text-gray-900 focus:outline-none bg-white rounded-[10px] border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 cursor-pointer">
                    Adicionar
            </a>

        </div>

        <div class="flex w-full flex-shrink flex-grow mb-14">
            <table class="w-full caption-bottom text-sm">
                <thead class="[&amp;_tr]:border-b">
                    <tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                        <th
                            class="h-12 px-4 text-left align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0 w-[100px]">
                            Nome</th>
                        <th
                            class="h-12 px-4 text-left align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0 w-[100px]">
                            E-mail</th>
                        <th
                            class="h-12 px-4 text-left align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0 w-[100px]">
                            Tipo</th>
                        <th
                            class="h-12 px-4 text-left align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0 w-[100px]">
                            </th>
                    </tr>
                </thead>
                <tbody class="[&amp;_tr:last-child]:border-0">
                    @foreach($users as $user)
                    <tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                        <td class="p-4 align-middle [&amp;:has([role=checkbox])]:pr-0 font-medium">
                            {{ $user->name }}
                        </td>
                        <td class="p-4 align-middle [&amp;:has([role=checkbox])]:pr-0 font-medium">
                            {{ $user->email }}
                        </td>
                        <td class="p-4 align-middle [&amp;:has([role=checkbox])]:pr-0 font-medium">
                            @if($user->access == 'admin')
                            Admin
                            @elseif($user->access == 'manager')
                            Gestor
                            @elseif($user->access == 'director')
                            Diretor
                            @elseif($user->access == 'coordinator')
                            Coordenador
                            @else
                            Supervisor
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('profiles.edit', [ "user" => $user ]) }}">
                                <img src="/img/edit.svg" />
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $users->links() }}
    </x-dashboard.container>
</div>
