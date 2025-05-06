<div class="filter-sidebar">
  <div class="filter-sidebar-item">
    <div class="title">{{ __('front/page.pages') }} <span class="badge rounded-pill ms-auto">{{ count($all_pages) }}</span></div>
    <div class="content">
      <div class="accordion" id="filter-pages">
        @foreach ($all_pages as $item)
        <div class="accordion-item">
          <div class="accordion-title">
            <div class="category-title-wrapper">
              <a href="{{ $item->url }}" class="category-title {{ $page->id == $item->id ? 'active' : '' }}">{{ $item->translation->title }}</a>
              @if($item->translation->sub_title)
              <a href="{{ $item->url }}" class="category-subtitle-link {{ $page->id == $item->id ? 'active' : '' }}">
                {!! nl2br(e($item->translation->sub_title)) !!}
              </a>
              @endif
            </div>
          </div>
        </div>
        @endforeach
      </div>
    </div>
  </div>
</div>
