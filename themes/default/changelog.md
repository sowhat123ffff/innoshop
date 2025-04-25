# Changelog - Custom Form Implementation

This changelog documents all changes made to implement and improve the custom form functionality in the InnoShop platform.

## Latest Changes (26-Apr-2025)

### Summary of Changes Made on 26-Apr-2025

In this update, we made several improvements to the Member Data functionality and fixed issues with the help popup and form validation. We also documented the initial implementation of the Member Data feature, which allows customers to save and reuse personal information across multiple purchases.

### 1. Implemented Accordion-Style Q&A in Member Data Help Popup (themes/default/views/account/member_data/index.blade.php)

- Converted the help popup Q&A section to an accordion-style interface
- Added toggle icons (+) to each question that rotate when expanded
- Implemented JavaScript to show only one answer at a time
- Added smooth animations for expanding/collapsing answers
- Fixed a route error with the Privacy Policy link by using a JavaScript void link
- Enhanced the styling with better spacing, colors, and transitions

```html
<div class="member-data-question" data-qa="1">
  <p>(Q) What is Member Data?</p>
  <p>(问) 什么是成员资料?</p>
  <span class="qa-toggle"><i class="bi bi-plus"></i></span>
</div>
<div class="member-data-answer" id="qa-answer-1">
  <p>(A) Member Data are key personal records like Chinese name, gender, Solar and Lunar Dates of Birth, mobile number and etc.</p>
  <p>(答) 成员资料是主要的个人记录，如中文姓名、性别、出生日期、手机号码等。</p>
</div>
```

```css
.member-data-question.active .qa-toggle i {
  transform: rotate(45deg);
}

.member-data-answer {
  max-height: 0;
  overflow: hidden;
  transition: all 0.3s ease;
  opacity: 0;
}

.member-data-answer.active {
  max-height: 500px;
  opacity: 1;
}
```

### 2. Added Chinese Characters to Gender and Zodiac Fields (themes/default/views/account/member_data/form.blade.php)

- Updated the Gender radio buttons to display "男 Male" and "女 Female"
- Updated the Zodiac dropdown to include Chinese characters for each zodiac sign:
  - 鼠 Rat, 牛 Ox, 虎 Tiger, 兔 Rabbit, 龙 Dragon, 蛇 Snake, 马 Horse, 羊 Goat, 猴 Monkey, 鸡 Rooster, 狗 Dog, 猪 Pig
- Updated the auto-selection function for zodiac signs to use the new values with Chinese characters
- Fixed validation rules in MemberDataController to accept the new format with Chinese characters

```html
<div class="form-check form-check-inline">
  <input class="form-check-input" type="radio" name="member_data[gender]" id="genderMale" value="男 Male" required>
  <label class="form-check-label" for="genderMale">男 Male</label>
</div>
```

```php
// Updated validation rule in MemberDataController
'member_data.gender' => 'required|in:Male,Female,男 Male,女 Female',
```

### 3. Member Data Feature Implementation (New Functionality)

- Created a dedicated Member Data section in the customer account area
- Implemented a database table structure to store customer member data records
- Added ability for customers to create, edit, and delete member data entries
- Implemented a form with fields for:
  - Name (Chinese Original Name)
  - Gender (Male/Female with Chinese characters)
  - Chinese Zodiac (with automatic selection based on birth date)
  - Birth Date (with automatic conversion to Lunar Date)
  - Birth Time (using traditional Chinese time periods)
  - WhatsApp number
- Created a comprehensive help section explaining the purpose and benefits of Member Data
- Added integration with product custom forms to allow reuse of saved member data
- Implemented proper validation for all fields

```php
// Member Data model structure
class MemberData extends BaseModel
{
    protected $table = 'member_data';

    protected $fillable = [
        'customer_id', 'member_data',
    ];

    protected $casts = [
        'member_data' => 'array',
    ];
}
```

### 4. Benefits of the Changes

- Improved user experience with a cleaner, more interactive help popup
- Enhanced bilingual support with Chinese characters for gender and zodiac fields
- Fixed validation errors when saving member data with Chinese characters
- Better organization of help content with accordion-style Q&A
- Smoother animations and transitions for a more polished interface
- Customers can now save personal information for reuse across multiple purchases
- Reduced data entry time for returning customers
- Improved accuracy of customer information with proper validation
- Enhanced support for Chinese language and cultural elements (zodiac, lunar calendar)

## Previous Changes (25-Apr-2025)

### Summary of Changes Made on 25-Apr-2025

In this update, we made several UI improvements to enhance the mobile experience. We fixed issues with the "Add to Cart" and "Buy Now" buttons in mobile view and improved the positioning of the "Add to Wish List" button to prevent it from blocking product images.

### 1. Fixed "Add to Cart" and "Buy Now" Buttons in Mobile View (themes/default/views/products/show.blade.php)

- Restructured the product-info-btns container using flex layout for better responsiveness
- Added minimum width constraints to ensure buttons don't get too small on mobile screens
- Implemented responsive CSS to adjust button sizing and spacing on different screen sizes
- Added proper vertical alignment and centering for button content
- Improved button text display with proper overflow handling
- Removed the temporary testing button that was no longer needed

