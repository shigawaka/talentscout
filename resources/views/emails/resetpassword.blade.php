<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta charset="utf-8">
    </head>
    <body>
        <h2>Password Reset</h2>

        <div>
            You have requested to reset your password.
            Please follow the link below to reset your password
            {{ URL::to('reset/verify/' . $confirmation_code) }}.<br/>
            Ignore this message if you did not request password reset.
        </div>

    </body>
</html>