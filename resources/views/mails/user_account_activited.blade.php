@extends('mails.layouts.notify_user_mail')

@section('content')
    <!-- Main Title -->
    <div class="body-title">
       Hola, {{ $mail_content->first_name . ' ' . $mail_content->last_name }}<br>
       Tu correo ha sido confirmado
    </div>
    <div class="body-subtitle">
       Da click en el botón para iniciar sesión.
    </div>
    <!-- Link Button -->
    <a class="link-button" href="{{ $mail_content->login_link }}">
        Iniciar Sesión
    </a>
    <!-- Body Text -->
    <div class="body-text">
        ¿No funciona el botón?<br/>
        Copia y pega la URL en tu navegador
    </div>
    <!-- URL Text-->
    <p class="url-text">
        {{ $mail_content->login_link }}
    </p>
@endsection()