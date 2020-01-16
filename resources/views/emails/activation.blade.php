@component('mail::message')

Thank you for registration in Mappyn

Activation Code : {{$client->activation_code}}

Please activate your account
@component('mail::button', ['url' => url('/activationPost') ])
Activate
@endcomponent

Thanks,<br>

-----------------------------------

شكرا للتسجيل في مابين 

كود التفعيل : {{$client->activation_code}}

يمكنك تفعيل حسابك من  خلال الرابط الاتي
@component('mail::button', ['url' => url('/activationPost') ])
تفعيل
@endcomponent

شكرا لكمٍ,<br>
{{ config('app.name') }}
@endcomponent

