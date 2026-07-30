<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Password Reset</title>
</head>

<body style="font-family: Arial, sans-serif; background:#f3f4f6; padding:30px;">

<div style="
    max-width:600px;
    margin:auto;
    background:white;
    padding:30px;
    border-radius:15px;
    box-shadow:0 10px 30px rgba(0,0,0,.1);
">

    <h2 style="
        color:#1e40af;
        text-align:center;
    ">
        Supplier Evaluation System
    </h2>


    <p>
        Hello <strong>{{ $user->name }}</strong>,
    </p>


    <p>
        We received a request to reset your password.
        If this was you, click the button below to create a new password.
    </p>


    <div style="text-align:center;margin:30px 0;">

        <a href="{{ $url }}"
           style="
           background:#2563eb;
           color:white;
           padding:14px 25px;
           border-radius:10px;
           text-decoration:none;
           font-weight:bold;
           ">
            Reset Password
        </a>

    </div>


    <p>
        If you did not request a password reset, you can safely ignore this email.
    </p>


    <hr>


    <p style="
        font-size:12px;
        color:#6b7280;
        text-align:center;
    ">
        © {{ date('Y') }} Supplier Evaluation System
    </p>


</div>

</body>
</html>