```html
<div class="product-info-btns d-flex flex-wrap">
  <div class="position-relative me-2 mb-2 mb-sm-0" style="flex: 1; min-width: 120px;">
    <button class="btn btn-primary add-cart w-100" data-id="{{ $product->id }}"
            data-price="{{ $product->masterSku->price }}" style="height: 50px !important; display: flex; align-items: center; justify-content: center;">
      {{ __('front/product.add_to_cart') }}
    </button>
  </div>
  <!-- Buy Now button with similar improvements -->
</div>
```

### 2. Improved "Add to Wish List" Button Positioning (plugins/CustomPlugin/Boot.php)

- Redesigned the wishlist button to be a small circular icon in the corner of product images
- Added custom CSS class for targeted styling without affecting other site elements
- Implemented responsive positioning that works well on both desktop and mobile screens
- Hid the "Add to Wish List" text on mobile, showing only the heart icon to save space
- Added subtle background and shadow for better visibility against different product images
- Made the button smaller on mobile screens to prevent it from blocking product images

```html
<div class="wishlist-container add-wishlist custom-wishlist-btn" data-in-wishlist="" data-id="{{ $productId }}" data-price="{{ $productPrice }}">
  <i class="bi bi-heart"></i> <span class="wishlist-text">Add to Wish List</span>
</div>
```

```css
/* Wishlist button styling */
.custom-wishlist-btn {
  position: absolute;
  top: 10px;
  right: 10px;
  background-color: rgba(255, 255, 255, 0.8);
  border-radius: 50%;
  width: 36px;
  height: 36px;
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: hidden;
  z-index: 5;
}

/* Mobile-specific adjustments */
@media (max-width: 767px) {
  .custom-wishlist-btn {
    width: 30px;
    height: 30px;
    top: 5px;
    right: 5px;
  }
}
```

### 3. Benefits of the Changes

- Improved mobile user experience with properly sized and positioned buttons
- Eliminated UI issues where the wishlist button was blocking product images
- Enhanced visual consistency across different screen sizes
- Better touch targets for mobile users
- More efficient use of screen space on smaller devices

## Previous Changes (25-Apr-2025)

### Summary of Changes Made on 25-Apr-2025

In this update, we made several improvements to the website's navigation and customer support features. We fixed the subtitle display in the mobile menu and added a WhatsApp floating button that appears on all customer-facing pages.

### 1. Fixed Mobile Menu Subtitle Display

- Fixed an issue where menu subtitles weren't displaying correctly in the mobile view
- Added subtitle display under each menu item in the mobile menu
- Made subtitles clickable with the same link as the main menu item
- Improved styling and spacing for better readability
- Added proper alignment and positioning of dropdown icons

### 2. Added WhatsApp Floating Button to All Pages

- Created a reusable WhatsApp button component in `themes/default/views/components/whatsapp-button.blade.php`
- Added the component to the main layout file (`themes/default/views/layouts/app.blade.php`) so it appears on all pages
- Implemented a popup with two contact options: "Product Enquiry Hotline" and "Feng Shui Enquiry Hotline"
- Added smooth animations for opening and closing the popup
- Made the button toggle the popup (clicking again closes it)
- Optimized for both desktop and mobile views

### 3. Enhanced User Experience

- Added subtle animations and transitions for a more polished look
- Implemented proper circular image display for the WhatsApp logo
- Added visual feedback when the popup is open (button state changes)
- Made the entire option cards clickable, not just the text
- Added staggered animations for the options when the popup appears
- Ensured the floating button follows the user as they scroll

### 4. Technical Improvements

- Organized code into a reusable component for better maintainability
- Used CSS transitions and transforms for smooth animations
- Implemented proper event handling for clicks and touch events
- Added fallback handling for missing images
- Ensured responsive design works on all screen sizes

## Previous Changes (26-May-2025)

### Summary of Changes Made on 26-May-2025

In this update, we added support for two additional product sections (4 and 5) on the homepage, allowing for more flexible product displays and marketing opportunities.

### 1. Added New Hook Inserts (themes/default/views/home.blade.php)

- Added `@hookinsert('home.content.bottom4')` and `@hookinsert('home.content.bottom5')` in the home.blade.php file
- These hooks allow for additional product sections to be displayed on the homepage
- Maintained consistent spacing and formatting with existing hook inserts

### 2. Extended Product Section Support (plugins/CustomPlugin/Boot.php)

- Added calls to `$this->setupProductSection(4)` and `$this->setupProductSection(5)` in the init method
- Added checkboxes for sections 4 and 5 in the admin panel product edit page
- Updated the JavaScript code to handle the new sections:
  - Added checks for section4 and section5 in the initialization
  - Added variables to capture section4 and section5 checkbox states
  - Updated the JSON data sent to the server to include section4 and section5

### 3. Added Configuration Fields (plugins/CustomPlugin/fields.php)

- Added configuration options for sections 4 and 5, including:
  - Enable/disable toggles for each section
  - Title and subtitle fields with default values in English and Chinese
  - Product ID text areas for specifying which products should appear in each section
- Followed the same pattern as existing sections 1-3 for consistency

### 4. Benefits of the Changes

- More flexible homepage layout with up to 5 distinct product sections
- Ability to showcase different product categories or collections
- Enhanced marketing capabilities for special promotions or seasonal items
- Consistent user experience for both customers and administrators

## Previous Changes (25-May-2025)

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
