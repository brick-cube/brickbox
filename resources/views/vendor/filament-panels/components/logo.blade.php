@php
$brandName = filament()->getBrandName();
$brandLogo = filament()->getBrandLogo();
@endphp

<div style="
    display: flex;
    flex-direction: row;
    align-items: center;
    justify-content: center;
    gap: 10px;
    width: 100%;
">
    {{-- Logo --}}
    @if (filled($brandLogo))
    <img
        src="{{ $brandLogo }}"
        alt="{{ $brandName }}"
        style="
                height: 38px;
                width: auto;
                border-radius: 6px;
                flex-shrink: 0;
            " />
    @endif

    {{-- Brand name --}}
    <span style="
        font-size: 1.35rem;    /* bigger heading */
        font-weight: 700;
        white-space: nowrap;
    ">
        {{ $brandName }}
    </span>
</div>