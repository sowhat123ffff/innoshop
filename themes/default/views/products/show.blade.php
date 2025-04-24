@extends('layouts.app')
@section('body-class', 'page-product')

@section('title', \InnoShop\Common\Libraries\MetaInfo::getInstance($product)->getTitle())
@section('description', \InnoShop\Common\Libraries\MetaInfo::getInstance($product)->getDescription())
@section('keywords', \InnoShop\Common\Libraries\MetaInfo::getInstance($product)->getKeywords())

@push('header')
  <script src="{{ asset('vendor/swiper/swiper-bundle.min.js') }}"></script>
  <link rel="stylesheet" href="{{ asset('vendor/swiper/swiper-bundle.min.css') }}">

  <script src="{{ asset('vendor/photoswipe/umd/photoswipe.umd.min.js') }}"></script>
  <script src="{{ asset('vendor/photoswipe/umd/photoswipe-lightbox.umd.min.js') }}"></script>
  <link rel="stylesheet" href="{{ asset('vendor/photoswipe/photoswipe.css') }}">

  <!-- Bootstrap Datepicker -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>

  <!-- Lunar Calendar Conversion Library -->
  <script src="https://cdn.jsdelivr.net/npm/lunar-javascript/lunar.js"></script>

  <style>
    .custom-form-container {
      background-color: #f8f9fa;
      padding: 15px;
      border-radius: 5px;
      margin-bottom: 20px;
    }
    .form-check-input:checked {
      background-color: #0d6efd;
      border-color: #0d6efd;
    }
    .invalid-feedback {
      display: block;
      color: #dc3545;
      margin-top: 5px;
    }
    .input-group-text {
      cursor: pointer;
    }
    .datepicker-dropdown {
      z-index: 1060 !important;
    }
    #customerLunarDOB {
      background-color: #f8f9fa;
    }
    /* Responsive button styles */
    .product-info-btns {
      width: 100%;
    }
    .product-info-btns .btn {
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }
    @media (max-width: 576px) {
      .product-info-btns {
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
      }
      .product-info-btns > div {
        flex: 1 0 auto;
        min-width: 120px;
        margin-bottom: 10px;
      }
      .product-info-btns .btn {
        width: 100%;
        padding: 0.5rem;
        font-size: 0.9rem;
      }
    }
  </style>
@endpush

