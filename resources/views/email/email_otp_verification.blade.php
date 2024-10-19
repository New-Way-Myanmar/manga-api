<x-mail::message>
Hello

<x-mail::panel>
Otp Code - {{ $otp }}
</x-mail::panel>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
