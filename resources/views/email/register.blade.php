@component('mail::message')
    <p>Hello <b>{{ $user->name ?? '' }}</b>,</p>
    <p>Please Activate your account now by clicking below link.</p>
    <p><a href="https://buzzinguniverse.iqspark.org/activation-account/activation-account.html?token={{ $token }}">Activate
            your account</a></p>
    <p>For any further assistance or questions, feel free to reach out to our support team. We are here to assist you.</p>
    <p>Thank you for choosing {{ config('app.name') }}. We value your security and appreciate your cooperation.</p>
    <p>Best regards</p>
@endcomponent
