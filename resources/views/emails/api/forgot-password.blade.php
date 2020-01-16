@component('mail::message')

Reset Password Code : {{$model->activation_code}}

Thanks,<br>
 
------------------------------------------------

كود استعادة كلمة المرور : {{$model->activation_code}}

شكرا,<br>
{{ config('app.name') }}
@endcomponent
