<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<style>

* { margin: 0; padding: 0; box-sizing: border-box; }

@page { margin: 0; }

body {
    font-family: DejaVu Sans, Arial, sans-serif;
    font-size: 11px;
    color: #111827;
    background: #ffffff;
    line-height: 1.45;
}

.brand-bar { background: #081d3a; height: 5px; width: 100%; }

.wrap { padding: 22px 42px 56px; }

/* ── Header ──────────────────────────────────────────────── */
.hdr { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
.hdr td { vertical-align: top; padding: 0; }

.doc-type {
    font-size: 24px;
    font-weight: 700;
    color: #111827;
    letter-spacing: -0.5px;
    line-height: 1;
    margin-bottom: 14px;
}

.meta-lbl {
    font-size: 7.5px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: #9ca3af;
    margin-bottom: 1px;
}

.meta-val {
    font-size: 11px;
    font-weight: 400;
    color: #111827;
    margin-bottom: 8px;
}

.brand-mark {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    background: #f6e304;
    color: #081d3a;
    font-size: 12px;
    font-weight: 700;
    text-align: center;
    line-height: 32px;
    display: block;
    margin-left: auto;
    margin-bottom: 10px;
}

.co {
    text-align: right;
    font-size: 10.5px;
    color: #6b7280;
    line-height: 1.65;
}

.co-name {
    font-size: 12px;
    font-weight: 700;
    color: #111827;
    margin-bottom: 3px;
}

/* ── Divider ─────────────────────────────────────────────── */
.section-divider {
    border: none;
    border-top: 1px solid #f3f4f6;
    margin: 14px 0;
}

/* ── Bill To ─────────────────────────────────────────────── */
.section-lbl {
    font-size: 7.5px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: #9ca3af;
    margin-bottom: 5px;
}

.client-name {
    font-size: 12px;
    font-weight: 700;
    color: #111827;
    margin-bottom: 2px;
}

.client-line {
    font-size: 10.5px;
    color: #4b5563;
    margin-bottom: 1px;
}

/* ── Items table ─────────────────────────────────────────── */
.tbl { width: 100%; border-collapse: collapse; margin-top: 16px; }

.tbl thead tr { background: #081d3a; }
.tbl thead th {
    padding: 9px 13px;
    font-size: 9px;
    font-weight: 700;
    color: #fff;
    text-align: left;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.tbl thead th.r { text-align: right; }

.tbl tbody tr { page-break-inside: avoid; }
.tbl tbody tr:nth-child(even) { background: #f9fafb; }
.tbl tbody tr:nth-child(odd)  { background: #ffffff; }
.tbl tbody td {
    padding: 9px 13px;
    font-size: 11px;
    color: #374151;
    border-bottom: 1px solid #f3f4f6;
}
.tbl tbody td.r { text-align: right; }

/* ── Totals ──────────────────────────────────────────────── */
.totals {
    width: 100%;
    border-collapse: collapse;
    page-break-inside: avoid;
    page-break-before: avoid;
}
.totals td {
    padding: 3px 13px;
    font-size: 11px;
    color: #6b7280;
    text-align: right;
}
.totals .lc { width: 72%; }
.totals tr.sub td  { padding-top: 10px; color: #374151; }
.totals tr.grand td {
    padding-top: 8px;
    padding-bottom: 8px;
    font-size: 13px;
    font-weight: 700;
    color: #111827;
    border-top: 1.5px solid #d1d5db;
}

/* ── Cards ───────────────────────────────────────────────── */
.card-wrap { margin-top: 16px; page-break-inside: avoid; }

.card-lbl {
    font-size: 7.5px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: #9ca3af;
    margin-bottom: 5px;
}

.notes-card {
    background: #f9fafb;
    border-left: 3px solid #081d3a;
    padding: 10px 14px;
    font-size: 10.5px;
    color: #374151;
    line-height: 1.65;
    page-break-inside: avoid;
}

/* ── Disclaimer ──────────────────────────────────────────── */
.disc-box {
    margin-top: 14px;
    padding: 8px 12px;
    background: #fffbeb;
    border-left: 3px solid #f59e0b;
    font-size: 10px;
    color: #92400e;
    line-height: 1.55;
    page-break-inside: avoid;
}

/* ── Accept ──────────────────────────────────────────────── */
.accept-card {
    margin-top: 12px;
    padding: 9px 12px;
    background: #fefce8;
    border-left: 3px solid #f6e304;
    font-size: 10.5px;
    color: #713f12;
    line-height: 1.65;
    page-break-inside: avoid;
}

/* ── Footer ──────────────────────────────────────────────── */
.footer {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    padding: 9px 42px 12px;
    border-top: 1px solid #e5e7eb;
    text-align: center;
    background: #ffffff;
}

.footer-line { font-size: 8.5px; color: #9ca3af; line-height: 1.55; }

</style>
</head>
<body>

<div class="brand-bar"></div>

<div class="wrap">

    @php
        $lineItems = \App\Support\QuotePricing::lineItems($booking);
        $finalTotal = (float) ($booking->quoted_price ?? $booking->total ?? 0);
    @endphp

    {{-- Header --}}
    <table class="hdr">
        <tr>
            <td width="46%">
                <div class="doc-type">QUOTATION</div>
                <div class="meta-lbl">Quote Number</div>
                <div class="meta-val">SK-{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}</div>
                <div class="meta-lbl">Date Issued</div>
                <div class="meta-val">{{ ($booking->quote_sent_at ?? now())->format('d M Y') }}</div>
                <div class="meta-lbl">Service Date</div>
                <div class="meta-val">{{ $booking->date->format('d M Y') }} &middot; {{ $booking->time }}</div>
            </td>
            <td width="54%" align="right">
                <div class="brand-mark">SK</div>
                <div class="co">
                    <div class="co-name">{{ \App\Support\Company::name() }}</div>
                    @foreach (\App\Support\Company::addressLines() as $line)
                        {{ $line }}<br>
                    @endforeach
                    {{ \App\Support\Company::cell() }}<br>
                    {{ \App\Support\Company::email() }}
                </div>
            </td>
        </tr>
    </table>

    <hr class="section-divider">

    {{-- Bill To --}}
    <div class="section-lbl">Bill To</div>
    <div class="client-name">{{ $booking->name }}</div>
    @if($booking->address)
        <div class="client-line">{{ $booking->address }}@if($booking->suburb), {{ $booking->suburb }}@endif</div>
    @endif
    <div class="client-line">{{ $booking->phone }}</div>

    {{-- Line Items --}}
    <table class="tbl">
        <thead>
            <tr>
                <th>Service / Description</th>
                <th class="r" style="width:44px">Qty</th>
                <th class="r" style="width:106px">Unit Price</th>
                <th class="r" style="width:106px">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($lineItems as $item)
            <tr>
                <td>{{ $item['description'] }}</td>
                <td class="r">{{ $item['qty'] }}</td>
                <td class="r">R&nbsp;{{ number_format($item['unit_price'], 2) }}</td>
                <td class="r">R&nbsp;{{ number_format($item['total'], 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Totals --}}
    <table class="totals">
        <tr class="sub">
            <td class="lc">Subtotal</td>
            <td>R&nbsp;{{ number_format((float) ($booking->total ?? 0), 2) }}</td>
        </tr>
        <tr class="grand">
            <td class="lc">{{ $booking->quoted_price ? 'Quoted Price' : 'Estimated Total' }}</td>
            <td>R&nbsp;{{ number_format($finalTotal, 2) }}</td>
        </tr>
    </table>

    {{-- Notes --}}
    @if($booking->notes || $booking->admin_notes)
    <div class="card-wrap">
        <div class="notes-card">
            <div class="card-lbl">Notes</div>
            @if($booking->notes){{ $booking->notes }}@endif
            @if($booking->notes && $booking->admin_notes)<br>@endif
            @if($booking->admin_notes){{ $booking->admin_notes }}@endif
        </div>
    </div>
    @endif

    {{-- Deposit --}}
    @if ($booking->deposit_amount > 0)
    <div class="accept-card">
        <strong>Deposit Required: R&nbsp;{{ number_format((float) $booking->deposit_amount, 2) }}</strong><br>
        Please pay via EFT to secure your booking date:<br>
        {{ \App\Support\Company::bankName() }} &middot; Branch Code {{ \App\Support\Company::branchCode() }} &middot; Account No {{ \App\Support\Company::accountNo() }}<br>
        {{ \App\Support\Company::referenceNote() }}
        @if ($booking->deposit_paid_at)
            <br><strong>Deposit received {{ $booking->deposit_paid_at->format('d M Y') }}.</strong>
        @endif
    </div>
    @endif

    {{-- Disclaimer --}}
    <div class="disc-box">
        <strong>Please note:</strong>
        This is an estimate based on the details provided. The final price is confirmed after a quick property check — never without your sign-off.
    </div>

    {{-- Accept link --}}
    @if($booking->accepted_token)
    <div class="accept-card">
        <strong>How to Accept This Quote</strong><br>
        Visit: {{ route('quote.show', $booking->accepted_token) }}
    </div>
    @endif

    {{-- Footer — fixed to bottom of every page --}}
    <div class="footer">
        <div class="footer-line">
            <strong style="color:#6b7280;">{{ \App\Support\Company::name() }}</strong>
            &nbsp;&middot;&nbsp;{{ \App\Support\Company::email() }}
            &nbsp;&middot;&nbsp;{{ \App\Support\Company::cell() }}
        </div>
        <div class="footer-line" style="color:#d1d5db; margin-top:2px;">
            Generated {{ now()->format('d M Y \a\t H:i') }}&nbsp;&middot;&nbsp;Computer generated document
        </div>
    </div>

</div>

</body>
</html>
