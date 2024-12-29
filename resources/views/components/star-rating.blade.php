@if ($rating)
    @for ($i = 1; $i <= 5; $i++)
        @if ($i <= floor($rating))
            ★
        @elseif ($i == floor($rating) + 1 && $rating - floor($rating) >= 0.5)
            ⯪
        @else
            ☆
        @endif
    @endfor
@else
    '☆☆☆☆☆'
@endif
