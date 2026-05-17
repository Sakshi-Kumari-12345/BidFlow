<x-mail::message>
# Good news, {{ $seller->name }}!

You have received a payment of **${{ number_format($auction->current_price, 2) }}** for your item: **{{ $auction->title }}**.

The buyer ({{ $buyer->name }}) has successfully completed the checkout process. 
Please prepare the item for shipping or contact the buyer to arrange fulfillment.

<x-mail::button :url="route('auctions.show', $auction)">
View Auction Details
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
