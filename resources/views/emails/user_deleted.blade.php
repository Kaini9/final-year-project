<x-mail::message>
# Hello {{ $userName }},

We are writing to inform you that your FashionConnect account has been deleted by an administrator.

**Reason for deletion:**
{{ $reason ?: 'Violation of community guidelines.' }}

If you believe this is a mistake, you may contact our support team.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
