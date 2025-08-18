<x-guest-layout>
    <div class="min-h-auto flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900">
        <div class="w-full sm:max-w-xl mt-6 mb-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
    
            @if(Session::has('success'))
            <div class="flex items-center p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
                <svg class="shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"></path>
                </svg>
                <div>{{ Session::get('success') }}</div>
            </div>
            @endif
            @if(Session::has('error'))
            <div class="flex items-center p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
                <svg class="shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"></path>
                </svg>
                <div><div>{{ Session::get('error') }}</div></div>
            </div>
            @endif

            <form method="POST" action="{{ route('complaint.store') }}" id="formValidation" enctype="multipart/form-data">
                @csrf

                @guest
                    <!-- Name -->
                    <div>
                        <x-input-label for="name" class="required-input" :value="__('Name')" />
                        <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <!-- Username -->
                    <div class="mt-4">
                        <x-input-label for="username" class="required-input" :value="__('CNIC')" />
                        <x-text-input id="username" class="block mt-1 w-full" type="text" name="username" placeholder="xxxxx-xxxxxxx-x" :value="old('username')" required autocomplete="username" />
                        <x-input-error :messages="$errors->get('username')" class="mt-2" />
                    </div>

                    <!-- Mobile -->
                    <div class="mt-4">
                        <x-input-label for="mobile" class="required-input" :value="__('Mobile')" />
                        <x-text-input id="mobile" class="block mt-1 w-full" type="text" name="mobile" :value="old('mobile')" placeholder="03xx-xxxxxxx" required autocomplete="username" />
                        <x-input-error :messages="$errors->get('mobile')" class="mt-2" />
                    </div>

                    <!-- Email Address -->
                    <div class="mt-4">
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" autocomplete="email" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Address -->
                    <div class="mt-4">
                        <x-input-label for="address" class="required-input" :value="__('Address')" />
                        {{ html()->textarea('address', null)->class('block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm')->placeholder('Address')->maxlength(500)->required() }}
                        <x-input-error :messages="$errors->get('address')" class="mt-2" />
                    </div>
                @endguest
                    
                    <!-- Complaint Category -->
                    <div class="mt-4">
                        <x-input-label for="category" class="required-input" :value="__('Complaint Category')" />
                        {{ html()->select('category', $categories, null)->class('block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm')->placeholder('Complaint Category')->required() }}
                        <x-input-error :messages="$errors->get('category')" class="mt-2" />
                    </div>
                    
                    <!-- Description -->
                    <div class="mt-4">
                        <x-input-label for="description" class="required-input" :value="__('Description')" />
                        {{ html()->textarea('description', null)->class('block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm')->placeholder('Description')->maxlength(500)->required() }}
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>
                    
                    <!-- Location -->
                    <div class="mt-4">
                        <x-input-label for="location" class="required-input" :value="__('Location')" />
                        {{ html()->text('location', null)->class('block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm')->placeholder('Location')->maxlength(100)->required() }}
                        <x-input-error :messages="$errors->get('location')" class="mt-2" />
                    </div>
                    
                    <!-- Attachment -->
                    <div class="mt-4">
                        <x-input-label for="attachment" :value="__('Attachment')" />
                        {{ html()->file('attachment') }}
                        <x-input-error :messages="$errors->get('attachment')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        @guest
                            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                                {{ __('Login') }}
                            </a>
                        @endguest

                        @auth
                            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('dashboard') }}">
                                {{ __('Complaint Details') }}
                            </a>
                        @endauth
                        

                        <x-primary-button class="ms-4">
                            {{ __('Submit') }}
                        </x-primary-button>
                    </div>
            </form>
            
            <link rel="stylesheet" href="{{ asset('css/custom.css?v=1') }}">

            <script src="{{ asset('js/jquery.min.js') }}"></script>
            <script src="{{ asset('js/jquery.validate.min.js') }}"></script>
            <script src="{{ asset('js/jquery.mask.js') }}"></script>
            
            <script type="text/javascript">
                $('document').ready(function () {
                    $('#formValidation').validate();
                    $('#username').mask('00000-0000000-0');    
                    $('#mobile').mask('0300-0000000');    
                });
            </script>
        </div>
    </div>
</x-guest-layout>
