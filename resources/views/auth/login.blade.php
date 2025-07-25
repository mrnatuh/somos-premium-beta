<x-layouts.web>
    <div class="grid grid-cols-1 lg:grid-cols-2 h-screen">
        <div class="bg-black h-screen hidden lg:flex">

        </div>

        <div class="p-4 flex items-center justify-center h-screen">
            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form
                method="POST"
                action="{{ route('login') }}"
                class="w-full max-w-[400px]"
            >
                @csrf

                <!-- Email Address -->
                <div>
                <x-input-label for="username" :value="__('Email ou Centro de custo')" />
                <x-text-input id="username" class="block mt-1 w-full" type="text" name="username" :value="old('username')" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('username')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="mt-4">
                    <x-input-label for="password" :value="__('Password')" />

                    <x-text-input id="password" class="block mt-1 w-full"
                                    type="password"
                                    name="password"
                                    required autocomplete="current-password" />

                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Remember Me -->
                {{-- <div class="block mt-4">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" name="remember">
                        <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
                    </label>
                </div> --}}

                <div class="flex items-center justify-end mt-4">
                    {{-- @if (Route::has('password.request'))
                        <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('password.request') }}">
                            {{ __('Forgot your password?') }}
                        </a>
                    @endif --}}

                    <x-primary-button class="ml-3">
                        {{ __('Log in') }}
                    </x-primary-button>
                </div>
            </form>

        </div>
    </div>
</x-layouts.web>
