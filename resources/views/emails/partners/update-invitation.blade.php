@component('mail::message')
# Update Your Partner Profile

Hello {{ $partner->first_name }},

You've been invited to update your partner profile information. 
Please click the button below to update your details.

@component('mail::button', ['url' => $url])
Update Profile
@endcomponent

This link will expire in {{ $expiration }}.

Thank you,<br>
{{ config('app.name') }}
@endcomponent
