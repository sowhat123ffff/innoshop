# Changelog - Custom Form Implementation

This changelog documents all changes made to implement and improve the custom form functionality in the InnoShop platform.

## Latest Changes (25-May-2025)

### Summary of Changes Made on 25-May-2025

In this update, we fixed a critical issue with custom-enabled products in the cart. Previously, when a customer added the same product multiple times with different custom information, only the last custom information was saved, and the previous ones were lost. Now, each product with custom form data is treated as a unique item in the cart.

### 1. Fixed Cart Item Stacking Issue (innopacks/common/src/Repositories/CartItemRepo.php)

```php
// Check if the product has custom_enabled and custom_data
$hasCustomData = isset($data['custom_data']) && !empty($data['custom_data']);
$isCustomEnabled = false;

// Get the product to check if custom_enabled is true
if ($hasCustomData) {
    $sku = Sku::query()->where('code', $data['sku_code'])->first();
    if ($sku && $sku->product) {
        $isCustomEnabled = (bool)$sku->product->custom_enabled;
    }
}

// If product has custom_enabled=true and custom_data is provided, always create a new cart item
if ($isCustomEnabled && $hasCustomData) {
    $cart = new CartItem($data);
    $cart->saveOrFail();
} else {
    // Standard behavior for non-custom products
    // ...
}
```

- Modified the `CartItemRepo::create()` method to check if a product has `custom_enabled=true`
- When a product has `custom_enabled=true` and custom form data is provided, the system now creates a new cart item instead of incrementing the quantity
- This ensures that each custom form submission creates a separate cart item with its own unique custom data
- Regular products (without custom forms) continue to stack quantities as before

### 2. Benefits of the Change

- When a customer buys multiple of the same product with different custom information, each set of custom information is now preserved
- Each product instance with custom data appears as a separate item in the cart with quantity=1
- All custom form data is properly saved to the database and displayed in the admin panel
- This prevents data loss when customers need to order multiple instances of the same product with different custom information

## Previous Changes (24-Apr-2025)

### Summary of Changes Made on 24-Apr-2025

In this session, we implemented several key improvements to the custom form functionality in the InnoShop platform. The main focus was on ensuring custom form data is properly saved to the database and displayed correctly in the admin panel, especially when multiple products with custom forms are ordered together.

### 1. Added "Add data to database" Button (themes/default/views/products/show.blade.php)

```php
// Added a temporary button for testing custom form data submission
<button type="button" id="saveCustomDataBtn" class="btn btn-warning btn-lg w-100">
  <i class="bi bi-database-add"></i> Add data to database (Test)
</button>
```

- Created a dedicated button to test saving custom form data to the database
- Implemented form validation for all custom fields
- Added visual feedback with success badges and button color animations
- Fixed SKU ID handling to ensure proper data submission
- Added detailed error messages and console logging for debugging

### 2. Updated "Add to Cart" and "Buy Now" Buttons (themes/default/views/products/show.blade.php)

```javascript
// Send the data to the server directly instead of using inno.addCart
axios.post(urls.cart_add, cartData)
  .then(function(res) {
    if (res.success) {
      // Show success message and update UI
      // ...
    }
  })
```

- Applied the same custom data saving functionality to the regular buttons
- Added success indicators and visual feedback
- Improved error handling and user experience

### 3. Removed Custom Data from Order Comments (themes/default/views/checkout/index.blade.php)

```javascript
// Add the custom data directly to the current object
// This is now the primary way custom data is stored and retrieved
current.custom_data = customData;

// Note: We no longer need to add custom data to the comment
// The custom_data field is now properly handled throughout the system
```

- Removed code that added custom form data to order comments
- Simplified custom data handling during checkout
- Removed unnecessary state variables

### 4. Improved Admin Panel Display (innopacks/panel/resources/views/orders/info.blade.php)

```php
// Create an array to store custom data for each order item
$itemsWithCustomData = [];

// Check if the order has items with custom data
foreach ($order->items as $item) {
  $itemCustomData = null;

  // Check for custom_data field in the order item
  if (isset($item->custom_data) && !empty($item->custom_data)) {
    // Process custom data...
  }

  // If we found custom data for this item, add it to our array
  if (!empty($itemCustomData)) {
    $itemsWithCustomData[] = [
      'item' => $item,
      'customData' => $itemCustomData
    ];
  }
}
```

- Completely redesigned custom information display to handle multiple products
- Added product headers with item information for each custom data section
- Added visual indicators in the order items table for products with custom data
- Added links from order items to the custom information section
- Simplified the legacy parser for backward compatibility
- Added a note informing administrators that "=== CUSTOM INFORMATION ===" is no longer needed

### 5. Fixed Data Flow Issues

- Ensured custom data is properly saved in the inno_cart_items.custom_data field
- Implemented proper transfer of custom data from cart to order during checkout
- Fixed issue where multiple products with custom forms in the same order would overwrite each other's data
- Ensured each product's custom data is displayed separately in the admin panel

### 6. Documentation

- Created readme.md file with overview of the theme and custom form implementation
- Created this changelog.md file to document all changes made

## Previous Features

- Custom form with fields for name, gender, DOB, zodiac, time of birth, and WhatsApp
- Form validation with inline error messages
- Custom data storage in the database
- Admin panel display for custom information
