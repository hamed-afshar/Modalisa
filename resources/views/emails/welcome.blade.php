@component('mail::message')
# Welcome to Modalisa
{{ $user->name }} Thanks for your registeration!
The body of your message.

@component('mail::button', ['url' => ''])
Button Text
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
