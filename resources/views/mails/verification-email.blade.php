@component('mail::message')

<h2>{{ _('Welcome!') }}</h2>

{{ _('Click the follwoing link to verify your email') }}
@component('mail::button', ['url' => $url, 'color' => 'green'])
    Activate
@endcomponent

{{ _('Thank you.') }}

@endcomponent