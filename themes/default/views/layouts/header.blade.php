@hookinsert('layout.header.top')

<header id="appHeader">
  <div class="header-top">
    <div class="container d-flex justify-content-between align-items-center">
      <div class="language-switch d-flex align-items-center">
        <div class="dropdown">
          <a class="btn dropdown-toggle" href="javascript:void(0)">
            <img src="{{ asset($current_locale->image) }}" class="img-fluid"> {{ $current_locale->name }}
          </a>
          <div class="dropdown-menu">
            @foreach (locales() as $locale)
              <a class="dropdown-item d-flex" href="{{ front_route('locales.switch', ['code' => $locale->code]) }}">
                <div class="wh-20 me-2"><img src="{{ image_origin($locale['image']) }}" class="img-fluid border">
                </div>
                {{ $locale->name }}
              </a>
            @endforeach
          </div>
        </div>
        <div class="dropdown ms-4">
          <a class="btn dropdown-toggle" href="javascript:void(0)">
            {{ current_currency()->name }}
          </a>
          <div class="dropdown-menu">
            @foreach (currencies() as $currency)
              <a class="dropdown-item" href="{{ front_route('currencies.switch', ['code' => $currency->code]) }}">
                {{ $currency->name }} ({{ $currency->symbol_left }})
              </a>
            @endforeach
          </div>
        </div>
        @hookinsert('layouts.header.currency.after')
      </div>

      <div class="top-info">
        @hookinsert('layouts.header.news.before')
        <a href="{{ front_route('articles.index') }}">News</a>
        @hookupdate('layouts.header.telephone')
        <span><i class="bi bi-telephone-outbound"></i> {{ system_setting('telephone') }}</span>
        @endhookupdate
      </div>
    </div>
  </div>
  <div class="header-desktop">
    <div class="container d-flex justify-content-between align-items-center">
      <div class="left">
        <h1 class="logo">
          <a href="{{ front_route('home.index') }}">
            <img src="{{ image_origin(system_setting('front_logo', 'images/logo.svg')) }}" class="img-fluid">
          </a>
        </h1>
        <div class="menu">
          <nav class="navbar navbar-expand-md navbar-light">
            <ul class="navbar-nav">
              <!--<li class="nav-item">
                <a class="nav-link" aria-current="page"
                   href="{{ front_route('home.index') }}">{{ __('front/common.home') }}</a>
              </li>-->

              @hookupdate('layouts.header.menu.pc')
              @foreach ($header_menus as $menu)
                @if ($menu['children'] ?? [])
                  <li class="nav-item">
                    <div class="dropdown">
                      @if ($menu['name'])
                        <a class="nav-link {{ equal_url($menu['url']) ? 'active' : '' }}"
                           href="{{ $menu['url'] }}">{{ $menu['name'] }}</a>
                        <a href="{{ $menu['url'] }}" class="menu-subtitle-link">
                        @if ($menu['name'] === '开运饰品')
                          Fortune<br>Accessories
                        @elseif ($menu['name'] === '代烧')
                          Burn On<br>Behalf
                        @elseif ($menu['name'] === '风水产品')
                          Feng Shui<br>Products
                        @elseif ($menu['name'] === '法会')
                          Praying<br>Ceremony
                        @elseif ($menu['name'] === '神料')
                          Praying<br>Supplies
                        @elseif ($menu['name'] === '风水服务')
                          Feng Shui<br>Services
                        @elseif ($menu['name'] === '风水资讯')
                          Feng Shui<br>Info
                        @elseif ($menu['name'] === '关于我们')
                          About<br>Us
                        @else
                          Default<br>Subtitle
                        @endif
                        </a>
                      @endif
                      <ul class="dropdown-menu">
                        @foreach ($menu['children'] as $child)
                          @if ($child['name'])
                            <li><a class="dropdown-item" href="{{ $child['url'] }}">{{ $child['name'] }}</a></li>
                          @endif
                        @endforeach
                      </ul>
                    </div>
                  </li>
                @else
                  @if ($menu['name'])
                    <li class="nav-item">
                      <a class="nav-link {{ equal_url($menu['url']) ? 'active' : '' }}"
                         href="{{ $menu['url'] }}">{{ $menu['name'] }}</a>
                      <a href="{{ $menu['url'] }}" class="menu-subtitle-link">
                        @if ($menu['name'] === '开运饰品')
                          Fortune<br>Accessories
                        @elseif ($menu['name'] === '代烧')
                          Burn On<br>Behalf
                        @elseif ($menu['name'] === '风水产品')
                          Feng Shui<br>Products
                        @elseif ($menu['name'] === '法会')
                          Praying<br>Ceremony
                        @elseif ($menu['name'] === '神料')
                          Praying<br>Supplies
                        @elseif ($menu['name'] === '风水服务')
                          Feng Shui<br>Services
                        @elseif ($menu['name'] === '风水资讯')
                          Feng Shui<br>Info
                        @elseif ($menu['name'] === '关于我们')
                          About<br>Us
                        @else
                          Default<br>Subtitle
                        @endif
                      </a>
                    </li>
                  @endif
                @endif
              @endforeach
              @endhookupdate
            </ul>
          </nav>
        </div>
      </div>
      <div class="right">
        <div class="search-icon-container">
          <a href="javascript:void(0)" class="search-icon"><i class="bi bi-search"></i></a>
          <div class="popup-search-box">
            <form action="{{ front_route('products.index') }}" method="get" class="search-group">
              <input type="text" class="form-control" name="keyword" placeholder="{{ __('front/common.search') }}"
                     value="{{ request('keyword') }}">
              <button type="submit" class="btn"><i class="bi bi-search"></i></button>
              <button type="button" class="btn-close-search"><i class="bi bi-x"></i></button>
            </form>
          </div>
        </div>
        <div class="icons">
          <div class="item">
            <div class="dropdown account-icon">
              <a class="btn dropdown-toggle px-0" href="{{ front_route('account.index') }}">
                <img src="{{ asset('images/icons/account.svg') }}" class="img-fluid">
              </a>

              <div class="dropdown-menu dropdown-menu-end">
                @if (current_customer())
                  <a href="{{ front_route('account.index') }}"
                     class="dropdown-item">{{ __('front/account.account') }}</a>
                  <a href="{{ front_route('account.orders.index') }}"
                     class="dropdown-item">{{ __('front/account.orders') }}</a>
                  <a href="{{ front_route('account.favorites.index') }}"
                     class="dropdown-item">{{ __('front/account.favorites') }}</a>
                  <a href="{{ front_route('account.logout') }}"
                     class="dropdown-item">{{ __('front/account.logout') }}</a>
                @else
                  <a href="{{ front_route('login.index') }}" class="dropdown-item">{{ __('front/common.login') }}</a>
                  <a href="{{ front_route('register.index') }}"
                     class="dropdown-item">{{ __('front/common.register') }}</a>
                @endif
              </div>
            </div>
          </div>
          <div class="item">
            <a href="{{ account_route('favorites.index') }}"><img src="{{ asset('images/icons/love.svg') }}"
                                                                  class="img-fluid"><span
                class="icon-quantity">{{ $fav_total }}</span></a>
          </div>
          <div class="item">
            <a href="javascript:void(0)" class="header-cart-icon" data-bs-toggle="offcanvas"
               data-bs-target="#cartOffcanvas" aria-controls="cartOffcanvas"><img src="{{ asset('images/icons/cart.svg') }}"
                                                                                  class="img-fluid"><span
                class="icon-quantity">0</span></a>
          </div>
          @hookinsert('layouts.header.cart.after')
        </div>
      </div>
    </div>
  </div>
  <div class="header-mobile">
    <div class="mb-icon" data-bs-toggle="offcanvas" data-bs-target="#mobile-menu-offcanvas"
         aria-controls="offcanvasExample">
      <i class="bi bi-list"></i>
    </div>

    <div class="logo">
      <a href="{{ front_route('home.index') }}">
        <img src="{{ image_origin(system_setting('front_logo', 'images/logo.svg')) }}" class="img-fluid">
      </a>
    </div>

    <a href="{{ front_route('carts.index') }}" class="header-cart-icon"><img src="{{ asset('images/icons/cart.svg') }}"
                                                                             class="img-fluid"><span
        class="icon-quantity">0</span></a>

    <div class="offcanvas offcanvas-start" tabindex="-1" id="mobile-menu-offcanvas">
      <div class="offcanvas-header">
        <div class="search-icon-container mobile">
          <a href="javascript:void(0)" class="search-icon"><i class="bi bi-search"></i></a>
          <div class="popup-search-box">
            <form action="{{ front_route('products.index') }}" method="get" class="search-group">
              <input type="text" class="form-control" name="keyword" placeholder="{{ __('front/common.search') }}"
                     value="{{ request('keyword') }}">
              <button type="submit" class="btn"><i class="bi bi-search"></i></button>
              <button type="button" class="btn-close-search"><i class="bi bi-x"></i></button>
            </form>
          </div>
        </div>
        <a class="account-icon" href="{{ front_route('account.index') }}">
          <img src="{{ asset('images/icons/account.svg') }}" class="img-fluid">
        </a>
      </div>
      <div class="close-offcanvas" data-bs-dismiss="offcanvas"><i class="bi bi-chevron-compact-left"></i></div>
      <div class="offcanvas-body mobile-menu-wrap">
        <div class="accordion accordion-flush" id="menu-accordion">
          <div class="accordion-item">
            <div class="nav-item-text">
              <a class="nav-link {{ equal_route_name('home.index') ? 'active' : '' }}" aria-current="page"
                 href="{{ front_route('home.index') }}">{{ __('front/common.home') }}</a>
            </div>
          </div>

          @hookupdate('layouts.header.menu.mobile')
          @foreach ($header_menus as $key => $menu)
            @if ($menu['name'])
              <div class="accordion-item">
                <div class="nav-item-text">
                  <a class="nav-link" href="{{ $menu['url'] }}"
                     data-bs-toggle="{{ !$menu['url'] ? 'collapse' : '' }}">
                    {{ $menu['name'] }}
                  </a>
                  @if (isset($menu['children']) && $menu['children'])
                    <span class="collapsed" data-bs-toggle="collapse"
                          data-bs-target="#flush-menu-{{ $key }}"><i class="bi bi-chevron-down"></i></span>
                  @endif
                </div>

                @if (isset($menu['children']) && $menu['children'])
                  <div class="accordion-collapse collapse" id="flush-menu-{{ $key }}"
                       data-bs-parent="#menu-accordion">
                    <div class="children-group">
                      <ul class="nav flex-column ul-children">
                        @foreach ($menu['children'] as $c_key => $child)
                          @if ($child['name'])
                            <li class="nav-item">
                              <a class="nav-link" href="{{ $child['url'] }}">{{ $child['name'] }}</a>
                            </li>
                          @endif
                        @endforeach
                      </ul>
                    </div>
                  </div>
                @endif
              </div>
            @endif
          @endforeach
          @endhookupdate

        </div>
      </div>
    </div>
  </div>
