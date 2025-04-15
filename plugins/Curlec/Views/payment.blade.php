<div class="curlec-payment card w-max-700 m-auto h-min-300">
  <div class="card-body">
    <div class="fs-5 mb-3">{{ __('Curlec (Razorpay) Payment') }}</div>
    <table class="table mb-3 table-bordered">
      <thead>
        <tr>
          <th>{{ __('Order Number') }}</th>
          <th>{{ __('Order Time') }}</th>
          <th>{{ __('Total') }}</th>
          <th>{{ __('Payment Method') }}</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>{{ $order->number }}</td>
          <td>{{ $order->created_at->format('Y-m-d') }}</td>
          <td>{{ currency_format($order->total) }}</td>
          <td>{{ $order->billing_method_name }}</td>
        </tr>
      </tbody>
    </table>
    <div class="fs-5 mb-3">{{ __('Payment Status') }}</div>
    @if(isset($payment_status) && $payment_status === 'success')
      <div class="alert alert-success">
        <i class="bi bi-check-circle-fill"></i>
        {{ __('Your payment was successful!') }}
      </div>
    @elseif(isset($payment_status) && $payment_status === 'failed')
      <div class="alert alert-danger">
        <i class="bi bi-x-circle-fill"></i>
        {{ __('Your payment failed. Please try again or contact support.') }}
      </div>
    @else
      <div class="alert alert-info">
        <i class="bi bi-info-circle-fill"></i>
        {{ __('Processing your payment...') }}
      </div>
    @endif

    <a href="{{ url('/') }}" class="btn btn-primary mt-4">
      {{ __('Return to Home') }}
    </a>
  </div>
<!-- Razorpay Checkout Script -->
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
@php
    $locale = app()->getLocale();
    $locales = function_exists('locales') ? locales() : [];
    if (is_array($locales) && count($locales) > 1) {
        $routeName = $locale . '.front.curlec.payment.order';
    } else {
        $routeName = 'front.curlec.payment.order';
    }
    try {
        \Log::debug('Curlec Payment: Attempting to resolve route', ['route' => $routeName]);
        $curlecOrderRoute = route($routeName);
        \Log::debug('Curlec Payment: Route resolved successfully', ['route' => $routeName, 'url' => $curlecOrderRoute]);
    } catch (\Exception $e) {
        \Log::error('Curlec Payment: Route resolution failed', ['route' => $routeName, 'error' => $e->getMessage()]);
        $curlecOrderRoute = null;
    }
@endphp
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Only trigger if payment is not already successful or failed
    @if(!isset($payment_status))
    fetch("{{ $curlecOrderRoute }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            receipt: '{{ $order->number }}',
            amount: {{ (int)($order->total * 100) }}, // Amount in cents/paise
            currency: 'MYR'
        })
    })
    .then(response => response.json())
    .then(data => {
        var options = {
            key: '{{ plugin_setting("curlec.key_id") }}', // Enter the Key ID
            amount: data.amount,
            currency: data.currency,
            name: 'InnoShop',
            order_id: data.order_id,
            handler: function (response){
                // Redirect or AJAX to confirm payment
                window.location.reload();
            },
            prefill: {
                email: '{{ $order->customer_email }}'
            },
            theme: {
                color: "#3399cc"
            }
        };
        var rzp = new Razorpay(options);
        rzp.open();
    })
    .catch(function(error) {
        alert('Failed to initiate payment: ' + error);
    });
    @endif
});
</script>
</div>