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
              <li class="nav-item">
                <a class="nav-link" aria-current="page"
                   href="{{ front_route('home.index') }}">{{ __('front/common.home') }}</a>
              </li>

              @hookupdate('layouts.header.menu.pc')
              @foreach ($header_menus as $menu)
                @if ($menu['children'] ?? [])
                  <li class="nav-item">
                    <div class="dropdown">
                      @if ($menu['name'])
                        <a class="nav-link {{ equal_url($menu['url']) ? 'active' : '' }}"
                           href="{{ $menu['url'] }}">{{ $menu['name'] }}</a>
                        <a href="{{ $menu['url'] }}" class="menu-subtitle-link">
                          @if ($menu['name'] === 'Products')
                            产品副标题
                          @elseif ($menu['name'] === 'Home')
                            首页副标题
                          @elseif ($menu['name'] === 'About')
                            关于副标题
                            @elseif ($menu['name'] === '法会')
                            Praying
                          @else
                            默认副标题
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
                        @if ($menu['name'] === 'Products')
                          产品副标题
                        @elseif ($menu['name'] === 'Home')
                          首页副标题
                        @elseif ($menu['name'] === 'About')
                          关于副标题
                          @elseif ($menu['name'] === '法会')
                          Praying<br>CEREMONY
                        @else
                          默认副标题
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
        <form action="{{ front_route('products.index') }}" method="get" class="search-group">
          <input type="text" class="form-control" name="keyword" placeholder="{{ __('front/common.search') }}"
                 value="{{ request('keyword') }}">
          <button type="submit" class="btn"><i class="bi bi-search"></i></button>
        </form>
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
        <form action="" method="get" class="search-group">
          <input type="text" class="form-control" placeholder="Search">
          <button type="submit" class="btn"><i class="bi bi-search"></i></button>
        </form>
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
    margin: 0 14px;
  }
</style>
