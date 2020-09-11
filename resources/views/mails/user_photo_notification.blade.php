@extends('mails.layouts.notify_user_mail')

@section('content')
    <!-- Main Title -->
    <div class="body-title">
       Hola, {{ $mail_content->first_name . ' ' . $mail_content->last_name }}<br>
    </div>
    <div class="body-subtitle">
       Una de tus fotos fue eliminada por un administrador.
    </div>
@endsection()