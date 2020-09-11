<!DOCTYPE html>
<html lang="es" dir="ltr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://fonts.googleapis.com/css?family=Lato:300,400,600,900&display=swap" rel="stylesheet">
        <style>
            *{
                -webkit-box-sizing: border-box;
                -moz-box-sizing: border-box;
                -ms-box-sizing: border-box;
                -o-box-sizing: border-box;
                box-sizing: border-box;
            }
            .body{
                background-color:#F6F6F6;
                text-align:center;
                padding:10px 80px;
                max-width:100%;
            }
            .content-container{
                background-color:#FFFFFF!important;
                box-shadow: 0 10px 30px 0 rgba(0, 0, 0, 0.05);
                margin:20px auto;
                border-radius:6px;
                -webkit-border-radius: 6px;
                -moz-border-radius: 6px;
                padding:10px;
                text-align: center;
                width: 100%;
                max-width:600px;
                margin: auto auto 14px auto;
                padding-bottom: 50px;
            }
            .body-title{
                font-family: 'Lato',sans-serif;
                font-size: 20px;
                font-weight: 600;
                color: #4f5561;
            }
            .body-subtitle{
                max-width:80%;
                margin:20px auto;
                font-family: 'Lato',sans-serif;
                font-size:16px;
                color:#4f5561;
            }
            .body-text{
                max-width:55%;
                margin:20px auto;
                font-family: 'Lato',sans-serif;
                font-size: 14px;
                line-height:20px;
                color:#4f5561;
            }
            .link-button{
                display: block;
                height:40px;
                max-width: 240px;
                border-radius: 40px;
                background-color: #0f03b8;
                font-family: 'Lato',sans-serif;
                font-size: 14px;
                font-weight: 400;
                letter-spacing:1.2px;
                text-align: center;
                color: #ffffff !important;
                padding:0px 10px 8px 10px;
                margin:30px auto 30px auto;
                line-height:37px;
                text-decoration:none;
                text-transform:uppercase;
                white-space:nowrap;
            }
            .url-text{
                width: 100%;
                display:inline-block;
                padding:14px 15px;
                word-wrap:break-word;
                max-width:450px;
                font-family: 'Lato',sans-serif;
                font-size: 14px;
                font-weight: 300;
                text-align: left;
                color: #4f5561;
                border-radius: 6px;
                background-color: #fafafa;
            }
            @media only screen and (max-width:800px) {
                .body{
                    padding:10px 20px;
                }
                .content-container{
                    margin:10px auto;
                    padding:30px;
                }
                .body-title{
                    font-size:20px;
                }
                .body-subtitle{
                    max-width:95%;
                    font-size:16px;
                }
                .body-text{
                    max-width:95%;
                    font-size:14px;
                    line-height:18px;
                    margin:25px auto;
                }
                .link-button{
                    width:230px;
                    font-size:12px;
                    padding:5px;
                    height:auto;
                }
            }
        </style>
        @yield('extra_head')
    </head>
    <body class="body">

        <div class="content-container">
            
            @yield('content')

        </div>
    </body>
</html>