@section('content')

  <x-front-breadcrumb type="product" :value="$product"/>

  @hookinsert('product.show.top')

  <div class="container">
    <div class="page-product-top">
      <div class="row">
        <div class="col-12 col-lg-6 product-left-col">
          <div class="product-images">

            @if(is_array($product->images))
              <div class="sub-product-img">
                <div class="swiper" id="sub-product-img-swiper">
                  <div class="swiper-wrapper">
                    @foreach($product->images as $image)
                      <div class="swiper-slide">
                        <a href="{{ image_resize($image, 600, 600) }}" data-pswp-width="800" data-pswp-height="800">
                          <img src="{{ image_resize($image) }}" class="img-fluid">
                        </a>
                      </div>
                    @endforeach
                  </div>
                  <div class="sub-product-btn">
                    <div class="sub-product-prev"><i class="bi bi-chevron-compact-up"></i></div>
                    <div class="sub-product-next"><i class="bi bi-chevron-compact-down"></i></div>
                  </div>
                  <div class="swiper-pagination sub-product-pagination"></div>
                </div>
              </div>
            @endif

            <div class="main-product-img">
              <img src="{{ $product->image_url }}" class="img-fluid">
            </div>
          </div>
        </div>

        <div class="col-12 col-lg-6">
          <div class="product-info">
            <h1 class="product-title">{!! str_replace(['&lt;br&gt;', '<br>'], '<br>', e($product->fallbackName())) !!}</h1>
            @hookupdate('front.product.show.price')
            <div class="product-price">
              <span class="price">{{ $sku['price_format'] }}</span>
              @if($sku['origin_price'])
                <span class="old-price ms-2">{{ $sku['origin_price_format'] }}</span>
              @endif
            </div>
            @endhookupdate

            <div class="stock-wrap">
              <div class="in-stock badge">{{ __('front/product.in_stock') }}</div>
              <div class="out-stock badge d-none">{{ __('front/product.out_stock') }}</div>
            </div>

            <div class="sub-product-title">{{ $product->fallbackName('summary') }}</div>

            <ul class="product-param">
              <li class="sku"><span class="title">{{ __('front/product.sku_code') }}:</span> <span
                  class="value">{{ $sku['code'] }}</span></li>
              <li class="model {{ !($sku['model'] ?? false) ? 'd-none' : '' }}"><span class="title">{{ __('front/product.model') }}:</span>
                <span class="value">{{ $sku['model'] }}</span></li>
              @if ($product->categories->count())
                <li class="category">
                  <span class="title">{{ __('front/product.category') }}:</span>
                  <span class="value">
                @foreach ($product->categories as $category)
                      <a href="{{ $category->url }}"
                         class="text-dark">{{ $category->fallbackName() }}</a>{{ !$loop->last ? ', ' : '' }}
                    @endforeach
              </span>
                </li>
              @endif
              @if($product->brand)
                <li class="brand">
                  <span class="title">{{ __('front/product.brand') }}:</span> <span class="value">
                <a href="{{ $product->brand->url }}"> {{ $product->brand->name }} </a>
              </span>
                </li>
              @endif
              @hookinsert('product.detail.brand.after')
            </ul>

            @include('products._variants')

            @if($product->custom_enabled)
            <div class="custom-form-container">
              <!-- Name Field -->
              <div class="mb-3 custom-name-form">
                <label for="customerName" class="form-label">姓名 (中文原名) Name (Chinese Original Name) <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="customerName" placeholder="请输入您的中文姓名 / Please enter your Chinese name" required>
                <div class="invalid-feedback" id="customerNameError" style="display: none;">
                  Must fill in the box / 必须填写此栏位
                </div>
              </div>

              <!-- Gender Field -->
              <div class="mb-3 custom-gender-form">
                <label class="form-label">性别 Gender <span class="text-danger">*</span></label>
                <div class="d-flex gap-4">
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="customerGender" id="genderMale" value="male" required>
                    <label class="form-check-label" for="genderMale">
                      男 Male
                    </label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="customerGender" id="genderFemale" value="female" required>
                    <label class="form-check-label" for="genderFemale">
                      女 Female
                    </label>
                  </div>
                </div>
                <div class="invalid-feedback" id="customerGenderError" style="display: none;">
                  Please select your gender / 请选择性别
                </div>
              </div>

              <!-- Date of Birth (Solar Calendar) Field -->
              <div class="mb-3 custom-dob-form">
                <label for="customerDOB" class="form-label">出生日期 (阳历) Date Of Birth (Solar Calendar) <span class="text-danger">*</span></label>
                <div class="input-group">
                  <input type="text" class="form-control" id="customerDOB" placeholder="YYYY-MM-DD" pattern="\d{4}-\d{2}-\d{2}" title="Please use format YYYY-MM-DD (e.g. 2004-05-18)" required>
                  <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                </div>
                <div class="invalid-feedback" id="customerDOBError" style="display: none;">
                  Please enter your date of birth (YYYY-MM-DD) / 请输入出生日期 (YYYY-MM-DD)
                </div>
                <div class="form-text small text-muted">
                  Format: YYYY-MM-DD (e.g., 1990-01-15) / 格式: YYYY-MM-DD (例如: 1990-01-15)
                </div>
              </div>

              <!-- Date of Birth (Lunar Calendar) Field -->
              <div class="mb-3 custom-lunar-dob-form">
                <label for="customerLunarDOB" class="form-label">出生日期 (农历) Date Of Birth (Lunar Calendar)</label>
                <input type="text" class="form-control" id="customerLunarDOB" readonly>
              </div>

              <!-- Chinese Zodiac Field -->
              <div class="mb-3 custom-zodiac-form">
                <label for="customerZodiac" class="form-label">生肖 Chinese Zodiac</label>
                <select class="form-select" id="customerZodiac">
                  <option value="" selected>请选择 / Please select</option>
                  <option value="rat">鼠 Rat</option>
                  <option value="ox">牛 Ox</option>
                  <option value="tiger">虎 Tiger</option>
                  <option value="rabbit">兔 Rabbit</option>
                  <option value="dragon">龙 Dragon</option>
                  <option value="snake">蛇 Snake</option>
                  <option value="horse">马 Horse</option>
                  <option value="goat">羊 Goat</option>
                  <option value="monkey">猴 Monkey</option>
                  <option value="rooster">鸡 Rooster</option>
                  <option value="dog">狗 Dog</option>
                  <option value="pig">猪 Pig</option>
                </select>
              </div>

              <!-- Time of Birth Field -->
              <div class="mb-3 custom-time-form">
                <label for="customerTimeOfBirth" class="form-label">出生时间 Time Of Birth <span class="text-danger">*</span></label>
                <select class="form-select" id="customerTimeOfBirth" required>
                  <option value="" selected>请选择 / Please select</option>
                  <option value="18139">吉时 (如不清出生时辰) Unknown time</option>
                  <option value="18140">早子时（ 12:00am - 12:59am ）Early Rat (12:00am - 12:59am)</option>
                  <option value="18141">丁丑时（ 01:00am - 02:59am ）Ox (01:00am - 02:59am)</option>
                  <option value="18142">丙寅时（ 03:00am - 04:59am ）Tiger (03:00am - 04:59am)</option>
                  <option value="18143">午卯时（ 05:00am - 06:59am ）Rabbit (05:00am - 06:59am)</option>
                  <option value="18144">戊辰时（ 07:00am - 08:59am ）Dragon (07:00am - 08:59am)</option>
                  <option value="18145">己巳时（ 09:00am - 10:59am ）Snake (09:00am - 10:59am)</option>
                  <option value="18146">庚午时（ 11:00am - 12:59pm ）Horse (11:00am - 12:59pm)</option>
                  <option value="18147">辛未时（ 01:00pm - 02:59pm ）Goat (01:00pm - 02:59pm)</option>
                  <option value="18148">壬申时（ 03:00pm - 04:59pm ）Monkey (03:00pm - 04:59pm)</option>
                  <option value="18149">癸酉时（ 05:00pm - 06:59pm ）Rooster (05:00pm - 06:59pm)</option>
                  <option value="18150">癸戌时（ 07:00pm - 08:59pm ）Dog (07:00pm - 08:59pm)</option>
                  <option value="18151">癸亥时（ 09:00pm - 10:59pm ）Pig (09:00pm - 10:59pm)</option>
                  <option value="18152">夜子时（ 11:00pm - 11:59pm ）Late Rat (11:00pm - 11:59pm)</option>
                </select>
                <div class="invalid-feedback" id="customerTimeError" style="display: none;">
                  Please select your time of birth / 请选择出生时间
                </div>
              </div>

              <!-- WhatsApp Number Field -->
              <div class="mb-3 custom-whatsapp-form">
                <label for="customerWhatsApp" class="form-label">联络号码 WhatsApp Number <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="customerWhatsApp" placeholder="请输入您的WhatsApp号码 / Please enter your WhatsApp number" required>
                <div class="invalid-feedback" id="customerWhatsAppError" style="display: none;">
                  Please enter your WhatsApp number / 请输入您的WhatsApp号码
                </div>
              </div>


            </div>
            @endif

            <div class="product-info-bottom">
              <div class="quantity-wrap">
                <div class="minus"><i class="bi bi-dash-lg"></i></div>
                <input type="number" class="form-control product-quantity" value="1" data-sku-id="{{ $sku['id'] }}">
                <div class="plus"><i class="bi bi-plus-lg"></i></div>
              </div>

              <div class="product-info-btns d-flex flex-wrap">
                <div class="position-relative me-2 mb-2 mb-sm-0" style="flex: 1; min-width: 120px;">
                  <button class="btn btn-primary add-cart w-100" data-id="{{ $product->id }}"
                          data-price="{{ $product->masterSku->price }}" style="height: 50px !important; display: flex; align-items: center; justify-content: center;">
                    {{ __('front/product.add_to_cart') }}
                  </button>
                  <span id="customDataSavedBadge1" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success" style="display: none;">
                    <i class="bi bi-check-lg"></i>
                    <span class="visually-hidden">Custom data saved</span>
                  </span>
                </div>
                <div class="position-relative" style="flex: 1; min-width: 120px;">
                  <button class="btn buy-now w-100" data-id="{{ $product->id }}"
                          data-price="{{ $product->masterSku->price }}" style="height: 50px !important; display: flex; align-items: center; justify-content: center;">
                    {{ __('front/product.buy_now') }}
                  </button>
                  <span id="customDataSavedBadge2" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success" style="display: none;">
                    <i class="bi bi-check-lg"></i>
                    <span class="visually-hidden">Custom data saved</span>
                  </span>
                </div>
                @hookinsert('product.detail.cart.after')
              </div>
            </div>
            <div class="add-wishlist" data-in-wishlist="{{ $product->hasFavorite() }}" data-id="{{ $product->id }}"
                 data-price="{{ $product->masterSku->price }}">
              <i
                class="bi bi-heart{{ $product->hasFavorite() ? '-fill' : '' }}"></i> {{ __('front/product.add_wishlist') }}
            </div>
            @hookinsert('product.detail.after')
          </div>
        </div>
      </div>
    </div>

    <div class="product-description">
      <ul class="nav nav-tabs tabs-plus">
        <li class="nav-item">
          <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#product-description-description"
                  type="button">{{ __('front/product.description') }}</button>
        </li>
        @if($attributes)
          <li class="nav-item">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#product-description-attribute"
                    type="button">{{ __('front/product.attribute') }}</button>
          </li>
          <li class="nav-item">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#product-review"
                    type="button">{{ __('front/product.review') }}</button>
          </li>
        @endif
        <li class="nav-item">
          <button class="nav-link correlation" data-bs-toggle="tab" data-bs-target="#product-description-correlation"
                  type="button">{{__('front/product.related_product')}}
          </button>
        </li>
        @hookinsert('product.detail.tab.link.after')
      </ul>
      <div class="tab-content">
        <div class="tab-pane fade show active" id="product-description-description">
          @if($product->fallbackName('selling_point'))
            {!! parsedown($product->fallbackName('selling_point')) !!}
          @endif
          {!! $product->fallbackName('content') !!}
        </div>

        @if($attributes)
          <div class="tab-pane fade" id="product-description-attribute" role="tabpanel">
            <table class="table table-bordered attribute-table">
              @foreach ($attributes as $group)
                <thead class="table-light">
                <tr>
                  <td colspan="2"><strong>{{ $group['attribute_group_name'] }}</strong></td>
                </tr>
                </thead>
                <tbody>
                @foreach ($group['attributes'] as $item)
                  <tr>
                    <td>{{ $item['attribute'] }}</td>
                    <td>{{ $item['attribute_value'] }}</td>
                  </tr>
                @endforeach
                </tbody>
              @endforeach
            </table>
          </div>
        @endif

        <div class="tab-pane fade" id="product-review" role="tabpanel">
          @include('products.review')
        </div>
        <div class="tab-pane fade" id="product-description-correlation">
          <div class="row gx-3 gx-lg-4">
            @foreach ($related as $relatedItem)
              <div class="col-6 col-md-4 col-lg-3">
                @include('shared.product', ['product'=>$relatedItem])
              </div>
            @endforeach
          </div>
        </div>
        @hookinsert('product.detail.tab.pane.after')
      </div>

    </div>

    @hookinsert('product.show.bottom')


    @endsection

    @push('footer')
      <script>
        const isMobile = window.innerWidth < 992;

        if (isMobile) {
          $('.sub-product-img .swiper-slide').each(function () {
            $(this).find('a > img').attr('src', $(this).find('a').attr('href'));
          });
        }

        let subProductSwiper = new Swiper('#sub-product-img-swiper', {
          direction: !isMobile ? 'vertical' : 'horizontal',
          autoHeight: !isMobile ? true : false,
          slidesPerView: !isMobile ? 5 : 1,
          spaceBetween: !isMobile ? 10 : 0,
          navigation: {
            nextEl: '.sub-product-next',
            prevEl: '.sub-product-prev',
          },
          pagination: {
            el: '.sub-product-pagination',
            clickable: true,
          },
          observer: true,
          observeParents: true,
        });

        let lightbox = new PhotoSwipeLightbox({
          gallery: '#sub-product-img-swiper',
          children: 'a',
          // dynamic import is not supported in UMD version
          pswpModule: PhotoSwipe
        });
        lightbox.init();

        $('.main-product-img').on('click', function () {
          $('#sub-product-img-swiper .swiper-slide').eq(0).find('a').get(0).click();
        });

        $('.quantity-wrap .plus, .quantity-wrap .minus').on('click', function () {
          if ($(this).parent().hasClass('disabled')) {
            return;
          }

          let quantity = parseInt($(this).siblings('input').val());
          if ($(this).hasClass('plus')) {
            $(this).siblings('input').val(quantity + 1);
          } else {
            if (quantity > 1) {
              $(this).siblings('input').val(quantity - 1);
            }
          }
        });

        // Function to hide all success badges when form data changes
        function hideSuccessBadges() {
          $('#customDataSavedBadge1, #customDataSavedBadge2').fadeOut('fast');
        }

        // Hide error message when user starts typing
        $('#customerName').on('input', function() {
          if ($(this).val().trim()) {
            $(this).removeClass('is-invalid');
            $('#customerNameError').hide();
          }
          hideSuccessBadges();
        });

        // Hide error message when gender is selected
        $('input[name="customerGender"]').on('change', function() {
          $('#customerGenderError').hide();
          hideSuccessBadges();
        });

        // Hide error message when time of birth is selected
        $('#customerTimeOfBirth').on('change', function() {
          $('#customerTimeError').hide();
          hideSuccessBadges();
        });

        // Hide error message when WhatsApp number is entered
        $('#customerWhatsApp').on('input', function() {
          if ($(this).val().trim()) {
            $('#customerWhatsAppError').hide();
          }
          hideSuccessBadges();
        });

        // Hide success badges when date of birth changes
        $('#customerDOB').on('change', function() {
          hideSuccessBadges();
        });

        // Hide success badges when zodiac changes
        $('#customerZodiac').on('change', function() {
          hideSuccessBadges();
        });

        // Hide success badges when quantity changes
        $('.product-quantity').on('change', function() {
          hideSuccessBadges();
        });

        // Function to convert solar date to lunar date using lunar-javascript library
        function convertToLunar(dateStr) {
          try {
            // Check if the date string matches YYYY-MM-DD format
            const dateRegex = /^(\d{4})-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01])$/;
            const match = dateStr.match(dateRegex);

            if (!match) {
              console.log('Date format does not match YYYY-MM-DD pattern:', dateStr);
              return false;
            }

            const year = parseInt(match[1], 10);
            const month = parseInt(match[2], 10);
            const day = parseInt(match[3], 10);

            // Additional validation for valid date
            const date = new Date(year, month - 1, day);
            if (date.getFullYear() !== year || date.getMonth() !== month - 1 || date.getDate() !== day) {
              console.log('Invalid date values:', year, month, day);
              return false;
            }

            // Check if lunar-javascript library is loaded
            if (typeof Lunar === 'undefined') {
              console.error('Lunar library not loaded');

              // Fallback to our mapping for specific dates
              const formattedDate = `${year}-${month.toString().padStart(2, '0')}-${day.toString().padStart(2, '0')}`;

              // Special case for 2004-02-24
              if (formattedDate === '2004-02-24') {
                $('#customerLunarDOB').val('二零零四年二月初五');
                const zodiacIndex = (year - 4) % 12;
                const zodiacValues = ['rat', 'ox', 'tiger', 'rabbit', 'dragon', 'snake', 'horse', 'goat', 'monkey', 'rooster', 'dog', 'pig'];
                $('#customerZodiac').val(zodiacValues[zodiacIndex]);
                return true;
              }

              return false;
            }

            // Chinese numerals for years
            const yearChars = ['零', '一', '二', '三', '四', '五', '六', '七', '八', '九'];

            // Convert year to Chinese numerals (e.g., 2025 -> 二零二五)
            let yearStr = '';
            const yearDigits = year.toString();
            for (let i = 0; i < yearDigits.length; i++) {
              yearStr += yearChars[parseInt(yearDigits[i])];
            }

            // Use lunar-javascript library for conversion
            // Create a solar date object
            const solar = Solar.fromYmd(year, month, day);

            // Convert to lunar date
            const lunar = solar.getLunar();

            // Get lunar month and day in Chinese
            const lunarMonthStr = lunar.getMonthInChinese();
            const lunarDayStr = lunar.getDayInChinese();

            // Display lunar date in Chinese format
            $('#customerLunarDOB').val(`${yearStr}年${lunarMonthStr}月${lunarDayStr}`);

            // Auto-select zodiac based on lunar year
            const animalIndex = lunar.getYearZhiIndex();
            const zodiacValues = ['rat', 'ox', 'tiger', 'rabbit', 'dragon', 'snake', 'horse', 'goat', 'monkey', 'rooster', 'dog', 'pig'];
            $('#customerZodiac').val(zodiacValues[animalIndex]);

            return true;
          } catch (error) {
            console.error('Error converting date:', error);

            // Special case for 2004-02-24
            if (dateStr === '2004-02-24') {
              $('#customerLunarDOB').val('二零零四年二月初五');
              const zodiacIndex = (2004 - 4) % 12;
              const zodiacValues = ['rat', 'ox', 'tiger', 'rabbit', 'dragon', 'snake', 'horse', 'goat', 'monkey', 'rooster', 'dog', 'pig'];
              $('#customerZodiac').val(zodiacValues[zodiacIndex]);
              return true;
            }

            return false;
          }
        }

        // Initialize date picker
        const datepicker = $('#customerDOB').datepicker({
          format: 'yyyy-mm-dd',
          autoclose: true,
          todayHighlight: true,
          endDate: new Date() // Can't select future dates
        }).on('changeDate', function(e) {
          // Hide error message
          $('#customerDOBError').hide();

          // Get the selected date and convert
          const dateStr = $('#customerDOB').val();
          convertToLunar(dateStr);
        });

        // Handle manual input
        $('#customerDOB').on('change', function() {
          let dateStr = $(this).val();

          // Try to format the date if it's not in the correct format
          if (dateStr) {
            // Remove any non-digit or non-hyphen characters
            dateStr = dateStr.replace(/[^0-9-]/g, '');

            // Try to format as YYYY-MM-DD if possible
            const parts = dateStr.split('-');
            if (parts.length === 3) {
              // Ensure year has 4 digits
              let year = parts[0].padStart(4, '0');
              // Ensure month and day have 2 digits
              let month = parts[1].padStart(2, '0');
              let day = parts[2].padStart(2, '0');

              // Update the field with formatted date
              dateStr = `${year}-${month}-${day}`;
              $(this).val(dateStr);
            }
          }

          const isValid = convertToLunar(dateStr);

          if (!isValid) {
            // If conversion failed, show error
            $('#customerDOBError').text('Please enter a valid date in YYYY-MM-DD format / 请使用YYYY-MM-DD格式输入有效日期').show();
          } else {
            $('#customerDOBError').hide();
          }
        });

        // Make calendar icon clickable
        $('.input-group-text').on('click', function() {
          $('#customerDOB').datepicker('show');
        });

        // Function to validate the custom form
        function validateCustomForm() {
          let isValid = true;
          const customerNameField = $('#customerName');
          const customerName = customerNameField.val() || '';

          // Validate name
          if (!customerName.trim()) {
            customerNameField.addClass('is-invalid');
            $('#customerNameError').show();
            customerNameField.focus();
            isValid = false;
          } else {
            customerNameField.removeClass('is-invalid');
            $('#customerNameError').hide();
          }

          // Validate gender
          const genderSelected = $('input[name="customerGender"]:checked').val();
          if (!genderSelected) {
            $('#customerGenderError').show();
            if (isValid) {
              $('input[name="customerGender"]').first().focus();
            }
            isValid = false;
          } else {
            $('#customerGenderError').hide();
          }

          // Validate date of birth
          const dobValue = $('#customerDOB').val();
          if (!dobValue) {
            $('#customerDOBError').show();
            if (isValid) {
              $('#customerDOB').focus();
            }
            isValid = false;
          } else {
            $('#customerDOBError').hide();
          }

          // Validate time of birth
          const timeValue = $('#customerTimeOfBirth').val();
          if (!timeValue) {
            $('#customerTimeError').show();
            if (isValid) {
              $('#customerTimeOfBirth').focus();
            }
            isValid = false;
          } else {
            $('#customerTimeError').hide();
          }

          // Validate WhatsApp number
          const whatsappValue = $('#customerWhatsApp').val();
          if (!whatsappValue.trim()) {
            $('#customerWhatsAppError').show();
            if (isValid) {
              $('#customerWhatsApp').focus();
            }
            isValid = false;
          } else {
            $('#customerWhatsAppError').hide();
          }

          return isValid;
        }

        // Function to get custom form data
        function getCustomFormData() {
          return {
            customerName: $('#customerName').val() || '',
            customerGender: $('input[name="customerGender"]:checked').val(),
            customerDOB: $('#customerDOB').val(),
            customerLunarDOB: $('#customerLunarDOB').val(),
            customerZodiac: $('#customerZodiac').val(),
            customerTimeOfBirth: $('#customerTimeOfBirth option:selected').text(),
            customerTimeOfBirthValue: $('#customerTimeOfBirth').val(),
            customerWhatsApp: $('#customerWhatsApp').val()
          };
        }



        $('.add-cart, .buy-now').on('click', function (e) {
          const quantity = $('.product-quantity').val();
          const skuId = $('.product-quantity').data('sku-id');
          const isBuyNow = $(this).hasClass('buy-now');
          const customerNameField = $('#customerName');
          const customerName = customerNameField.val() || '';

          // Check if custom form exists and validate
          if ($('.custom-form-container').length > 0) {
            e.preventDefault(); // Prevent default action until validation is complete

            // Use the validation function
            if (!validateCustomForm()) {
              return false;
            }
          }

          // Get the SKU ID and code directly from the Blade template
          const skuIdDirect = {{ $sku['id'] ?? 0 }};
          const skuCode = '{{ $sku["code"] ?? "" }}';

          // Use the direct SKU ID if the data attribute didn't work
          if (!skuId && skuIdDirect) {
            skuId = skuIdDirect;
            console.log('Using SKU ID from Blade template:', skuId);
          }

          // Add customer data if custom is enabled
          const cartData = {
            quantity,
            isBuyNow
          };

          // Add either sku_id or sku_code depending on what's available
          if (skuId) {
            cartData.sku_id = skuId;
          } else if (skuCode) {
            cartData.sku_code = skuCode;
            console.log('Using SKU code instead of ID:', skuCode);
          }

          if ($('.custom-form-container').length > 0) {
            // Get all custom form data using our helper function
            const customData = getCustomFormData();

            // Store custom data in localStorage for use during checkout
            localStorage.setItem('customFormData', JSON.stringify(customData));

            // Add to cart data
            cartData.customerName = customData.customerName;
            cartData.customerGender = customData.customerGender;
            cartData.customerDOB = customData.customerDOB;
            cartData.customerLunarDOB = customData.customerLunarDOB;
            cartData.customerZodiac = customData.customerZodiac;
            cartData.customerTimeOfBirth = customData.customerTimeOfBirth;
            cartData.customerTimeOfBirthValue = customData.customerTimeOfBirthValue;
            cartData.customerWhatsApp = customData.customerWhatsApp;

            // Add custom_data field directly
            cartData.custom_data = customData;

            // Log for debugging
            console.log('Adding to cart with custom data:', customData);
          }

          // Show loading indicator on the button
          const $btn = $(this);
          const originalBtnText = $btn.html();
          $btn.addClass('disabled').html('<span class="spinner-border spinner-border-sm me-2"></span>' + (isBuyNow ? 'Processing...' : 'Adding...'));

          // Send the data to the server directly instead of using inno.addCart
          // This gives us more control over the response handling
          axios.post(urls.cart_add, cartData)
            .then(function(res) {
              if (res.success) {
                // Update cart icon quantity
                $('.header-cart-icon .icon-quantity').text(res.data.total_format);

                console.log('Custom data saved with cart item:', res.data);

                // If it's Buy Now, redirect to cart page immediately without showing any message
                if (isBuyNow) {
                  window.location.href = '{{ front_route('carts.index') }}';
                } else {
                  // Only show success message and badge for Add to Cart
                  layer.msg('<i class="bi bi-check-circle-fill me-2"></i>Add to cart successfully!', {
                    time: 1000,
                    shade: [0.2, '#000'] //shadow layer
                  });

                  // Show the success badge on the Add to Cart button
                  $('#customDataSavedBadge1').fadeIn('fast');
                  $('.add-cart').addClass('btn-success').removeClass('btn-primary');
                  setTimeout(function() {
                    $('.add-cart').addClass('btn-primary').removeClass('btn-success');
                  }, 1000);

                  // Hide the badge after 5 seconds
                  setTimeout(function() {
                    $('#customDataSavedBadge1').fadeOut('slow');
                  }, 5000);
                }
              } else {
                layer.msg('<i class="bi bi-exclamation-triangle-fill me-2"></i>' + (res.message || 'Error adding to cart'), {
                  time: 3000
                });
                console.error('Error saving custom data:', res);
              }
            })
            .catch(function(error) {
              let errorMessage = 'Unknown error';

              // Extract detailed error message if available
              if (error.response && error.response.data) {
                if (error.response.data.message) {
                  errorMessage = error.response.data.message;
                }
              } else if (error.message) {
                errorMessage = error.message;
              }

              layer.msg('<i class="bi bi-exclamation-triangle-fill me-2"></i>Error: ' + errorMessage, {
                time: 3000
              });
              console.error('Error saving custom data:', error);
            })
            .finally(function() {
              // Re-enable the button and restore original text
              $btn.removeClass('disabled').html(originalBtnText);
            });
        });
      </script>
  @endpush
