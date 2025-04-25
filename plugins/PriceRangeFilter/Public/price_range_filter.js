// noUiSlider initialization and AJAX filter logic
$(function () {
    // Debug: Start of plugin JS
    console.log('[PriceRangeFilter] JS loaded');
    // DOM injection: insert slider into sidebar if not present
    var $sidebar = $('.col-md-3, .filter-sidebar, #sidebar, aside').first();
    console.log('[PriceRangeFilter] Sidebar found:', $sidebar.length > 0);
    if ($sidebar.length && !$('#price-range-filter').length) {
        console.log('[PriceRangeFilter] Injecting slider via AJAX...');
        $.ajax({
            url: '/plugin/pricerangefilter/sidebar',
            dataType: 'html',
            success: function (html) {
                console.log('[PriceRangeFilter] Slider HTML loaded');
                $sidebar.prepend(html);
                // Retry slider initialization until noUiSlider is loaded
                function tryInitSlider(retries) {
                    if (typeof noUiSlider !== 'undefined' && $('#prf-price-slider').length) {
                        initSlider();
                    } else if (retries > 0) {
                        setTimeout(function() { tryInitSlider(retries - 1); }, 200);
                    } else {
                        console.log('[PriceRangeFilter] noUiSlider not loaded after retries');
                    }
                }
                tryInitSlider(10); // Retry up to 10 times
            },
            error: function(xhr, status, error) {
                console.log('[PriceRangeFilter] AJAX error:', status, error);
            }
        });
    } else {
        if (!($sidebar.length)) {
            console.log('[PriceRangeFilter] No sidebar found for injection.');
        }
        if ($('#price-range-filter').length) {
            console.log('[PriceRangeFilter] Slider already present, skipping injection.');
        }
    }

    function initSlider() {
        var min = parseFloat($('#prf-price-slider').data('min'));
        var max = parseFloat($('#prf-price-slider').data('max'));
        var selectedMin = parseFloat($('#prf-price-slider').data('selected-min'));
        var selectedMax = parseFloat($('#prf-price-slider').data('selected-max'));
        var slider = document.getElementById('prf-price-slider');
        if (!slider.noUiSlider) {
            noUiSlider.create(slider, {
                start: [selectedMin, selectedMax],
                connect: true,
                range: { 'min': min, 'max': max },
                tooltips: [true, true],
                format: {
                    to: function (value) { return Math.round(value); },
                    from: function (value) { return Number(value); }
                }
            });
            slider.noUiSlider.on('change', function (values, handle, unencoded, tap, positions, noUiEvent) {
                if (noUiEvent && noUiEvent.stopImmediatePropagation) {
                    noUiEvent.stopImmediatePropagation();
                }
                var minVal = Math.round(values[0]);
                var maxVal = Math.round(values[1]);
                $.ajax({
                    url: '/plugin/pricerangefilter/filter',
                    data: {
                        price_start: minVal,
                        price_end: maxVal
                    },
                    dataType: 'json',
                    success: function (res) {
                        if (res && res.html) {
                            // Replace the product list (adjust selector as needed)
                            $('.category-wrap').parent().html(res.html);
                        }
                    }
                });
            });
            slider.noUiSlider.on('update', function (values) {
                $('#price-min-label').text(values[0]);
                $('#price-max-label').text(values[1]);
            });
        }
    }

    // If slider is already present on page load, initialize it
    if (typeof noUiSlider !== 'undefined' && $('#prf-price-slider').length) {
        initSlider();
    }
});
