<div class="filter-sidebar" id="filterSidebar">
  <div class="filter-sidebar-item">
    <div class="title">{{ __('front/category.category') }}</div>
    <div class="content">
      <div class="accordion" id="filter-category">
        @foreach ($categories as $key => $category)
        <div class="accordion-item">
          <div class="accordion-title">
            <a href="{{ $category['url'] }}" class="">{{ $category['name'] }}</a>
            @if ($category['children'])
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#filter-collapse-{{ $key }}"></button>
            @endif
          </div>
          @if ($category['children'])
          <div id="filter-collapse-{{ $key }}" class="accordion-collapse collapse" data-bs-parent="#filter-category">
            <div class="accordion-body">
              <div class="accordion" id="filter-category-{{ $key }}">
                @foreach ($category['children'] as $child)
                <div class="accordion-item">
                  <div class="accordion-title">
                    <a href="{{ $child['url'] }}" class="">{{ $child['name'] }}</a>
                    @if (isset($child['children']) && $child['children'])
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#filter-collapse-{{ $key }}-{{ $loop->index }}"></button>
                    @endif
                  </div>
                  @if (isset($child['children']) && $child['children'])
                  <div id="filter-collapse-{{ $key }}-{{ $loop->index }}" class="accordion-collapse collapse" data-bs-parent="#filter-category-{{ $key }}">
                    <div class="accordion-body">
                      <div class="accordion" id="filter-category-{{ $key }}-{{ $loop->index }}">
                        @foreach ($child['children'] as $subChild)
                        <div class="accordion-item">
                          <div class="accordion-title">
                            <a href="{{ $subChild['url'] }}" class="">{{ $subChild['name'] }}</a>
                          </div>
                        </div>
                        @endforeach
                      </div>
                    </div>
                  </div>
                  @endif
                </div>
                @endforeach
              </div>
            </div>
          </div>
          @endif
        </div>
        @endforeach
      </div>
    </div>
  </div>

  @if(isset($max_price) && $max_price > 0)
  <div class="filter-sidebar-item">
    <div class="title">PRICE</div>
    <div class="content">
      <div class="price-filter-container">
        <div class="price-slider-container">
          <div class="multi-range">
            <input type="range" min="0" max="{{ $max_price }}" value="{{ $price_start ?? 0 }}" id="price-min-slider" class="lower">
            <input type="range" min="0" max="{{ $max_price }}" value="{{ $price_end ?? $max_price }}" id="price-max-slider" class="upper">
          </div>
        </div>
        <div class="price-inputs mt-3">
          <div class="price-input">
            <label>RM</label>
            <input type="number" id="price-min-input" value="{{ $price_start ?? 0 }}" min="0" max="{{ $max_price }}">
          </div>
          <div class="price-input">
            <label>RM</label>
            <input type="number" id="price-max-input" value="{{ $price_end ?? $max_price }}" min="0" max="{{ $max_price }}">
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="filter-sidebar-item">
    <div class="title">AVAILABILITY</div>
    <div class="content">
      <div class="form-check">
        <input class="form-check-input" type="checkbox" id="in-stock-checkbox" {{ isset($in_stock) && $in_stock ? 'checked' : '' }}>
        <label class="form-check-label" for="in-stock-checkbox">In Stock</label>
      </div>
    </div>
  </div>

  <div class="filter-sidebar-item">
    <div class="d-flex justify-content-between">
      <button class="btn btn-primary" id="apply-filter-btn">Apply Filter</button>
      <button class="btn btn-dark" id="reset-filter-btn">Clear</button>
    </div>
  </div>
  @endif
</div>

<div class="overlay" id="overlay" style="display: none;"></div>

