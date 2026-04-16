<x-mail::message>
# Hello {{ $user->name }},

We are writing to inform you that a recent **{{ $contentType }}** you published has been removed by our moderation team.

**Reason for removal:**
{{ $reason ?: 'Violation of content guidelines.' }}

Please ensure future content complies with our community standards.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
