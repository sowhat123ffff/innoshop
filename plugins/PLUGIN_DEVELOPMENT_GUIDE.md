# InnoShop Plugin Development Guide

> **IMPORTANT:**
> Always read and process the entire contents of this file before making any changes or using it as a reference. Partial reading or editing may result in missed details, broken structure, or plugin development errors.


## Overview
This guide details the standards, structure, and best practices for developing plugins for InnoShop. It references real plugin examples in your codebase and is designed for both human and AI developers to ensure consistent, robust, and maintainable plugin development.

---

## Laravel Multilingual Route Pitfalls

- **Locale Prefixes:** If your system supports multiple languages, routes will usually have prefixes like `/en`, `/zh`, etc. If no locale is present, `/` is used as the default.
- **Route Name Prefix Issues:**
    - If you use `.name('front.')` in a route group, Laravel will automatically prefix all route names in that group with `front.`.
    - Do NOT add the same prefix in both your plugin and the main system. This will result in names like `en.front.front.xxx`.
    - Best practice: add the prefix in the main system (e.g., RouteServiceProvider) only. Plugin route definitions should use `.name('xxx')` only.
- **Consistency in Controller/Blade:**
    - Always build route names in controllers and views to match the actual route names in `route:list`.
    - Example: If the route name is `en.front.curlec.payment.order`, use `$locale . '.front.curlec.payment.order'` everywhere.
- **Clear All Laravel Caches After Route Changes:**
    - Always clear route, config, view, and application caches after modifying routes, or changes may not take effect.
- **Debugging Checklist:**
    1. Use `php artisan route:list` to check the real route names.
    2. Ensure all code constructs and calls route names exactly as defined.
    3. Clear all caches before retesting.

---

## Plugin Principles
- **Automatic Discovery:** Plugins leverage Laravel's discovery/bootstrap mechanisms and must be PSR-4 autoload compliant.
- **Naming:** Use StudlyCaps (capital camel case) for directory and class names.
- **Extensibility:** Plugins can extend any part of the system using hooks, routes, views, and configuration.

---

## Directory Structure
A typical plugin directory looks like:

```
PluginName/
├── Boot.php                # Plugin bootstrap/init class (required)
├── config.json             # Plugin metadata (required)
├── fields.php              # Plugin config fields (optional, recommended)
├── composer.json           # Composer dependencies (optional)
├── Controllers/            # Controllers (optional)
├── Lang/                   # Language packs (optional)
├── Middleware/             # Middleware (optional)
├── Migrations/             # DB migrations (optional)
├── Models/                 # Eloquent models (optional)
├── Repositories/           # Data repositories (optional)
├── Routes/                 # Routing files (panel.php/front.php) (optional)
├── Static/ or Public/      # Static assets (optional)
├── Views/                  # Blade templates (optional)
```

### Example: `BankTransfer`, `FixedShipping`, and `OpenAI` Plugins

#### BankTransfer
```
BankTransfer/
├── Boot.php
├── Lang/
│   ├── en/
│   └── zh_cn/
├── Public/
├── Views/
│   └── payment.blade.php
├── config.json
├── fields.php
```

#### FixedShipping
```
FixedShipping/
├── Boot.php
├── Lang/
│   ├── en/
│   └── zh_cn/
├── Public/
├── config.json
├── fields.php
```

#### OpenAI
```
OpenAi/
├── Libraries/
│   └── OpenAI.php
├── Public/
├── Services/
│   └── OpenAiService.php
├── config.json
├── fields.php
```

---

## Required Files
### `config.json`
Defines plugin metadata. Examples:

#### BankTransfer
```json
{
    "code": "bank_transfer",
    "name": {"zh_cn": "银行转账", "en": "Bank Transfer"},
    "description": {"zh_cn": "银行转账", "en": "Bank Transfer"},
    "type": "billing",
    "version": "v1.0.0",
    "icon": "/images/logo.png",
    "author": {"name": "InnoShop", "email": "edward@innoshop.com"}
}
```
#### FixedShipping
```json
{
    "code": "fixed_shipping",
    "name": {"zh_cn": "固定运费", "en": "Fixed Shipping"},
    "description": {"zh_cn": "固定运费", "en": "Fixed Shipping"},
    "type": "shipping",
    "version": "v1.0.0",
    "icon": "/images/logo.png",
    "author": {"name": "InnoShop", "email": "edward@innoshop.com"}
}
```
#### OpenAI
```json
{
  "code": "open_ai",
  "name": {"zh_cn": "OpenAI 集成", "en": "OpenAI Integration"},
  "description": {"zh_cn": "OpenAI 集成, 优化并生成产品、文章相关内容", "en": "OpenAI Integration for Content Generation"},
  "type": "intelli",
  "version": "v1.0.0",
  "icon": "/images/logo.png",
  "author": {"name": "InnoShop", "email": "edward@innoshop.com"}
}
```

