<div class="filter-sidebar" id="filterSidebar">
  <div class="filter-sidebar-item">
    <div class="title">{{ __('front/category.category') }}</div>
    <div class="content">
      <div class="accordion" id="filter-category">
        @foreach ($categories as $key => $category)
        <div class="accordion-item">
          <div class="accordion-title">
            <div class="category-title-wrapper">
              <a href="{{ $category['url'] }}" class="category-title {{ request()->url() == $category['url'] ? 'active' : '' }}">{{ $category['name'] }}</a>
              <a href="{{ $category['url'] }}" class="category-subtitle-link {{ request()->url() == $category['url'] ? 'active' : '' }}">
                @if ($category['name'] === '开运饰品')
                  Fortune<br>Accessories
                @elseif ($category['name'] === '代烧')
                  Burn On<br>Behalf
                @elseif ($category['name'] === '风水产品')
                  Feng Shui<br>Products
                @elseif ($category['name'] === '法会')
                  Praying<br>Ceremony
                @elseif ($category['name'] === '神料')
                  Praying<br>Supplies
                @elseif ($category['name'] === '风水服务')
                  Feng Shui<br>Services
                @elseif ($category['name'] === '风水资讯')
                  Feng Shui<br>Info
                @elseif ($category['name'] === '关于我们')
                  About<br>Us
                @else
                  Category<br>Subtitle
                @endif
              </a>
            </div>
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
                    <div class="category-title-wrapper">
                      <a href="{{ $child['url'] }}" class="category-title {{ request()->url() == $child['url'] ? 'active' : '' }}">{{ $child['name'] }}</a>
                      <a href="{{ $child['url'] }}" class="category-subtitle-link subcategory {{ request()->url() == $child['url'] ? 'active' : '' }}">
                        @if ($child['name'] === '开运饰品')
                          Fortune<br>Accessories
                        @elseif ($child['name'] === '代烧')
                          Burn On<br>Behalf
                        @elseif ($child['name'] === '风水产品')
                          Feng Shui<br>Products
                        @elseif ($child['name'] === '法会')
                          Praying<br>Ceremony
                        @elseif ($child['name'] === '神料')
                          Praying<br>Supplies
                        @elseif ($child['name'] === '风水服务')
                          Feng Shui<br>Services
                        @elseif ($child['name'] === '风水资讯')
                          Feng Shui<br>Info
                        @elseif ($child['name'] === '关于我们')
                          About<br>Us
                        @else
                          Sub<br>Category
                        @endif
                      </a>
                    </div>
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
                            <div class="category-title-wrapper">
                              <a href="{{ $subChild['url'] }}" class="category-title {{ request()->url() == $subChild['url'] ? 'active' : '' }}">{{ $subChild['name'] }}</a>
                              <a href="{{ $subChild['url'] }}" class="category-subtitle-link subsubcategory {{ request()->url() == $subChild['url'] ? 'active' : '' }}">
                                @if ($subChild['name'] === '开运饰品')
                                  Fortune<br>Accessories
                                @elseif ($subChild['name'] === '代烧')
                                  Burn On<br>Behalf
                                @elseif ($subChild['name'] === '风水产品')
                                  Feng Shui<br>Products
                                @elseif ($subChild['name'] === '法会')
                                  Praying<br>Ceremony
                                @elseif ($subChild['name'] === '神料')
                                  Praying<br>Supplies
                                @elseif ($subChild['name'] === '风水服务')
                                  Feng Shui<br>Services
                                @elseif ($subChild['name'] === '风水资讯')
                                  Feng Shui<br>Info
                                @elseif ($subChild['name'] === '关于我们')
                                  About<br>Us
                                @else
                                  Sub<br>Item
                                @endif
                              </a>
                            </div>
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
  /* Category title and subtitle styles */
  .category-title-wrapper {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    width: 100%;
  }

  .category-title {
    font-weight: bold;
    text-decoration: none !important;
    position: relative;
    display: block;
    width: 100%;
    color: #333; /* Default color for non-selected categories */
  }

  .category-title.active,
  .category-subtitle-link.active {
    color: #E91E63 !important; /* Red color for selected category */
    font-weight: bold;
  }

  /* Add a special class that we can apply with JavaScript */
  .category-title.current-category,
  .category-subtitle-link.current-category {
    color: #E91E63 !important;
    font-weight: bold;
  }

  .category-subtitle-link {
    display: inline-block;
    font-size: 12px;
    color: #888;
    margin-top: 0;
    padding: 0;
    text-decoration: none;
    transition: color .3s;
    position: relative;
    line-height: 1.1;
  }

  .category-subtitle-link.subcategory {
    font-size: 11px;
  }

  .category-subtitle-link.subsubcategory {
    font-size: 10px;
  }

  .category-subtitle-link:after {
    content: "";
    position: absolute;
    left: 0;
    right: 0;
    bottom: -2px;
    border-bottom: 2px solid #888;
    width: 0;
    transition: width .3s;
  }

  /* Hover styles for non-active items */
  .category-title:not(.active):hover {
    color: #555;
  }

  .category-subtitle-link:not(.active):hover,
  .accordion-title:hover .category-subtitle-link:not(.active) {
    color: #555;
  }

  /* Underline effect for hover and active states */
  .category-subtitle-link:hover:after,
  .accordion-title:hover .category-subtitle-link:after,
  .category-subtitle-link.active:after {
    width: 100%;
    left: 0;
    right: auto;
  }

  /* Active category underline color */
  .category-subtitle-link.active:after {
    border-bottom-color: #E91E63;
  }

  /* Adjust accordion title spacing for subtitles */
  .accordion-title.has-subtitle {
    padding: 8px 0;
  }

  .accordion-title.has-subtitle .accordion-button {
    align-self: flex-start;
    margin-top: 5px;
  }

  .filter-sidebar .accordion-item .accordion-title {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
  }

  /* Price range slider styles */
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

  // Main document ready function for all sidebar functionality
  $(document).ready(function() {
    // Add has-subtitle class to accordion titles with subtitles
    $('.accordion-title').each(function() {
      if ($(this).find('.category-subtitle-link').length) {
        $(this).addClass('has-subtitle');
      }
    });

    // Check all category links (including subcategories and sub-subcategories)
    $('.filter-sidebar a.category-title').each(function() {
      // Get current URL and link URL for comparison
      let currentUrl = window.location.href.split('?')[0]; // Remove query parameters
      let linkUrl = $(this).attr('href').split('?')[0]; // Remove query parameters

      // Also try decoding both URLs for comparison
      currentUrl = decodeURIComponent(currentUrl);
      linkUrl = decodeURIComponent(linkUrl);

      console.log('Comparing:', currentUrl, linkUrl); // Debug output

      // Check if this is the active category
      if (currentUrl === linkUrl || window.location.href === $(this).attr('href')) {
        console.log('Found active category:', $(this).text()); // Debug output

        // Add active class to the title and its subtitle
        $(this).addClass('active');
        $(this).siblings('.category-subtitle-link').addClass('active');

        // Expand all parent accordions
        $(this).parents('.accordion-item').each(function() {
          $(this).find('.accordion-button').attr('aria-expanded', true);
          $(this).find('.accordion-collapse').addClass('show');
        });
      }
    });

    // If no exact match was found, try matching by category name
    if ($('.filter-sidebar a.category-title.active').length === 0) {
      // Try to find the category by checking if its name appears in the URL or page title
      let pageTitle = document.title;
      $('.filter-sidebar a.category-title').each(function() {
        let categoryName = $(this).text().trim();
        if (pageTitle.indexOf(categoryName) !== -1 || window.location.href.indexOf(categoryName) !== -1) {
          $(this).addClass('active');
          $(this).siblings('.category-subtitle-link').addClass('active');

          // Expand all parent accordions
          $(this).parents('.accordion-item').each(function() {
            $(this).find('.accordion-button').attr('aria-expanded', true);
            $(this).find('.accordion-collapse').addClass('show');
          });
        }
      });
    }

    // Direct check for specific categories - this ensures they're highlighted when selected
    // This is a fallback in case the URL matching doesn't work
    let pageTitle = document.title;

    // Force highlight for '开运饰品' category if it's in the URL or page title
    $('.filter-sidebar a.category-title').each(function() {
      let categoryText = $(this).text().trim();

      // Check if this category name is in the URL or page title
      if (window.location.href.indexOf(categoryText) !== -1 ||
          pageTitle.indexOf(categoryText) !== -1 ||
          document.referrer.indexOf(categoryText) !== -1) {

        console.log('Found category in URL/title:', categoryText);
        $(this).addClass('current-category');
        $(this).siblings('.category-subtitle-link').addClass('current-category');

        // Expand all parent accordions
        $(this).parents('.accordion-item').each(function() {
          $(this).find('.accordion-button').attr('aria-expanded', true);
          $(this).find('.accordion-collapse').addClass('show');
        });
      }

      // Special case for '开运饰品' category
      if (categoryText === '开运饰品') {
        if (window.location.href.indexOf('开运饰品') !== -1 ||
            pageTitle.indexOf('开运饰品') !== -1 ||
            pageTitle.indexOf('Fortune') !== -1 ||
            document.referrer.indexOf('开运饰品') !== -1) {

          console.log('Highlighting 开运饰品 category');
          $(this).addClass('current-category');
          $(this).siblings('.category-subtitle-link').addClass('current-category');

          // Expand all parent accordions
          $(this).parents('.accordion-item').each(function() {
            $(this).find('.accordion-button').attr('aria-expanded', true);
            $(this).find('.accordion-collapse').addClass('show');
          });
        }
      }
    });

    // Mobile sidebar toggle functionality
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

    // Price range slider functionality
    const priceMinSlider = document.getElementById('price-min-slider');
    const priceMaxSlider = document.getElementById('price-max-slider');
    const priceMinInput = document.getElementById('price-min-input');
    const priceMaxInput = document.getElementById('price-max-input');

    if (priceMinSlider && priceMaxSlider) {
      console.log('Price slider elements found, initializing...');

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
        console.log('Apply filter button clicked');
        applyFilters();
      });

      // Reset filter button
      $('#reset-filter-btn').on('click', function() {
        console.log('Reset filter button clicked');
        resetFilters();
      });
    } else {
      console.log('Price slider elements not found');
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
    console.log('Applying filters...');
    const priceMinInput = document.getElementById('price-min-input');
    const priceMaxInput = document.getElementById('price-max-input');
    const inStockCheckbox = document.getElementById('in-stock-checkbox');

    // Get current URL
    let url = window.location.href;

    // Remove existing filter parameters
    if (typeof inno !== 'undefined' && inno.removeURLParameters) {
      url = inno.removeURLParameters(url, 'price_start', 'price_end', 'in_stock');
    } else {
      // Fallback if inno utility is not available
      url = removeURLParam(url, 'price_start');
      url = removeURLParam(url, 'price_end');
      url = removeURLParam(url, 'in_stock');
    }

    console.log('URL after removing parameters:', url);

    // Add price filter parameters
    if (priceMinInput && parseInt(priceMinInput.value) > 0) {
      if (typeof inno !== 'undefined' && inno.updateQueryStringParameter) {
        url = inno.updateQueryStringParameter(url, 'price_start', priceMinInput.value);
      } else {
        // Fallback
        url = addURLParam(url, 'price_start', priceMinInput.value);
      }
    }

    if (priceMaxInput && parseInt(priceMaxInput.value) < parseInt(priceMaxInput.max)) {
      if (typeof inno !== 'undefined' && inno.updateQueryStringParameter) {
        url = inno.updateQueryStringParameter(url, 'price_end', priceMaxInput.value);
      } else {
        // Fallback
        url = addURLParam(url, 'price_end', priceMaxInput.value);
      }
    }

    // Add in-stock filter parameter
    if (inStockCheckbox && inStockCheckbox.checked) {
      if (typeof inno !== 'undefined' && inno.updateQueryStringParameter) {
        url = inno.updateQueryStringParameter(url, 'in_stock', '1');
      } else {
        // Fallback
        url = addURLParam(url, 'in_stock', '1');
      }
    }

    console.log('Final URL:', url);

    // Navigate to the filtered URL
    window.location.href = url;
  }

  // Fallback URL parameter functions in case inno utility is not available
  function removeURLParam(url, parameter) {
    const urlParts = url.split('?');
    if (urlParts.length < 2) return url;

    const prefix = encodeURIComponent(parameter) + '=';
    const parts = urlParts[1].split(/[&;]/g);

    // Reverse iteration to handle removing multiple instances
    for (let i = parts.length - 1; i >= 0; i--) {
      if (parts[i].lastIndexOf(prefix, 0) !== -1) {
        parts.splice(i, 1);
      }
    }

    return urlParts[0] + (parts.length > 0 ? '?' + parts.join('&') : '');
  }

  function addURLParam(url, key, value) {
    const separator = url.indexOf('?') !== -1 ? '&' : '?';
    return url + separator + encodeURIComponent(key) + '=' + encodeURIComponent(value);
  }

  function resetFilters() {
    console.log('Resetting filters...');
    const priceMinSlider = document.getElementById('price-min-slider');
    const priceMaxSlider = document.getElementById('price-max-slider');
    const priceMinInput = document.getElementById('price-min-input');
    const priceMaxInput = document.getElementById('price-max-input');
    const inStockCheckbox = document.getElementById('in-stock-checkbox');

    // Reset slider and input values
    if (priceMinSlider) {
      priceMinSlider.value = 0;
      if (priceMinInput) priceMinInput.value = 0;
    }

    if (priceMaxSlider) {
      priceMaxSlider.value = priceMaxSlider.max;
      if (priceMaxInput) priceMaxInput.value = priceMaxSlider.max;
    }

    if (inStockCheckbox) inStockCheckbox.checked = false;

    // Update the slider track appearance
    updateSliderTrack();

    // Remove all filter parameters from URL
    let url = window.location.href;

    if (typeof inno !== 'undefined' && inno.removeURLParameters) {
      url = inno.removeURLParameters(url, 'price_start', 'price_end', 'in_stock');
    } else {
      // Fallback if inno utility is not available
      url = removeURLParam(url, 'price_start');
      url = removeURLParam(url, 'price_end');
      url = removeURLParam(url, 'in_stock');
    }

    console.log('Reset URL:', url);
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