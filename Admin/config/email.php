<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require "PHPMailer/src/Exception.php";
require "PHPMailer/src/PHPMailer.php";
require "PHPMailer/src/SMTP.php";

function header_tmp(){
    $domain= _DOMAIN_;
    return  <<<_header
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <title>Lumienux</title>
            <meta name="keywords" content="solar, inverter, panel, installation, lumienux, maintenance" />
            <meta name="author" content="David Aladeokin" />
            <meta name="description" content="Lumienux solar" />
            <meta name="viewport" content="width=device-width, initial-scale=1.0" />
            <style>
            header{
                background-color:#212529;
            }
            header img{
                margin: 0%;
                width: 25vw;
            }
            main{
                padding:5%;
                font-size: 1em;
            }
            button{
                padding:1vh 1vw;
                margin-left:5vw;
            }
            .product{
                margin-top: 2vh;
                clear: both;
            }
            .product h2{
                border-bottom: var(--bd2);
                font-style: oblique;
            }
            footer{
                border-top:1px solid #000000;
                padding:1em;
                font-size: 0.8em;
            }
            footer div{
                margin: 1vh auto;
            }
            address{
                display:inline;
            }

            /*      RWD      */
            @media all and (min-width:692px){
                header img{
                    width: 20vw;
                }

            }
            @media all and (min-width:992px){
                header img{
                    width: 15vw;
                }
            }

            </style>
            </head>
        <body>
            <header>
                <a href="$domain">
                    <img alt="Lumienux Logo" src="$domain/img/brand_logo.png" />
                </a>
            </header>
            <main>
    _header;
}


function footer_tmp(){
    $phone= _ADMIN_PHONE_;
    $whatsapp= _WHATSAPP_;
    $facebook= _FACEBOOK_;
    $instagram= _INSTAGRAM_;
    $email= _ADMIN_;
    return  <<<_footer
                </main>
                <footer>
                    <div>Whatsapp: <a href="$whatsapp">$phone</a></div>
                    <div>Facebook: <a href="$facebook">$facebook</a></div>
                    <div>Instagram: <a href="$instagram">$instagram</a></div>
                    <div>Address: <address>Odo-ona kekere, New garage, Challenge Ibadan, Oyo state, Nigeria.</address></div>
                </footer>
            </body>
        </html>
    _footer;
}

//Admin Authentication Link format
function adminAuth($link){
    return <<<_auth
            <p>Hello, Click on the button below to complete your registration on Lumienux portal</p>
            <a href="$link"><button>Complete Registration</button></a>
            <p>If you did not initiate this response, Kindly ignore this mail.</p>
        _auth;
}


function send_mail($to, $sub, $mes){
    $mail= new PHPMailer();
    $mail->Host= _MAIL_HOST_;
    $mail->SMTPAuth= true;
    $mail->Username= _MAIL_USER_;
    $mail->Password= 'AZack#8448';
    //$mail->SMTPSecure= 'ssl';
    $mail->Port= 465;

 
    $mail->setFrom(_MAIL_USER_);
    $mail->addReplyTo(_MAIL_REPLY_, _MAIL_REPLY_NAME_);
    $mail->addAddress($to);
    $mail->isHTML(true);

    $mail->Subject= $sub;
    $mail->Body= header_tmp().$mes.footer_tmp();

    $mail->send();
}