<div class="page-title-container">
  <div class="container">
    <div class="title-box">
      <h1 class="title">{{ $page->translation->title }}</h1>
      @if($page->translation->sub_title)
        <div class="sub-title">{{ $page->translation->sub_title }}</div>
      @endif
    </div>
  </div>
</div>
