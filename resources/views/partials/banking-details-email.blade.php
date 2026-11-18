{{-- Banking details for client emails (table layout, inline styles). --}}
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f8f9fc; border-radius:12px; margin-bottom:24px;">
    <tr>
        <td style="padding:16px 20px;">
            <p style="margin:0 0 8px; font-size:12px; font-weight:700; letter-spacing:0.06em; text-transform:uppercase; color:#5b6779;">Banking details</p>
            <table role="presentation" width="100%" cellpadding="0" cellspacing="4">
                @foreach (\App\Support\Company::bankingRows() as $row)
                    <tr>
                        <td style="font-size:12px; color:#8898aa; padding:3px 0;">{{ $row['label'] }}</td>
                        <td style="font-size:13px; color:#081d3a; font-weight:600; text-align:right; padding:3px 0;">{{ $row['value'] }}</td>
                    </tr>
                @endforeach
            </table>
            @if (filled(\App\Support\Company::referenceNote()))
                <p style="margin:8px 0 0; font-size:12.5px; color:#081d3a;">{{ \App\Support\Company::referenceNote() }}</p>
            @endif
        </td>
    </tr>
</table>