### `Boot.php`
The entry point for plugin initialization. Must define an `init()` method for registering hooks, routes, etc.
Examples:
#### BankTransfer
```php
namespace Plugin\BankTransfer;
class Boot {
    public function init(): void {
        listen_hook_filter('service.payment.api.bank_transfer.data', function ($data) {
            $data['params'] = plugin_setting('bank_transfer');
            return $data;
        });
    }
}
```
#### FixedShipping
```php
namespace Plugin\FixedShipping;
use InnoShop\Common\Entities\ShippingEntity;
use InnoShop\Plugin\Core\BaseBoot;
class Boot extends BaseBoot {
    public function init() {}
    public function getQuotes(ShippingEntity $entity): array {
        $code     = $this->plugin->getCode();
        $resource = $this->pluginResource->jsonSerialize();
        $cost     = $this->getShippingFee($entity);
        $quotes[] = [
            'type'        => 'shipping',
            'code'        => "{$code}.0",
            'name'        => $resource['name'],
            'description' => $resource['description'],
            'icon'        => $resource['icon'],
            'cost'        => $cost,
            'cost_format' => currency_format($cost),
        ];
        return $quotes;
    }
}
```

### `fields.php`
Defines plugin configuration fields for the admin UI.
Examples:
#### BankTransfer
```php
return [
    [
        'name'      => 'bank_name',
        'label_key' => 'common.bank_name',
        'type'      => 'string',
        'required'  => true,
        'rules'     => 'required',
    ],
    [
        'name'      => 'bank_account',
        'label_key' => 'common.bank_account',
        'type'      => 'string',
        'required'  => true,
        'rules'     => 'required',
    ],
    [
        'name'      => 'bank_username',
        'label_key' => 'common.bank_username',
        'type'      => 'string',
        'required'  => true,
        'rules'     => 'required',
    ],
    [
        'name'      => 'bank_comment',
        'label_key' => 'common.bank_comment',
        'type'      => 'textarea',
        'required'  => false,
    ],
];
```
#### FixedShipping
```php
return [
    [
        'name'      => 'type',
        'label_key' => 'common.type',
        'type'      => 'select',
        'options'   => [
            ['value' => 'fixed', 'label_key' => 'common.fixed'],
            ['value' => 'percent', 'label_key' => 'common.percent'],
        ],
        'required' => true,
        'rules'    => 'required',
    ],
    [
        'name'      => 'value',
        'label_key' => 'common.value',
        'type'      => 'string',
        'required'  => true,
        'rules'     => 'required',
    ],
];
```
#### OpenAI
```php
return [
    [
        'name'     => 'api_key',
        'label'    => 'API Key',
        'type'     => 'string',
        'required' => true,
        'rules'    => 'required',
    ],
    [
        'name'        => 'proxy_url',
        'label'       => '代理地址',
        'type'        => 'string',
        'required'    => true,
        'rules'       => 'required',
        'description' => '不填写则使用官方接口地址: https://api.openai.com/v1/',
    ],
    [
        'name'    => 'model_type',
        'label'   => '使用模型',
        'type'    => 'select',
        'options' => [
            ['value' => 'gpt-4o-mini', 'label' => 'GPT-4o mini'],
            ['value' => 'gpt-4o', 'label' => 'GPT-4o'],
            ['value' => 'gpt-4-turbo', 'label' => 'GPT-4 Turbo'],
        ],
        'emptyOption' => false,
        'required'    => true,
        'rules'       => 'required',
    ],
];
```

---

