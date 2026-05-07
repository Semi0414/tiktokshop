<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $emailTitle ?? __('superadmin::app.sellers.view.welcome-email-default-subject') }}</title>
</head>
<body style="margin:0;padding:0;background-color:#f3f4f6;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,'Helvetica Neue',Arial,sans-serif;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color:#f3f4f6;padding:24px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellspacing="0" cellpadding="0" border="0" style="max-width:600px;width:100%;background-color:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,0.08);">
                    <tr>
                        <td style="background:linear-gradient(135deg,#ff0050 0%,#ff4081 50%,#00f2ea 100%);padding:28px 24px;text-align:center;">
                            @if (config('storefront-branding.legacy_tiktok_branding'))
                            <img src="https://upload.wikimedia.org/wikipedia/en/thumb/a/a9/TikTok_logo.svg/200px-TikTok_logo.svg.png" alt="TikTok" width="120" height="36" style="display:inline-block;max-width:140px;height:auto;filter:brightness(0) invert(1);">
                            <p style="margin:16px 0 0;font-size:13px;font-weight:600;letter-spacing:0.12em;color:rgba(255,255,255,0.95);text-transform:uppercase;">TikStore Seller Hub</p>
                            @else
                            <p style="margin:0;font-size:20px;font-weight:800;color:#ffffff;letter-spacing:-0.02em;">{{ config('storefront-branding.marketplace_name') }}</p>
                            <p style="margin:16px 0 0;font-size:13px;font-weight:600;letter-spacing:0.12em;color:rgba(255,255,255,0.95);text-transform:uppercase;">Seller account</p>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:32px 28px 24px;">
                            <h1 style="margin:0 0 16px;font-size:22px;line-height:1.35;color:#111827;">{{ __('superadmin::app.sellers.view.welcome-email-heading', ['name' => e($sellerName)]) }}</h1>
                            <div style="margin:0;font-size:15px;line-height:1.65;color:#4b5563;">
                                {!! $messageBody !!}
                            </div>
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" style="margin:24px 0;">
                                <tr>
                                    <td align="center" style="border-radius:8px;background:linear-gradient(135deg,#ff0050,#e6004a);">
                                        <a href="{{ $loginUrl }}" target="_blank" rel="noopener noreferrer" style="display:inline-block;padding:14px 32px;font-size:15px;font-weight:700;color:#ffffff;text-decoration:none;">{{ __('superadmin::app.sellers.view.welcome-email-cta') }}</a>
                                    </td>
                                </tr>
                            </table>
                            <p style="margin:0 0 8px;font-size:13px;line-height:1.5;color:#6b7280;">
                                {{ __('superadmin::app.sellers.view.welcome-email-link-hint') }}
                            </p>
                            <p style="margin:0;font-size:12px;word-break:break-all;color:#2563eb;">
                                <a href="{{ $loginUrl }}" style="color:#2563eb;">{{ $loginUrl }}</a>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:20px 28px 28px;border-top:1px solid #e5e7eb;background-color:#f9fafb;">
                            <p style="margin:0;font-size:12px;line-height:1.5;color:#9ca3af;text-align:center;">
                                {{ __('superadmin::app.sellers.view.welcome-email-footer') }}
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
