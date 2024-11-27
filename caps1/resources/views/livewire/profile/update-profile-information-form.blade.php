<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;

new class extends Component {
    public string $student_id = '';
    public string $first_name = '';
    public string $last_name = '';
    public string $yrlvl = '';
    public string $program = '';
    public string $username = '';
    public string $email = '';

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->student_id = Auth::user()->student_id;
        $this->last_name = Auth::user()->last_name;
        $this->first_name = Auth::user()->first_name;
        $this->username = Auth::user()->username;
        $this->email = Auth::user()->email;
        $this->yrlvl = Auth::user()->year_levels->yearlevel;
        $this->program = Auth::user()->programs->name;
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'username' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
        ]);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->dispatch('profile-updated', username: $user->username);
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function sendVerification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }
}; ?>

<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form wire:submit="updateProfileInformation" class="mt-6 space-y-6">

        <div>
            <x-input-label for="student_id" :value="__('Student ID')" />
            <x-text-input wire:model="student_id" id="student_id" type="text" class="mt-1 block w-full bg-gray-100"
                readonly />
        </div>

        <div>
            <x-input-label for="last_name" :value="__('Lastname')" />
            <x-text-input wire:model="last_name" id="last_name" type="text" class="mt-1 block w-full bg-gray-100"
                readonly />
        </div>

        <div>
            <x-input-label for="first_name" :value="__('Firstname')" />
            <x-text-input wire:model="first_name" id="first_name" type="text" class="mt-1 block w-full bg-gray-100"
                readonly />
        </div>

        <div>
            <x-input-label for="username" :value="__('username')" />
            <x-text-input wire:model="username" id="username" username="username" type="text"
                class="mt-1 block w-full" required autofocus autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('username')" />
        </div>

        <div>
            <x-input-label for="program" :value="__('Program')" />
            <x-text-input wire:model="program" id="program" type="text" class="mt-1 block w-full bg-gray-100"
                readonly />
        </div>

        <div>
            <x-input-label for="yrlvl" :value="__('Year Level')" />
            <x-text-input wire:model="yrlvl" id="yrlvl" type="text" class="mt-1 block w-full bg-gray-100"
                readonly />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input wire:model="email" id="email" username="email" type="email" class="mt-1 block w-full"
                required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !auth()->user()->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                        {{ __('Your email address is unverified.') }}

                        <button wire:click.prevent="sendVerification"
                            class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button style="background-color: #800000; color: white;">{{ __('Save') }}</x-primary-button>

            <x-action-message class="me-3" on="profile-updated">
                {{ __('Saved.') }}
            </x-action-message>
        </div>
    </form>
</section>
