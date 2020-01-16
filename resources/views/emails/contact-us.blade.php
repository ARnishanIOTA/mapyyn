@component('mail::message')

{{$request->message}}
Thanks,<br>
{{ config('app.name') }}
@endcomponent
