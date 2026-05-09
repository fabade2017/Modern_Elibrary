<x-guest-layout>
    <div class="container-fluid" style="height: 100vh; display: flex;">
        <div class="login-half" style="flex: 1; display: flex; justify-content: center; align-items: center; padding: 20px; background-color: #fff;">
            <div class="login-card" style="border-radius: 12px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); padding: 30px; max-width: 400px; width: 100%;">
                <div class="login-logo" style="text-align: center; margin-bottom: 20px;">
                    <img src="https://via.placeholder.com/120x40?text=eLibrary+Logo" alt="eLibrary Management Logo" style="max-width: 120px;">
                </div>
                <div class="login-title" style="text-align: center; font-size: 24px; font-weight: 600; color: #1a1a1a; margin-bottom: 20px;">Sign in to your account</div>
                <div class="datetime-display" id="datetime" style="text-align: center; font-size: 14px; color: #6c757d; margin-bottom: 20px;"></div>

                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email Address -->
                    <div class="mb-3">
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input id="email" class="form-control" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
                        <x-input-label for="password" :value="__('Password')" />
                        <x-text-input id="password" class="form-control" type="password" name="password" required autocomplete="current-password" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Remember Me -->
                    <div class="mb-3">
                        <label for="remember_me" class="inline-flex items-center">
                            <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                            <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                        </label>
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        @if (Route::has('password.request'))
                            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                                {{ __('Forgot your password?') }}
                            </a>
                        @endif

                        <x-primary-button class="ms-3 btn btn-primary" style="padding: 10px; font-weight: 500;">
                            {{ __('Log in') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
        <div class="image-half" style="flex: 1; background: url('img/ddade9a9-f1e0-4567-ba93-6b221ac08af7.jpg') no-repeat top right; background-size: cover;">
            <style>
                .image-half::after {
                    content: 'Command Schools eLibrary Management System';
                    position: absolute;
                    top: 20px;
                    right: 20px;
                    color: #fff;
                    font-size: 24px;
                    font-weight: 600;
                    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
                    z-index: 1;
                }
            </style>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {
            // Update datetime display
            function updateDateTime() {
                const now = new Date();
                const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit', hour12: true, timeZone: 'Africa/Lagos' };
                const formattedDateTime = now.toLocaleString('en-US', options).replace(' at ', ', ').replace(/(\d+):(\d+) (\w+)/, '$1:$2 $3 WAT');
                $('#datetime').text('Current Time: ' + formattedDateTime);
            }
            updateDateTime(); // Initial call
            setInterval(updateDateTime, 60000); // Update every minute
        });
    </script>
</x-guest-layout>