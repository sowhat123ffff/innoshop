@if($product->fallbackName())
  <div class="product-grid-item {{ request('style_list') ?? '' }}">
    <div class="image">
      <a href="{{ $product->url }}">
        <img src="{{ $product->image_url }}" class="img-fluid">
      </a>
      <div class="wishlist-container add-wishlist" data-in-wishlist="{{ $product->hasFavorite() }}"
           data-id="{{ $product->id }}" data-price="{{ $product->masterSku->price }}">
        <i class="bi bi-heart{{ $product->hasFavorite() ? '-fill' : '' }}"></i> {{ __('front/product.add_wishlist') }}
      </div>
    </div>
    <div class="product-item-info">
      <div class="product-name">
        <a href="{{ $product->url }}">
          {!! str_replace(['&lt;br&gt;', '<br>'], '<br>', e($product->fallbackName())) !!}
        </a>
      </div>

      @hookinsert('product.list_item.name.after')

      @if(request('style_list') == 'list')
        <div class="sub-product-title">{{ $product->fallbackName('summary') }}</div>
      @endif

      <div class="product-bottom">
        <div class="product-bottom-btns">
          <div class="btn-add-cart cursor-pointer" data-id="{{ $product->id }}"
               data-price="{{ $product->masterSku->getFinalPrice() }}"
               data-sku-id="{{ $product->masterSku->id }}">{{ __('front/cart.add_to_cart') }}
          </div>
        </div>
        <div class="product-price">
          @if ($product->masterSku->origin_price)
            <div class="price-old">{{ $product->masterSku->origin_price_format }}</div>
          @endif
          <div class="price-new">{{ $product->masterSku->getFinalPriceFormat() }}</div>
        </div>
      </div>
    </div>
  </div>
@endif

<style>
  .product-grid-item .product-name a {
    white-space: normal;
    overflow: visible;
    text-overflow: clip;
    display: block;
    height: auto;
    line-height: 1.4;
    word-break: break-word;
  }
</style>