@push('header')
<style>
  .multi-range {
    position: relative;
    height: 30px;
    margin-bottom: 10px;
  }

  .multi-range input[type="range"] {
    position: absolute;
    width: 100%;
    height: 5px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    pointer-events: none;
    -webkit-appearance: none;
    appearance: none;
    outline: none;
    z-index: 2;
  }

  .multi-range input[type="range"]::-webkit-slider-runnable-track {
    width: 100%;
    height: 5px;
    background: #e5e5e5;
    border: none;
    border-radius: 3px;
  }

  .multi-range input.upper::-webkit-slider-runnable-track {
    background: linear-gradient(to right, #E91E63 0%, #E91E63 var(--upper-fill, 50%), #e5e5e5 var(--upper-fill, 50%));
  }

  .multi-range input.lower::-webkit-slider-runnable-track {
    background: linear-gradient(to right, #e5e5e5 0%, #e5e5e5 var(--lower-fill, 0%), #E91E63 var(--lower-fill, 0%));
  }

  .multi-range input[type="range"]::-webkit-slider-thumb {
    -webkit-appearance: none;
    appearance: none;
    width: 18px;
    height: 18px;
    border-radius: 50%;
    background: #E91E63;
    cursor: pointer;
    pointer-events: auto;
    border: 2px solid white;
    box-shadow: 0 0 3px rgba(0, 0, 0, 0.3);
    margin-top: -6px;
  }

  .multi-range input[type="range"]::-moz-range-track {
    width: 100%;
    height: 5px;
    background: #e5e5e5;
    border: none;
    border-radius: 3px;
  }

  .multi-range input.upper::-moz-range-track {
    background: linear-gradient(to right, #E91E63 0%, #E91E63 var(--upper-fill, 50%), #e5e5e5 var(--upper-fill, 50%));
  }

  .multi-range input.lower::-moz-range-track {
    background: linear-gradient(to right, #e5e5e5 0%, #e5e5e5 var(--lower-fill, 0%), #E91E63 var(--lower-fill, 0%));
  }

  .multi-range input[type="range"]::-moz-range-thumb {
    width: 18px;
    height: 18px;
    border-radius: 50%;
    background: #E91E63;
    cursor: pointer;
    pointer-events: auto;
    border: 2px solid white;
    box-shadow: 0 0 3px rgba(0, 0, 0, 0.3);
  }

  .multi-range input.lower {
    z-index: 1;
  }

  .multi-range input.upper {
    z-index: 2;
  }
</style>
@endpush

@push('footer')
<script>
  function toggleSidebar() {
    if ($(window).width() < 768) {
      $('#filterSidebar').css('transform', 'translateX(0)');
      $('#overlay').show();
    }
  }

  $(document).ready(function() {
    $('#toggleFilterSidebar').on('click', function() {
      $('#filterSidebar').css('transform', 'translateX(0)');
      $('#overlay').show();
    });

    $('#overlay').on('click', function() {
      $('#filterSidebar').css('transform', 'translateX(100%)');
      $(this).hide();
    });

    $(document).on('click', function(event) {
      if ($(window).width() < 768 && !$(event.target).closest('#filterSidebar, #toggleFilterSidebar').length) {
        $('#filterSidebar').css('transform', 'translateX(100%)');
        $('#overlay').hide();
      }
    });

    $('#filter-category a').each(function() {
      if ($(this).attr('href') === window.location.href) {
        $(this).addClass('text-primary');
        $(this).parents('.accordion-item').each(function() {
          $(this).find('.accordion-button').attr('aria-expanded', true).siblings('a').addClass('text-primary');
          $(this).find('.accordion-collapse').addClass('show');
        });
      }
    });

    // Price range slider functionality
    const priceMinSlider = document.getElementById('price-min-slider');
    const priceMaxSlider = document.getElementById('price-max-slider');
    const priceMinInput = document.getElementById('price-min-input');
    const priceMaxInput = document.getElementById('price-max-input');

    if (priceMinSlider && priceMaxSlider) {
      // Update the price input when sliders change
      priceMinSlider.addEventListener('input', function() {
        // Ensure min doesn't exceed max
        if (parseInt(this.value) > parseInt(priceMaxSlider.value)) {
          this.value = priceMaxSlider.value;
        }
        priceMinInput.value = this.value;
        updateSliderTrack();
      });

      priceMaxSlider.addEventListener('input', function() {
        // Ensure max doesn't go below min
        if (parseInt(this.value) < parseInt(priceMinSlider.value)) {
          this.value = priceMinSlider.value;
        }
        priceMaxInput.value = this.value;
        updateSliderTrack();
      });

      // Update the sliders when inputs change
      priceMinInput.addEventListener('change', function() {
        // Ensure min doesn't exceed max
        if (parseInt(this.value) > parseInt(priceMaxInput.value)) {
          this.value = priceMaxInput.value;
        }
        priceMinSlider.value = this.value;
        updateSliderTrack();
      });

      priceMaxInput.addEventListener('change', function() {
        // Ensure max doesn't go below min
        if (parseInt(this.value) < parseInt(priceMinInput.value)) {
          this.value = priceMinInput.value;
        }
        priceMaxSlider.value = this.value;
        updateSliderTrack();
      });

      // Initialize slider track
      updateSliderTrack();

      // Apply filter button
      $('#apply-filter-btn').on('click', function() {
        applyFilters();
      });

      // Reset filter button
      $('#reset-filter-btn').on('click', function() {
        resetFilters();
      });
    }
  });

  function updateSliderTrack() {
    const priceMinSlider = document.getElementById('price-min-slider');
    const priceMaxSlider = document.getElementById('price-max-slider');

    if (priceMinSlider && priceMaxSlider) {
      const minVal = parseInt(priceMinSlider.value);
      const maxVal = parseInt(priceMaxSlider.value);
      const minPercent = (minVal / priceMinSlider.max) * 100;
      const maxPercent = (maxVal / priceMaxSlider.max) * 100;

      priceMinSlider.style.setProperty('--lower-fill', `${minPercent}%`);
      priceMaxSlider.style.setProperty('--upper-fill', `${maxPercent}%`);
    }
  }

  function applyFilters() {
    const priceMinInput = document.getElementById('price-min-input');
    const priceMaxInput = document.getElementById('price-max-input');
    const inStockCheckbox = document.getElementById('in-stock-checkbox');

    let url = inno.removeURLParameters(window.location.href, 'price_start', 'price_end', 'in_stock');

    // Add price filter parameters
    if (priceMinInput && parseInt(priceMinInput.value) > 0) {
      url = inno.updateQueryStringParameter(url, 'price_start', priceMinInput.value);
    }

    if (priceMaxInput && parseInt(priceMaxInput.value) < parseInt(priceMaxInput.max)) {
      url = inno.updateQueryStringParameter(url, 'price_end', priceMaxInput.value);
    }

    // Add in-stock filter parameter
    if (inStockCheckbox && inStockCheckbox.checked) {
      url = inno.updateQueryStringParameter(url, 'in_stock', '1');
    }

    // Navigate to the filtered URL
    window.location.href = url;
  }

  function resetFilters() {
    const priceMinSlider = document.getElementById('price-min-slider');
    const priceMaxSlider = document.getElementById('price-max-slider');
    const priceMinInput = document.getElementById('price-min-input');
    const priceMaxInput = document.getElementById('price-max-input');
    const inStockCheckbox = document.getElementById('in-stock-checkbox');

    if (priceMinSlider) {
      priceMinSlider.value = 0;
      if (priceMinInput) priceMinInput.value = 0;
    }

    if (priceMaxSlider) {
      priceMaxSlider.value = priceMaxSlider.max;
      if (priceMaxInput) priceMaxInput.value = priceMaxSlider.max;
    }

    if (inStockCheckbox) inStockCheckbox.checked = false;

    updateSliderTrack();

    // Remove all filter parameters from URL
    let url = inno.removeURLParameters(window.location.href, 'price_start', 'price_end', 'in_stock');
    window.location.href = url;
  }

  $(window).resize(function() {
    if ($(window).width() >= 768) {
      $('#filterSidebar').css('transform', 'translateX(0)');
      $('#overlay').hide();
    } else {
      $('#filterSidebar').css('transform', 'translateX(100%)');
      $('#overlay').hide();
    }
  });

  $(window).on('resize', function() {
    if ($(window).width() === 768) {
      $('#filterSidebar').css('transform', 'translateX(0)');
    }
  });
</script>
@endpush