## Directory/Component Reference
- **Controllers/**: Handles plugin logic (e.g., PaymentController.php)
- **Lang/**: Language packs (e.g., `en/messages.php`, `zh_cn/messages.php`)
- **Routes/**: Contains `panel.php` (backend routes) and `front.php` (frontend routes)
- **Views/**: Blade templates for UI
- **Public/** or **Static/**: Assets (images, JS, CSS)
- **Models/**: Eloquent models for DB tables
- **Repositories/**: Data access classes
- **Migrations/**: DB migration files

---

## Configuration Field Types
- `bool`: Boolean switch
- `checkbox`: Multi-select
- `image`: Image upload
- `multi-rich-text`: Multi-language rich text
- `multi-string`: Multi-language string
- `multi-textarea`: Multi-language textarea
- `rich-text`: Rich text
- `select`: Dropdown
- `string`: Short text
- `textarea`: Long text

### Usage
Access plugin settings anywhere with:
```php
plugin_setting('pluginName', 'configKey');
```

---

## Development Standards
- **Naming:** Use StudlyCaps for directories/classes
- **Structure:** Follow the directory structure above
- **Config:** Use `fields.php` and `config.json` for settings
- **Assets:** Reference static files with `plugin_asset()`
- **Routes:** Place in `Routes/panel.php` and `Routes/front.php`

---

## Plugin Development Process
1. **Create Directory:** `plugins/YourPluginName/`
2. **Add `config.json`**
3. **Add `Boot.php`** with `init()` method
4. **Add Controllers/Models/Views** as needed
5. **Define Routes** in `Routes/`
6. **Add Config Fields** in `fields.php` (if needed)
7. **Add Language Packs** in `Lang/` (if needed)
8. **Add Assets** in `Public/` or `Static/`
9. **Test plugin activation/deactivation via admin panel**

---

## Example: Minimal Plugin Skeleton
```
YourPlugin/
├── Boot.php
├── config.json
```

## Example: Full-featured Plugin
```
YourPlugin/
├── Boot.php
├── config.json
├── fields.php
├── Controllers/
├── Routes/
├── Views/
├── Lang/
├── Public/
```

---

## Deep Dive: Advanced Plugin Components

### 1. Hooks & Events
Plugins can extend or modify system behavior by registering hooks/filters in `Boot.php`.

**BankTransfer Example:**
```php
listen_hook_filter('service.payment.api.bank_transfer.data', function ($data) {
    $data['params'] = plugin_setting('bank_transfer');
    return $data;
});
```
- Use unique hook names for each plugin.
- You can register multiple hooks in `init()`.

**FixedShipping Example:**
```php
public function getQuotes(ShippingEntity $entity): array {
    // ...custom shipping calculation logic
    return $quotes;
}
```
- Shipping plugins often expose methods called by the system.

---

### 2. Language Packs (Localization)
Store all user-facing strings in `Lang/{locale}/common.php` or similar files.

**BankTransfer Example (`Lang/en/common.php`):**
```php
return [
    'bank_info'      => 'Bank Information',
    'bank_name'      => 'Bank Name',
    'bank_account'   => 'Account Number',
    'bank_username'  => 'Account Name',
    'bank_comment'   => 'Comment',
    'order_success'  => 'Order submitted successfully, please pay to the bank account:',
    'number'         => 'Order number',
    'order_time'     => 'Order time',
    'total'          => 'Amount',
    'payment_method' => 'Payment method',
    'bank_transfer'  => 'Bank transfer',
];
```
- Use `__('PluginName::common.key')` in Blade/views for translation.

---

### 3. Blade Views/Templates
Store plugin UI in the `Views/` directory using Laravel Blade syntax.

**BankTransfer Example (`Views/payment.blade.php`):**
```blade
<div class="bank-transfer card w-max-700 m-auto h-min-300">
  <div class="card-body">
    <div class="fs-5 mb-3">{{ __('BankTransfer::common.order_success') }}</div>
    <!-- ...table and bank info ... -->
    <div>
      <p>{{ plugin_setting('bank_transfer.bank_name') }}</p>
      <p>{{ plugin_setting('bank_transfer.bank_account') }}</p>
      <p>{{ plugin_setting('bank_transfer.bank_comment') }}</p>
    </div>
    <button type="button" class="btn btn-primary btn-bank-transfer">{{ __('front/common.confirm') }}</button>
  </div>
</div>
```
- Use `plugin_setting()` for config values.
- Use translation helpers for all strings.

---

### 4. Plugin Assets (Static Files)
Place images, JS, CSS, etc. in `Public/` or `Static/`.
- Reference assets in Blade with `plugin_asset('PluginName:path/to/file')`.
- Example: `<img src="{{ plugin_asset('BankTransfer:logo.png') }}" />`

---

### 5. Advanced Configuration & Field Types
- Use `fields.php` to define complex configuration, including selects, multi-language, and validation rules.
- See OpenAI and FixedShipping for select/multi-select and advanced types.

**OpenAI Example:**
```php
return [
    [
        'name'     => 'api_key',
        'label'    => 'API Key',
        'type'     => 'string',
        'required' => true,
        'rules'    => 'required',
    ],
    [
        'name'        => 'proxy_url',
        'label'       => '代理地址',
        'type'        => 'string',
        'required'    => true,
        'rules'       => 'required',
        'description' => '不填写则使用官方接口地址: https://api.openai.com/v1/',
    ],
    [
        'name'    => 'model_type',
        'label'   => '使用模型',
        'type'    => 'select',
        'options' => [
            ['value' => 'gpt-4o-mini', 'label' => 'GPT-4o mini'],
            ['value' => 'gpt-4o', 'label' => 'GPT-4o'],
            ['value' => 'gpt-4-turbo', 'label' => 'GPT-4 Turbo'],
        ],
        'emptyOption' => false,
        'required'    => true,
        'rules'       => 'required',
    ],
];
```
- Use `label_key` and multi-language fields for internationalization.

---

## Eloquent Model Location for InnoShop

- **All core and shared Eloquent models are located in:**
  
  `innopacks/common/src/Models`
  
- **Example:**
  - Product SKU model: `InnoShop\Common\Models\Product\Sku`
  - Use this model for product price, inventory, and SKU-related queries.

- **Best Practice:**
  - Always use the Eloquent model (e.g., `\InnoShop\Common\Models\Product\Sku`) for database queries in plugins, instead of `DB::table`, whenever possible.
  - This ensures maintainability, leverages Laravel’s ORM features, and keeps plugin code robust and clean.

- **How to use in plugin Boot.php:**
  ```php
  // Get min/max price for available SKUs
  $min = \InnoShop\Common\Models\Product\Sku::whereNotNull('price')->where('price', '>', 0)->min('price');
  $max = \InnoShop\Common\Models\Product\Sku::whereNotNull('price')->where('price', '>', 0)->max('price');
  ```

- **If you need to add a new model:**
  - Place it in `innopacks/common/src/Models` following PSR-4 and StudlyCaps naming conventions.

---

## 6. Custom Libraries & Services
Complex plugins can include custom libraries (e.g., API clients) and service classes for business logic.

**OpenAI Example:**
- `Libraries/OpenAI.php` encapsulates all API communication and handles authentication, error handling, and HTTP requests.
- `Services/OpenAiService.php` provides a service layer for business logic, calling the library and handling errors for the application.

```php
// Libraries/OpenAI.php
class OpenAI {
    public function __construct() {
        $apiKey = plugin_setting('open_ai', 'api_key');
        // ...
    }
    // ...
}

// Services/OpenAiService.php
class OpenAiService {
    public function complete($requestData): mixed {
        $result = OpenAI::getInstance()->completions($requestData);
        if (isset($result['error'])) {
            throw new Exception($result['error']['message']);
        }
        return $result['choices'][0]['message']['content'];
    }
}
```
- This separation improves maintainability and testability.

---

## 7. Advanced Error Handling
- Use exceptions for error states in libraries/services.
- Log errors with Laravel's logging facilities (`Log::error(...)`).
- Provide user-friendly error messages in views.

---

## 8. Plugin Testing & Debugging
- Test plugin activation/deactivation in the admin panel.
- Use Laravel's built-in testing tools for controllers/models if needed.
- Add debug logging in Boot.php and service classes during development.
- Use feature toggles in `fields.php` to enable/disable features for testing.

---

## 9. Database Migrations (if needed)
- If your plugin needs custom tables, add migration files in a `Migrations/` directory.
- Use Laravel's migration syntax.
- Example:
```php
// Migrations/2024_01_01_000000_create_openai_logs_table.php
Schema::create('openai_logs', function (Blueprint $table) {
    $table->id();
    $table->string('prompt');
    $table->text('response');
    $table->timestamps();
});
```
- Run migrations during plugin activation if necessary.

---

## 10. Advanced Field Types & Dynamic Config
- Use `options` for select fields, `label_key` for multi-language, and `description` for help text.
- Support dynamic field loading via AJAX if needed (for advanced plugins).
- Example (OpenAI):
```php
[
    'name'    => 'model_type',
    'label'   => '使用模型',
    'type'    => 'select',
    'options' => [
        ['value' => 'gpt-4o-mini', 'label' => 'GPT-4o mini'],
        ['value' => 'gpt-4o', 'label' => 'GPT-4o'],
        ['value' => 'gpt-4-turbo', 'label' => 'GPT-4 Turbo'],
    ],
    'emptyOption' => false,
    'required'    => true,
    'rules'       => 'required',
],
```

---

## 11. Best Practices for Maintainability
- Separate business logic (services/libraries) from controllers/views.
- Document all config fields and hooks.
- Use language packs for all user-facing text.
- Keep plugin logic isolated from the core system.
- Use versioning in `config.json` and document changes.
- Prefer composition over inheritance for plugin extensibility.
- Clean up resources (DB, files) on plugin deactivation if needed.

---

## Structure for Frontend Hook Discovery (for AI/Automation)

To enable AI or automated tools to efficiently discover insertable hooks for plugin UI injection, follow this structure and process:

### 1. Key Frontend Layout Files to Index
- `resources/views/layouts/app.blade.php` (main HTML structure, includes header/footer)
- `resources/views/layouts/header.blade.php` (site header, navigation, language/currency switch)
- `resources/views/layouts/footer.blade.php` (site footer, copyright, scripts)
- `resources/views/shared/filter_sidebar.blade.php` (sidebar filters/categories)
- `resources/views/products/index.blade.php` (main product listing page)
- Any theme override files in `themes/<theme>/views/layouts/` or `themes/<theme>/views/shared/`

### 2. How to Find Insertable Hooks
- Search for `@hookinsert('...')` and `@hookupdate('...')` in all Blade files.
- Record the hook name and the file/line where it appears.
- Example index entry:
  ```json
  {
    "layout.footer.bottom": "resources/views/layouts/footer.blade.php:LINE",
    "layout.header.top": "resources/views/layouts/header.blade.php:LINE",
    "front.layout.app.head.bottom": "resources/views/layouts/app.blade.php:LINE"
  }
  ```
- This index allows plugins (or AI) to select the most appropriate hook for UI injection.

### 3. Typical Insertable Hook Locations
- **Head:** For meta, CSS, or head scripts (`front.layout.app.head.bottom`)
- **Header:** For navigation/language/currency (`layout.header.top`, `layouts.header.currency.after`, etc.)
- **Sidebar:** If present, e.g. `shared.filter_sidebar.custom` (must be added by theme)
- **Footer:** For scripts or copyright (`layout.footer.bottom`, `layout.footer.top`)
- **Menu/Navigation:** `layouts.header.menu.pc`, `layouts.header.menu.mobile`

### 4. Best Practice for Plugin UI Injection
- Always prefer using a hook that is as close as possible to your desired UI location.
- If no suitable hook exists, document the need for a new hook in the theme.
- Avoid direct DOM manipulation unless absolutely necessary.

### 5. Example: How AI Should Use This Structure
- When asked to inject UI, AI should:
  1. Search the above files for all `@hookinsert`/`@hookupdate`.
  2. Build an index of available hooks.
  3. Suggest or use the most contextually appropriate hook for the plugin's purpose.
  4. If no hook is available, recommend a theme update or fallback to JS injection.

---

## Activation/Deactivation
Plugins are managed from the InnoShop admin backend. Activation runs `Boot::init()`, loading routes, hooks, and config.

---

## Best Practices & Tips
- Keep plugin logic isolated from core.
- Use hooks for extensibility.
- Document your config fields and routes.
- Use language packs for all user-facing strings.
- Test thoroughly in both admin and frontend contexts.

---

## Conclusion
InnoShop plugins are powerful and flexible. By following this guide and referencing real plugin examples, you can build robust, maintainable, and extensible plugins for any use case.

---

_Last updated: 2025-04-16_
