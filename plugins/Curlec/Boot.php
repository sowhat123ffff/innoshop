<?php
/**
 * Curlec (Razorpay) Payment Plugin Bootstrap
 *
 * @link       https://www.innoshop.com
 * @author     InnoShop <team@innoshop.com>
 * @license    https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

namespace Plugin\Curlec;

use Illuminate\Support\Facades\Log;

class Boot
{
    /**
     * Initialize the Curlec plugin: load routes, register hooks, and perform startup checks.
     */
    public function init(): void
    {
        Log::info('[Curlec] Boot::init() - Initializing Curlec payment plugin.');

        // Load plugin routes (front.php)
        $routePath = __DIR__ . '/Routes/front.php';
        if (file_exists($routePath)) {
            try {
                require_once $routePath;
                Log::info('[Curlec] Routes loaded: ' . $routePath);
            } catch (\Throwable $e) {
                Log::error('[Curlec] Failed to load routes: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            }
        } else {
            Log::warning('[Curlec] Route file not found: ' . $routePath);
        }

        // Register payment API data hook
        listen_hook_filter('service.payment.api.curlec.data', function ($data) {
            Log::debug('[Curlec] service.payment.api.curlec.data hook called', ['data_in' => $data]);
            try {
                $data['params'] = plugin_setting('curlec');
            } catch (\Throwable $e) {
                Log::error('[Curlec] Failed to inject plugin settings: ' . $e->getMessage());
            }
            Log::debug('[Curlec] service.payment.api.curlec.data hook returning', ['data_out' => $data]);
            return $data;
        });
        Log::info('[Curlec] Hook registered: service.payment.api.curlec.data');
    }
}