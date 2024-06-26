<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
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
    
        <!-- Device ID -->
      <input type="hidden" name="text" id="deviceid">
      
        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" name="remember">
                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button id="loginButton" class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>

        </div>
       
    </form>

<script type="text/javascript">

document.addEventListener("DOMContentLoaded", (event) => {
            console.log("DOM fully loaded");
            initializeFlutterCommunication();
            // Initialize the device ID
            initializeDeviceId();
        });

        function serviceConfigure(deviceId)
        {
            document.getElementById('deviceid').value=deviceId;
        }

        function initializeFlutterCommunication() 
        {
            try {
                communicationchannel.postMessage('Message from javascript'); 
            } catch (error) {
                console.log("error",error)
            }
        }

        function initializeDeviceId() {
            // Check if device ID exists in the URL
            var urlParams = new URLSearchParams(window.location.search);
            var deviceIdFromUrl = urlParams.get('device_id');

            if (deviceIdFromUrl) {
                // Set the device ID value to the hidden input field
                document.getElementById('deviceid').value = deviceIdFromUrl;

                // Send AJAX request to check if device ID exists in the database
                $.ajax({
                    url: '/check-device',
                    method: 'POST',
                    data: { device_id: deviceIdFromUrl },
                    success: function(response) {
                        if (response.user_id) {
                            // Device ID exists, automatically log in the user
                            window.location.href = '/dashboard'; // Redirect to dashboard
                        } else {
                            // Device ID doesn't exist, store a new record
                            // Send AJAX request to store the device ID
                            $.ajax({
                                url: '/store-device',
                                method: 'POST',
                                data: { device_id: deviceIdFromUrl },
                                success: function(response) {
                                    console.log('Device ID stored successfully');
                                    // Optionally, you can redirect the user to the login page or any other appropriate action
                                    window.location.href = '/login';
                                },
                                error: function(xhr, status, error) {
                                    console.error('Error storing device ID:', error);
                                    // Handle the error as needed
                                }
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error checking device ID:', error);
                        // Handle the error as needed
                    }
                });
            }
        }


</script>
</x-guest-layout>
