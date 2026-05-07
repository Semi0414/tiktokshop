<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Welcome') }}</title>
</head>
<body style="margin:0;padding:0;background-color:#f4f4f7;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,'Helvetica Neue',Arial,sans-serif;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color:#f4f4f7;padding:24px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellspacing="0" cellpadding="0" style="max-width:600px;width:100%;background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,0.08);">
                    <tr>
                        <td style="background:linear-gradient(135deg,#ff0050 0%,#000000 100%);padding:28px 24px;text-align:center;">
                            <img src="{{ $logoUrl }}" alt="TikTok" width="120" height="auto" style="display:inline-block;max-width:140px;height:auto;border:0;" />
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:32px 28px 16px;text-align:center;">
                            <h1 style="margin:0 0 12px;font-size:22px;font-weight:700;color:#111827;line-height:1.35;">
                                {{ __('Welcome to TikStore') }}
                            </h1>
                            <p style="margin:0;font-size:16px;color:#4b5563;line-height:1.6;">
                                {{ __('Hi') }} <strong style="color:#111827;">{{ $sellerName }}</strong>,
                            </p>
                            <p style="margin:16px 0 0;font-size:15px;color:#6b7280;line-height:1.65;">
                                {{ __('Your seller account is ready. Log in to manage your shop, products, and orders from one place.') }}
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:8px 28px 32px;text-align:center;">
                            <a href="{{ $loginUrl }}" style="display:inline-block;padding:14px 32px;background-color:#ff0050;color:#ffffff;text-decoration:none;font-weight:700;font-size:15px;border-radius:999px;letter-spacing:0.02em;">
                                {{ __('Seller login') }}
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:0 28px 28px;">
                            <p style="margin:0;font-size:13px;color:#9ca3af;line-height:1.5;text-align:center;">
                                {{ __('If the button does not work, copy and paste this link into your browser:') }}
                            </p>
                            <p style="margin:8px 0 0;font-size:12px;color:#6b7280;word-break:break-all;text-align:center;">
                                <a href="{{ $loginUrl }}" style="color:#ff0050;">{{ $loginUrl }}</a>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:20px 24px;background-color:#f9fafb;border-top:1px solid #e5e7eb;text-align:center;">
                            <p style="margin:0;font-size:12px;color:#9ca3af;">
                                &copy; {{ date('Y') }} {{ config('app.name') }}. {{ __('All rights reserved.') }}
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
