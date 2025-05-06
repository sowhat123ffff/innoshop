<div class="mobile-page-nav d-md-none">
  <div class="container">
    <div class="mobile-page-nav-inner">
      <div class="mobile-page-nav-title">{{ __('front/page.pages') }}</div>
      <div class="mobile-page-nav-items">
        @foreach ($all_pages as $item)
          <a href="{{ $item->url }}" class="mobile-page-nav-item {{ $page->id == $item->id ? 'active' : '' }}">
            <div class="mobile-page-title">{{ $item->translation->title }}</div>
            @if($item->translation->sub_title)
              <div class="mobile-page-subtitle">{{ $item->translation->sub_title }}</div>
            @else
              <div class="mobile-page-subtitle">&nbsp;</div>
            @endif
          </a>
        @endforeach

        <!-- Add empty items to ensure proper grid alignment -->
        @php
          $remainder = count($all_pages) % 4;
          if ($remainder > 0 && $remainder < 4) {
            $emptyItems = 4 - $remainder;
            for ($i = 0; $i < $emptyItems; $i++) {
              echo '<div class="mobile-page-nav-item empty"></div>';
            }
          }
        @endphp
      </div>
    </div>
  </div>
</div>
