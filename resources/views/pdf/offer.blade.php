<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #121212; }
        h1 { font-size: 32px; margin-bottom: 4px; }
        .title { font-size: 16px; color: #343434; }
        table.items { width: 100%; border-collapse: collapse; margin-top: 30px; }
        table.items th, table.items td { padding: 8px 10px; text-align: left; border-bottom: 1px solid #EEEEEE; }
        table.items th { background-color: #EEEEEE; }
        .text-right { text-align: right; }
        .totals { width: 300px; margin-left: auto; margin-top: 10px; }
        .totals td { border: none; padding: 6px 10px; }
        .totals .total-row { font-weight: 700; font-size: 14px; }
    </style>
</head>
<body>
    <h1>{{ $offer->offer_number }}</h1>
    <p class="title">{{ $offer->title }}</p>

    <table style="width: 100%; margin-top: 20px;">
        <tr>
            <td style="width: 50%; vertical-align: top; border: none;">
                <strong>{{ __('For') }}:</strong><br>
                <strong>{{ $offer->company?->name ?? '—' }}</strong><br>
                @if ($offer->contact)
                    {{ $offer->contact->first_name }} {{ $offer->contact->last_name }}<br>
                @endif
                {{ $offer->company?->address }}<br>
                {{ $offer->company?->city }} {{ $offer->company?->postal_code }}
            </td>
            <td style="width: 50%; vertical-align: top; border: none; line-height: 20px;">
                <strong>{{ __('Offer issued') }}:</strong> {{ $offer->offer_issued->format('d.m.Y.') }}<br>
                <strong>{{ __('Valid till') }}:</strong> {{ $offer->offer_valid?->format('d.m.Y.') ?? '—' }}<br>
                <strong>{{ __('Status') }}:</strong> {{ $offer->status->label() }}
            </td>
        </tr>
    </table>

    <table class="items">
        <thead>
            <tr>
                <th>{{ __('Description') }}</th>
                <th class="text-right">{{ __('Quantity') }}</th>
                <th class="text-right">{{ __('Price') }}</th>
                <th class="text-right">{{ __('Total') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($offer->offerItems as $item)
                <tr>
                    <td>{{ $item->description }}</td>
                    <td class="text-right">{{ number_format($item->quantity, 2) }}</td>
                    <td class="text-right">{{ number_format($item->unit_price, 2) }} {{ $offer->currency->value }}</td>
                    <td class="text-right">{{ number_format($item->total, 2) }} {{ $offer->currency->value }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="totals">
        <tr>
            <td>{{ __('Amount') }}:</td>
            <td class="text-right">{{ number_format($offer->subtotal, 2) }} {{ $offer->currency->value }}</td>
        </tr>
        <tr>
            <td>{{ __('Tax rate') }} ({{ $offer->tax_rate }}%):</td>
            <td class="text-right">{{ number_format($offer->tax_amount, 2) }} {{ $offer->currency->value }}</td>
        </tr>
        <tr class="total-row">
            <td>{{ __('Total') }}:</td>
            <td class="text-right">{{ number_format($offer->total, 2) }} {{ $offer->currency->value }}</td>
        </tr>
    </table>
</body>
</html>