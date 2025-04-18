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
    <div class="product-item-info product-hover-title">
      <div class="product-name">
          {{ $product->fallbackName() }}
      </div>
    </div>
  </div>
@endif

<style>
.product-grid-item .product-item-info {
  opacity: 0;
  pointer-events: none;
  transition: opacity 0.3s;
  position: absolute;
  left: 0; right: 0; bottom: 0; top: 0;
  background: rgba(0,0,0,0.5);
  color: #fff;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.1rem;
  font-weight: bold;
  text-align: center;
}
.product-grid-item { position: relative; overflow: hidden; }
.product-grid-item:hover .product-item-info { opacity: 1; pointer-events: auto; }
</style>
