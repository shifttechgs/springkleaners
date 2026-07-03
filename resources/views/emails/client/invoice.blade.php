<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Your invoice</title>
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
            <h1 style="margin:0 0 12px; font-size:20px; font-weight:800; color:#081d3a;">Your invoice is ready</h1>
            <p style="margin:0 0 20px; font-size:14px; line-height:1.6; color:#4b5563;">
                Hi {{ $booking->name }}, thanks again for choosing {{ \App\Support\Company::name() }}. Your invoice <strong style="color:#081d3a;">{{ $booking->invoice_number }}</strong> for the {{ $booking->date->format('d M Y') }} job is ready to view and download.
            </p>

            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f8f9fc; border-radius:12px; margin-bottom:24px;">
                <tr>
                    <td style="padding:16px 20px;">
                        <table role="presentation" width="100%" cellpadding="0" cellspacing="4">
                            <tr>
                                <td style="font-size:12px; color:#8898aa; padding:4px 0;">Invoice Number</td>
                                <td style="font-size:13px; color:#081d3a; font-weight:600; text-align:right; padding:4px 0;">{{ $booking->invoice_number }}</td>
                            </tr>
                            <tr>
                                <td style="font-size:12px; color:#8898aa; padding:4px 0;">Amount</td>
                                <td style="font-size:13px; color:#081d3a; font-weight:600; text-align:right; padding:4px 0;">R{{ number_format((float) ($booking->quoted_price ?? $booking->total ?? 0), 2) }}</td>
                            </tr>
                            <tr>
                                <td style="font-size:12px; color:#8898aa; padding:4px 0;">Status</td>
                                <td style="font-size:13px; color:#081d3a; font-weight:600; text-align:right; padding:4px 0;">{{ $booking->payment_status->label() }}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

            <table role="presentation" cellpadding="0" cellspacing="0">
                <tr>
                    <td style="border-radius:10px; background:#f6e304;">
                        <a href="{{ route('invoice.show', $booking->accepted_token) }}" target="_blank" style="display:inline-block; padding:14px 28px; font-size:14px; font-weight:700; color:#081d3a; text-decoration:none;">
                            View &amp; Download Invoice
                        </a>
                    </td>
                </tr>
            </table>
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
