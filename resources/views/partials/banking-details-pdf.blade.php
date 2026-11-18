{{-- Banking details block for the quote and invoice PDFs (DomPDF: tables + inline styles only). --}}
@php($bankRows = \App\Support\Company::bankingRows())
<div style="margin-top:12px; padding:10px 12px; background:#f8f9fc; border:1px solid #e3e7ef; border-radius:6px; page-break-inside:avoid;">
    <div style="font-size:8px; font-weight:bold; letter-spacing:0.08em; text-transform:uppercase; color:#5b6779; margin-bottom:6px;">Banking Details{{ filled($intro ?? null) ? ' — '.$intro : '' }}</div>
    <table style="width:100%; border-collapse:collapse;">
        @foreach (array_chunk($bankRows, 3) as $row)
            <tr>
                @foreach ($row as $detail)
                    <td style="width:33%; padding:2px 8px 4px 0; vertical-align:top;">
                        <span style="display:block; font-size:7.5px; color:#8a94a6;">{{ $detail['label'] }}</span>
                        <span style="display:block; font-size:9.5px; font-weight:bold; color:#081d3a;">{{ $detail['value'] }}</span>
                    </td>
                @endforeach
                @for ($i = count($row); $i < 3; $i++)<td style="width:33%;"></td>@endfor
            </tr>
        @endforeach
    </table>
    @if (filled(\App\Support\Company::referenceNote()))
        <div style="margin-top:4px; font-size:8.5px; color:#081d3a;">{{ \App\Support\Company::referenceNote() }}</div>
    @endif
</div>
