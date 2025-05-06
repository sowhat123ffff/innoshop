@extends('layouts.app')

@section('title', \InnoShop\Common\Libraries\MetaInfo::getInstance($page)->getTitle())
@section('description', \InnoShop\Common\Libraries\MetaInfo::getInstance($page)->getDescription())
@section('keywords', \InnoShop\Common\Libraries\MetaInfo::getInstance($page)->getKeywords())

@push('header')
<style>
  /* Filter sidebar styles */
  .filter-sidebar {
    margin-bottom: 30px;
  }

  .filter-sidebar-item {
    margin-bottom: 30px;
  }

  .filter-sidebar-item .title {
    font-size: 24px;
    font-weight: 700;
    margin-bottom: 15px;
    color: #000;
    display: flex;
    align-items: center;
  }

  .filter-sidebar-item .title .badge {
    background-color: #f5f5f5;
    color: #000;
    font-size: 12px;
    font-weight: 600;
    padding: 4px 8px;
    margin-left: 10px;
  }

  .filter-sidebar .accordion-item {
    border: none;
    margin-bottom: 10px;
  }

  .filter-sidebar .accordion-title {
    padding: 5px 0;
  }

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
    color: #333;
    font-size: 18px;
  }

  .category-title.active,
  .category-subtitle-link.active {
    color: #ff0000 !important;
    font-weight: bold;
  }

  .category-subtitle-link {
    display: inline-block;
    font-size: 14px;
    color: #666;
    margin-top: 2px;
    padding: 0;
    text-decoration: none;
    transition: color .3s;
    position: relative;
    line-height: 1.2;
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

  .category-subtitle-link:not(.active):hover {
    color: #555;
  }

  /* Underline effect for hover and active states */
  .category-subtitle-link:hover:after,
  .category-subtitle-link.active:after {
    width: 100%;
    left: 0;
    right: auto;
  }

  /* Active category underline color */
  .category-subtitle-link.active:after {
    border-bottom-color: #ff0000;
  }

  /* Mobile page navigation styles */
  .mobile-page-nav {
    margin-bottom: 20px;
    background-color: #f8f9fa;
    padding: 15px 0;
  }

  .mobile-page-nav-inner {
    padding: 0 10px;
  }

  .mobile-page-nav-title {
    font-size: 18px;
    font-weight: 700;
    margin-bottom: 10px;
    color: #333;
  }

  .mobile-page-nav-items {
    display: flex;
    flex-wrap: wrap;
    padding-bottom: 10px;
  }

  .mobile-page-nav-item {
    flex: 0 0 calc(25% - 12px);
    display: inline-block;
    margin-right: 8px;
    margin-bottom: 8px;
    padding: 8px 10px;
    border-radius: 5px;
    background-color: #fff;
    text-decoration: none;
    color: #333;
    border: 1px solid #e5e5e5;
    transition: all 0.3s;
    text-align: center;
  }

  @media (max-width: 575px) {
    .mobile-page-nav-item {
      flex: 0 0 calc(50% - 8px);
    }
  }

  .mobile-page-nav-item.active {
    background-color: #fff;
    color: #ff0000;
    border-color: #ff0000;
  }

  .mobile-page-title {
    font-weight: 600;
    font-size: 14px;
    margin-bottom: 2px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    line-height: 1.2;
  }

  .mobile-page-subtitle {
    font-size: 11px;
    color: #666;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    line-height: 1.2;
    height: 14px;
  }

  .mobile-page-nav-item.active .mobile-page-subtitle {
    color: #ff0000;
  }

  .mobile-page-nav-item.active-highlight {
    box-shadow: 0 0 8px rgba(255, 0, 0, 0.3);
    transform: translateY(-2px);
  }

  .mobile-page-nav-item.empty {
    visibility: hidden;
    pointer-events: none;
    border: none;
    background: transparent;
  }

  /* Hide desktop sidebar on mobile */
  @media (max-width: 767px) {
    .filter-sidebar {
      display: none;
    }
  }
</style>
@endpush

@section('content')
  @if($page->show_breadcrumb)
      <x-front-breadcrumb type="page" :value="$page" />
  @endif

  @include('shared.mobile-page-nav', ['page' => $page, 'all_pages' => $all_pages])

  @hookinsert('page.show.top')

  @if(isset($result))
    {!! $result !!}
  @else
    <div class="page-service-content">
      <div class="container">
        <div class="row">
          <div class="col-md-3 d-none d-md-block">
            @include('shared.page-sidebar')
          </div>
          <div class="col-12 col-md-9">
            {!! $page->translation->content !!}
          </div>
        </div>
      </div>
    </div>
  @endif

  @hookinsert('page.show.bottom')

@endsection

@push('footer')
<script>
  $(document).ready(function() {
    // Make sure the active item is visible
    const activeItem = $('.mobile-page-nav-item.active');
    if (activeItem.length) {
      // Add a class to highlight the active item more prominently
      activeItem.addClass('active-highlight');
    }
  });
</script>
@endpush