</header>

@hookinsert('layout.header.bottom')

<style>
  /* 移除主标题下划线动画 */
  header .header-desktop .left .menu .navbar .nav-item>.nav-link:after,
  header .header-desktop .left .menu .navbar .nav-item>.dropdown>.nav-link:after {
    border-bottom: none !important;
    content: none !important;
  }
  .nav-item {
    text-align: center;
    padding-bottom: 0;
  }
  .nav-item .nav-link {
    font-weight: bold;
    text-decoration: none !important;
    padding-bottom: 2px;
    position: relative;
  }
  .nav-item .menu-subtitle-link {
    display: inline-block;
    font-size: 13px;
    color: #888;
    margin-top: 0;
    padding: 0;
    text-decoration: none;
    transition: color .3s;
    position: relative;
    line-height: 1.1;
  }
  .menu-subtitle-link:after {
    content: "";
    position: absolute;
    left: 0;
    right: 0;
    bottom: -2px;
    border-bottom: 2px solid #888;
    width: 0;
    transition: width .3s;
  }
  .menu-subtitle-link:hover,
  .nav-item:hover .menu-subtitle-link {
    color: #333;
  }
  .menu-subtitle-link:hover:after,
  .nav-item:hover .menu-subtitle-link:after {
    width: 100%;
    left: 0;
    right: auto;
  }
  .navbar-nav .nav-item {
    margin: 0 7px;
  }

  /* Search icon and popup styles */
  .search-icon-container {
    position: relative;
    display: inline-block;
  }

  .search-icon {
    font-size: 22px;
    color: #333;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background-color: #F1F3F5;
    transition: all 0.3s ease;
  }

  .search-icon:hover {
    background-color: #e9ecef;
  }

  .popup-search-box {
    position: absolute;
    top: 100%;
    right: 0;
    width: 300px;
    background-color: #fff;
    border-radius: 4px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    padding: 10px;
    z-index: 1000;
    display: none;
    margin-top: 10px;
  }

  .popup-search-box:before {
    content: '';
    position: absolute;
    top: -8px;
    right: 15px;
    width: 0;
    height: 0;
    border-left: 8px solid transparent;
    border-right: 8px solid transparent;
    border-bottom: 8px solid #fff;
  }

  .popup-search-box .search-group {
    width: 100%;
    background-color: #F1F3F5;
    border-radius: 4px;
    position: relative;
  }

  .popup-search-box .search-group input {
    width: 100%;
    padding-right: 70px;
  }

  .btn-close-search {
    position: absolute;
    right: 40px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    font-size: 18px;
    color: #999;
    cursor: pointer;
    padding: 0;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .btn-close-search:hover {
    color: #333;
  }

  /* Mobile search styles */
  .search-icon-container.mobile .popup-search-box {
    position: fixed;
    top: 60px;
    left: 0;
    right: 0;
    width: 100%;
    margin-top: 0;
    border-radius: 0;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
  }

  .search-icon-container.mobile .popup-search-box:before {
    display: none;
  }
</style>

@push('footer')
<script>
  $(function() {
    // Toggle search popup when search icon is clicked
    $('.search-icon').on('click', function(e) {
      e.preventDefault();
      $(this).siblings('.popup-search-box').fadeToggle(200);
      $(this).siblings('.popup-search-box').find('input').focus();
    });

    // Close search popup when close button is clicked
    $('.btn-close-search').on('click', function() {
      $(this).closest('.popup-search-box').fadeOut(200);
    });

    // Close search popup when clicking outside
    $(document).on('click', function(e) {
      if (!$(e.target).closest('.search-icon-container').length) {
        $('.popup-search-box').fadeOut(200);
      }
    });

    // Handle Enter key press in search input
    $('.popup-search-box input').on('keydown', function(e) {
      if (e.keyCode === 13) {
        $(this).closest('form').submit();
      }
    });
  });
</script>
@endpush
