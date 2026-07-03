<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Booking confirmed</title>
</head>
<body style="margin:0; padding:0; background:#f4f6f9; font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;">
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f4f6f9; padding:32px 16px;">
<tr>
<td align="center">
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:520px; background:#ffffff; border-radius:16px; overflow:hidden; box-shadow:0 4px 24px rgba(8,29,58,0.08);">

    <tr>
        <td style="background:#081d3a; padding:32px 36px;">
            <table role="presentation" cellpadding="0" cellspacing="0">
                <tr>
                    <td style="width:32px;height:32px;border-radius:8px;background:#f6e304;text-align:center;vertical-align:middle;font-size:12px;font-weight:700;color:#081d3a;font-family:Arial,sans-serif;">SK</td>
                    <td style="padding-left:10px; font-size:18px; font-weight:800; color:#ffffff;">Spring<span style="color:#f6e304;">Kleaners</span></td>
                </tr>
            </table>
        </td>
    </tr>

    <tr>
        <td style="padding:36px;">
            <h1 style="margin:0 0 12px; font-size:20px; font-weight:800; color:#081d3a;">You're confirmed! ✅</h1>
            <p style="margin:0 0 20px; font-size:14px; line-height:1.6; color:#4b5563;">
                Hi {{ $booking->name }}, we've locked in your clean for <strong style="color:#081d3a;">{{ $booking->date->format('d M Y') }} at {{ $booking->time }}</strong>. Here's a quick summary.
            </p>

            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f8f9fc; border-radius:12px; margin-bottom:24px;">
                <tr>
                    <td style="padding:16px 20px;">
                        <table role="presentation" width="100%" cellpadding="0" cellspacing="4">
                            <tr>
                                <td style="font-size:12px; color:#8898aa; padding:4px 0;">Service</td>
                                <td style="font-size:13px; color:#081d3a; font-weight:600; text-align:right; padding:4px 0;">{{ \App\Support\Services::find($booking->service)['name'] ?? $booking->service }}</td>
                            </tr>
                            <tr>
                                <td style="font-size:12px; color:#8898aa; padding:4px 0;">Date &amp; time</td>
                                <td style="font-size:13px; color:#081d3a; font-weight:600; text-align:right; padding:4px 0;">{{ $booking->date->format('d M Y') }} &middot; {{ $booking->time }}</td>
                            </tr>
                            @if ($booking->address)
                            <tr>
                                <td style="font-size:12px; color:#8898aa; padding:4px 0;">Address</td>
                                <td style="font-size:13px; color:#081d3a; font-weight:600; text-align:right; padding:4px 0;">{{ $booking->address }}@if($booking->suburb), {{ $booking->suburb }}@endif</td>
                            </tr>
                            @endif
                        </table>
                    </td>
                </tr>
            </table>

            @if ($booking->deposit_amount > 0)
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#fefce8; border-left:3px solid #f6e304; border-radius:8px; margin-bottom:24px;">
                <tr>
                    <td style="padding:16px 20px; font-size:13px; color:#713f12; line-height:1.7;">
                        <strong>Deposit required: R{{ number_format((float) $booking->deposit_amount, 2) }}</strong><br>
                        Please pay via EFT to secure your date:<br>
                        {{ \App\Support\Company::bankName() }} &middot; Branch Code {{ \App\Support\Company::branchCode() }} &middot; Account No {{ \App\Support\Company::accountNo() }}<br>
                        {{ \App\Support\Company::referenceNote() }}
                    </td>
                </tr>
            </table>
            @endif

            <p style="margin:0; font-size:14px; line-height:1.6; color:#4b5563;">
                Questions before then? Just reply to this email or WhatsApp us on {{ \App\Support\Company::cell() }}.
            </p>
        </td>
    </tr>

    <tr>
        <td style="padding:20px 36px; border-top:1px solid #e8eaf0; text-align:center;">
            <p style="margin:0; font-size:11.5px; color:#9ca3af;">{{ \App\Support\Company::name() }} &middot; {{ \App\Support\Company::cell() }} &middot; {{ \App\Support\Company::email() }}</p>
        </td>
    </tr>

</table>
</td>
</tr>
</table>
</body>
</html>
