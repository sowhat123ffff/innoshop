{{-- Debug: Show if partial is rendered --}}
<div>DEBUG: PriceRangeFilter partial loaded</div>
<div id="price-range-filter" class="card mb-3">
    <div class="card-header">
        {{ __('PriceRangeFilter::common.price_range') }}
    </div>
    <div class="card-body">
        <div id="price-slider"
             data-min="{{ $minPrice }}"
             data-max="{{ $maxPrice }}"
             data-selected-min="{{ $selectedMin }}"
             data-selected-max="{{ $selectedMax }}"></div>
        <div class="d-flex justify-content-between mt-2">
            <span id="price-min-label">{{ $selectedMin }}</span>
            <span id="price-max-label">{{ $selectedMax }}</span>
        </div>
    </div>
</div>
