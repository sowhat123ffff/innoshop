@extends('layouts.app')
@section('body-class', 'page-home')

@push('header')
  <script src="{{ asset('vendor/swiper/swiper-bundle.min.js') }}"></script>
  <link rel="stylesheet" href="{{ asset('vendor/swiper/swiper-bundle.min.css') }}">
@endpush

@section('content')

  @hookinsert('home.content.top')

  <section class="module-content">
    @if ($slideshow)
      <section class="module-line">
        <div class="swiper" id="module-swiper-1">
          <div class="module-swiper swiper-wrapper">
            @foreach ($slideshow as $slide)
              @if ($slide['image'][front_locale_code()] ?? false)
                <div class="swiper-slide">
                  <a href="{{ $slide['link'] ?: 'javascript:void(0)' }}"><img
                      src="{{ image_origin($slide['image'][front_locale_code()]) }}" class="img-fluid"></a>
                </div>
              @endif
            @endforeach
          </div>
          <div class="swiper-pagination"></div>
        </div>
      </section>


      <script>
        var swiper = new Swiper('#module-swiper-1', {
          loop: true,
          pagination: {
            el: '.swiper-pagination',
            clickable: true,
          },
          autoplay: {
            delay: 2500,
            disableOnInteraction: true,
          },
        });
      </script>
    @endif


    @hookinsert('home.content.bottom')

  @hookinsert('home.content.bottom2')

  @hookinsert('home.content.bottom3')

  @hookinsert('home.content.bottom4')

  @hookinsert('home.content.bottom5')

    @hookinsert('home.swiper.after')

    @if (0)
      <section class="module-line">
        <div class="module-banner-2">
          <div class="container">
            <div class="row">
              <div class="col-12 col-md-4 mb-2 mb-lg-0">
                <a href=""><img src="{{ asset('images/demo/banner/banner-3.jpg') }}" class="img-fluid"></a>
              </div>
              <div class="col-12 col-md-8">
                <a href=""><img src="{{ asset('images/demo/banner/banner-4.jpg') }}" class="img-fluid"></a>
              </div>
            </div>
          </div>
        </div>
      </section>
    @endif

    <section class="module-line">
      <div class="module-product-tab">
        <div class="container">
          <div class="module-title-wrap">
            <div class="module-title">服务<!--{{ __('front/home.feature_product') }} --></div>
            <div class="module-sub-title">Service <!--{{ __('front/home.feature_product_text') }}--></div>

          </div>

          <ul class="nav nav-tabs">
            @foreach ($tab_products as $item)
              <li class="nav-item" role="presentation">
                <button class="nav-link {{ $loop->first ? 'active' : '' }}" data-bs-toggle="tab"
                  data-bs-target="#module-product-tab-x-{{ $loop->iteration }}"
                  type="button">{{ $item['tab_title'] }}</button>
              </li>
            @endforeach
          </ul>

          <div class="tab-content">
            @foreach ($tab_products as $item)
              <div class="tab-pane fade show {{ $loop->first ? 'active' : '' }}"
                id="module-product-tab-x-{{ $loop->iteration }}">
                <div class="row gx-3 gx-lg-4">
                  @foreach ($item['products'] as $product)
                    <div class="col-6 col-md-4 col-lg-3">
                      <div class="product-grid-item product-featured-hover">
                        <a href="{{ $product->url }}" style="text-decoration:none;display:block;width:100%;height:100%">
                          <div class="image">
                            <img src="{{ $product->image_url }}" class="img-fluid" width="300" height="170" style="width:300px;height:170px;object-fit:cover;">
                          </div>
                          <div class="product-item-info product-hover-title">
                            <div class="product-name">
                              <span>
                                {!! str_replace(['&lt;br&gt;', '<br>'], '<br>', e($product->fallbackName())) !!}
                              </span>
                            </div>
                          </div>
                        </a>
                      </div>
                    </div>
                  @endforeach
                </div>
              </div>
            @endforeach
          </div>
        </div>
      </div>
      <style>
        .product-featured-hover .product-item-info {
          opacity: 0;
          pointer-events: none;
          transition: opacity 0.3s;
          position: absolute;
          left: 0; right: 0; bottom: 0; top: 0;
          background: rgba(255,255,255,0.7);
          color: #111;
          display: flex;
          align-items: center;
          justify-content: center;
          font-size: 1.1rem;
          font-weight: bold;
          text-align: center;
          z-index: 2;
        }
        .product-featured-hover { position: relative; overflow: hidden; }
        .product-featured-hover:hover .product-item-info {
          opacity: 1;
          pointer-events: auto;
        }
        .product-featured-hover .product-item-info .product-name a {
          color: #111;
          text-decoration: none;
          display: inline-block;
          width: 100%;
          padding: 0.5em 1em;
          pointer-events: auto;
          z-index: 3;
          position: relative;
          white-space: normal;
          word-break: break-word;
          line-height: 1.5;
        }
        .product-featured-hover .image { z-index: 1; }
        .product-name span {
          word-break: break-all;
          white-space: normal;
          display: inline-block;
          width: 100%;
        }
      </style>
    </section>




    @if (0)
      <section class="module-line">
        <div class="module-banner-1">
          <div class="container">
            <a href=""><img src="{{ asset('images/demo/banner/banner-5.jpg') }}" class="img-fluid"></a>
          </div>
        </div>
      </section>
    @endif

    <section class="module-line">
      <div class="module-product-tab">
        <div class="container">
          <div class="module-title-wrap">
            <div class="module-title">相关话题<!--{{ __('front/home.news_blog') }}--></div>
            <div class="module-sub-title">Related Posts<!--{{ __('front/home.news_blog_text') }}--></div>
          </div>

          <div class="row gx-3 gx-lg-4">
            @foreach ($news as $new)
              <div class="col-6 col-md-4 col-lg-3">
                @include('shared.blog', ['item' => $new])
              </div>
            @endforeach
          </div>
        </div>
      </div>
    </section>
  </section>


@endsection
