<?php
/**
 * Copyright (c) Since 2024 InnoShop - All Rights Reserved
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
     * Method executed when the plugin is initialized.
     */
    public function init(): void
    {
        Log::debug('Curlec Plugin: Boot::init() called');

        // Register plugin routes
        if (file_exists(__DIR__ . '/Routes/front.php')) {
            Log::debug('Curlec Plugin: Loading Routes/front.php');
            require_once __DIR__ . '/Routes/front.php';
            Log::debug('Curlec Plugin: Routes/front.php loaded successfully');
        } else {
            Log::warning('Curlec Plugin: Routes/front.php not found');
        }

        Log::debug('Curlec Plugin: Registering hook filter service.payment.api.curlec.data');
        listen_hook_filter('service.payment.api.curlec.data', function ($data) {
            Log::debug('Curlec Plugin: service.payment.api.curlec.data hook called', ['data_in' => $data]);
            // Inject Curlec (Razorpay) plugin settings into the payment data
            $data['params'] = plugin_setting('curlec');
            Log::debug('Curlec Plugin: service.payment.api.curlec.data hook returning', ['data_out' => $data]);
            return $data;
        });
        Log::debug('Curlec Plugin: Hook filter registered');
    }
}