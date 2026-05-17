<x-mail::message>
# Congratulations, {{ $winner->name }}!

You are the winning bidder for the auction: **{{ $auction->title }}**.

Your winning bid was **${{ number_format($auction->current_price, 2) }}**.

To complete your purchase and secure your item, please complete your payment using the button below.

<x-mail::button :url="route('checkout.session', $auction)">
Pay Now
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
