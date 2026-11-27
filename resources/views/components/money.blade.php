@props(['amount' => 0])

<span {{ $attributes }}>{{ config('extrack.currency_symbol') }}{{ number_format((float) $amount, 2) }}</span>
