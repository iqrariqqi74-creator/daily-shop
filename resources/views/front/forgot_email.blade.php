<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Forgot Password</title>

    <style>
        body {
            margin: 0;
            padding: 0;
            background: #f5f5f5;
            font-family: Arial, Helvetica, sans-serif;
        }

        .email-wrapper {
            width: 100%;
            padding: 40px 0;
        }

        .email-container {
            width: 600px;
            max-width: 90%;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 5px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        }

        .email-header {
            background: #ff6666;
            color: #ffffff;
            text-align: center;
            padding: 25px;
        }

        .email-header h1 {
            margin: 0;
            font-size: 26px;
        }

        .email-body {
            padding: 35px;
            color: #333333;
        }

        .email-body h2 {
            margin-top: 0;
            font-size: 22px;
        }

        .email-body p {
            font-size: 15px;
            line-height: 1.7;
        }

        .reset-code {
            margin: 25px 0;
            padding: 15px;
            background: #f8f8f8;
            border: 1px dashed #ff6666;
            text-align: center;
            font-size: 26px;
            font-weight: bold;
            color: #ff6666;
            letter-spacing: 3px;
        }

        .reset-button {
            display: inline-block;
            padding: 12px 25px;
            background: #ff6666;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 3px;
            font-size: 14px;
            font-weight: bold;
        }

        .email-footer {
            padding: 20px;
            background: #fafafa;
            text-align: center;
            color: #777777;
            font-size: 12px;
        }
    </style>
</head>

<body>

<div class="email-wrapper">

    <div class="email-container">

        <!-- =====================================================
             HEADER
        ====================================================== -->

        <div class="email-header">

            <h1>DailyShop</h1>

        </div>


        <!-- =====================================================
             BODY
        ====================================================== -->

        <div class="email-body">

            <h2>
                Hello {{ $name }},
            </h2>

            <p>
                We received a request to change the password
                for your DailyShop account.
            </p>

            <p>
                Please click the button below to create a
                new password for your account.
            </p>


            <!-- =================================================
                 RESET CODE
            ================================================== -->

            <div class="reset-code">

                {{ $rand_id }}

            </div>


            <p>
                You can also use the reset code above if required.
            </p>


            <!-- =================================================
                 CHANGE PASSWORD BUTTON
            ================================================== -->

            <p style="text-align: center;">

                <a
                    href="{{ url('/forgot_password_change/' . $rand_id )}}"
                    class="reset-button"
                >
                    Change Password
                </a>

                    {{ url('/email-verification/' . $rand_id) }}


            </p>


            <p>
                If you did not request a password change,
                you can safely ignore this email.
            </p>

        </div>


        <!-- =====================================================
             FOOTER
        ====================================================== -->

        <div class="email-footer">

            © {{ date('Y') }} DailyShop.
            All rights reserved.

        </div>

    </div>

</div>

</body>
</html>
