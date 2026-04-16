<x-mail::message>
# Hello {{ $user->name }},

We are writing to inform you that your FashionConnect account has been temporarily suspended.

**Reason for suspension:**
{{ $reason ?: 'Violation of community rules.' }}

Your account will remain suspended until **{{ $suspendedUntil->format('F j, Y, g:i a') }}**. Until this time, you will not be able to log in or interact with the platform.

If you have questions, please contact support.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
