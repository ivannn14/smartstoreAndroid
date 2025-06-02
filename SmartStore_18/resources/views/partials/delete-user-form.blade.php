<section class="space-y-6">
    <div x-data="{ open: false }">
        <button x-on:click="open = true"
            class="px-4 py-2 bg-red-600 text-white font-semibold rounded-lg shadow-md transition hover:bg-red-700 focus:ring focus:ring-red-300">
            {{ __('Delete Account') }}
        </button>

        <!-- Delete Confirmation Modal -->
        <div x-show="open" x-transition
            class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 p-4">
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg w-full max-w-md"
                x-transition.opacity x-transition.scale.95
                style="border: none !important; box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);">

                <!-- Modal Header -->
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">
                    {{ __('Are you sure you want to delete your account?') }}
                </h2>
                <p class="mt-3 text-base text-gray-700 dark:text-gray-300 leading-relaxed">
                    {{ __('This action is irreversible. Please enter your password to confirm.') }}
                </p>

                <!-- Form -->
                <form method="post" action="{{ route('profile.destroy') }}" class="mt-5 space-y-4" onsubmit="return confirmDelete()">
                    @csrf
                    @method('delete')

                    <!-- Password Input -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-800 dark:text-gray-300">
                            {{ __('Password') }}
                        </label>
                        <input type="password" id="password" name="password"
                            class="mt-2 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm dark:bg-gray-700 dark:text-white focus:ring focus:ring-red-300 focus:outline-none p-2">
                        <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2 text-red-500" />
                    </div>

                    <!-- Buttons -->
                    <div class="flex justify-end space-x-3">
                        <button type="button" x-on:click="open = false"
                            class="px-4 py-2 bg-gray-500 text-white font-medium rounded-lg shadow-md transition hover:bg-gray-600 focus:ring focus:ring-gray-300">
                            {{ __('Cancel') }}
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-red-600 text-white font-semibold rounded-lg shadow-md transition hover:bg-red-700 focus:ring focus:ring-red-300">
                            {{ __('Delete Account') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<script>
    function confirmDelete() {
        return confirm("⚠️ Warning! This action is irreversible. Are you sure you want to delete your account?");
    }
</script>
