@component('mail::message')
# Update Your Speaker Profile

Hello {{ $speaker->first_name }},

You've been invited to update your speaker profile information in our system.

Click the button below to access your profile editor. This link will expire in 48 hours.

@component('mail::button', ['url' => route('speakers.edit-with-token', $token)])
Update Profile
@endcomponent

If you did not expect this email, no action is needed.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
