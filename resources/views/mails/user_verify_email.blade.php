@extends('mails.layouts.notify_user_mail')

@section('content')
    <!-- Main Title -->
    <div class="body-title">
       Hola, {{ $mail_content->first_name . ' ' . $mail_content->last_name }}
    </div>
    <div class="body-subtitle">
       Da click en el botón para confirmar tu correo.
    </div>
    <!-- Link Button -->
    <a class="link-button" href="{{ $mail_content->verify_link }}">
        Confirmar Correo
    </a>
    <!-- Body Text -->
    <div class="body-text">
        ¿No funciona el botón?<br/>
        Copia y pega la URL en tu navegador
    </div>
    <!-- URL Text-->
    <div class="url-text">
        {{ $mail_content->verify_link }}
    </div>
@endsection()