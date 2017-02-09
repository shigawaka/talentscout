<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta charset="utf-8">
    </head>
    <body>
        <h2>Resend link</h2>

        <div>
            Please follow the link below to activate your account!
            {{ URL::to('reset/verify/' . $confirmation_code) }}.<br/>
            Ignore this message if you did not request password reset.
        </div>

    </body>
</html>