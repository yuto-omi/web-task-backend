<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f4f4f7; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f4f4f7; padding: 40px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <tr>
                        <td style="background-color: #4f46e5; padding: 32px; text-align: center;">
                            <h1 style="color: #ffffff; margin: 0; font-size: 24px;">{{ config('app.name') }}</h1>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 32px;">
                            <h2 style="color: #1a1a2e; margin: 0 0 16px;">Welcome, {{ $userName }}!</h2>
                            <p style="color: #51545e; font-size: 16px; line-height: 1.6; margin: 0 0 24px;">
                                Your account has been created successfully. Here are your account details:
                            </p>
                            <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f4f4f7; border-radius: 6px; padding: 20px; margin-bottom: 24px;">
                                <tr>
                                    <td style="padding: 8px 20px;">
                                        <strong style="color: #1a1a2e;">Name:</strong>
                                        <span style="color: #51545e;">{{ $userName }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 8px 20px;">
                                        <strong style="color: #1a1a2e;">Email:</strong>
                                        <span style="color: #51545e;">{{ $userEmail }}</span>
                                    </td>
                                </tr>
                            </table>
                            <p style="color: #51545e; font-size: 14px; line-height: 1.6; margin: 0;">
                                If you did not create this account, please disregard this email.
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="background-color: #f4f4f7; padding: 20px; text-align: center;">
                            <p style="color: #9a9ea6; font-size: 12px; margin: 0;">
                                &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
