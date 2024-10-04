@component('mail::message')
    <p>Hello <b>{{ $reciever->name ?? '' }}</b>,</p>
    <p>Dear <b>{{ $user->name }} </b>has sent you invitation link.</p>
    <p>Please click on below link to accept the invitation.</p>
    <p><a href="https://buzzinguniverse.iqspark.org/invitation-request?token={{ $invitation->token }}">Accept Invitation</a>
    </p>
    <p>For any further assistance or questions, feel free to reach out to our support team. We are here to assist you.</p>
    <p>Thank you for choosing {{ config('app.name') }}. We value your security and appreciate your cooperation.</p>
    <p>Best regards</p>
@endcomponent
