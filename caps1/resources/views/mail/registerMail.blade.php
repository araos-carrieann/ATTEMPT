<x-mail::message>
    Dear New eBookada <strong>{{ $data['usrname'] }}</strong>,

    We’re thrilled to have you on board! Your registration has been approved, and your account is now active. You can log in and start exploring right away.
    <x-mail::button :url="config('app.url')">
        Start Exploring
    </x-mail::button>

    If you have any questions or need assistance, feel free to reach out to us at libraraypupunisan@gmail.com.

    Enjoy reading,
    {{ config('app.name') }}
</x-mail::message>
