@extends('panel::layouts.app')
@section('title', __('panel/menu.orders'))

@section('page-title-right')
  <div class="title-right-btns">
    <div class="status-wrap" id="status-app">
      @foreach($next_statuses as $status)
        <button class="btn btn-primary ms-2" @click="edit('{{ $status['status'] }}')">{{ $status['name'] }}</button>
      @endforeach
      <a class="btn btn-success ms-2" href="{{ panel_route('orders.printing', $order) }}" target="_blank">{{
            panel_trans('order.print') }}</a>
      @hookinsert('panel.orders.info.print.after')
      <el-dialog v-model="statusDialog" title="{{ __('panel/order.status') }}" width="500">
        <div class="mb-2">{{ __('panel/order.comment') }}</div>
        <textarea v-model="comment" class="form-control" placeholder="{{ __('panel/order.comment') }}"
                  rows="3"></textarea>
        <template #footer>
          <div class="dialog-footer">
            <el-button @click="statusDialog = false">{{ __('panel/common.close') }}</el-button>
            <el-button type="primary" @click="submit">{{ __('panel/common.btn_save') }}</el-button>
          </div>
        </template>
      </el-dialog>
    </div>
  </div>
@endsection

@section('content')
  <div class="card mb-4">
    <div class="card-header">
      <h5 class="card-title mb-0">{{ __('panel/order.order_info') }}</h5>
    </div>
    <div class="card-body">
      <table class="table align-middle">
        <thead>
        <tr>
          <th>{{ __('panel/order.number') }}</th>
          <th>{{ __('panel/order.created_at') }}</th>
          <th>{{ __('panel/order.total') }}</th>
          <th>{{ __('panel/order.billing_method_name') }}</th>
          <th>{{ __('panel/order.shipping_method_name') }}</th>
          <th>{{ __('panel/common.status') }}</th>
        </tr>
        </thead>
        <tbody>
        <tr>
          <td>{{ $order->number }}</td>
          <td>{{ $order->created_at }}</td>
          <td>{{ $order->total_format }}</td>
          <td>{{ $order->billing_method_name }}</td>
          <td>{{ $order->shipping_method_name }}</td>
          <td><span class="badge bg-{{$order->status_color}}">{{ $order->status_format }}</span></td>
        </tr>
        </tbody>
      </table>
    </div>
  </div>

  @hookinsert('panel.orders.info.order_info.after')

  <div class="card mb-4">
    <div class="card-header">
      <h5 class="card-title mb-0">{{ __('panel/order.order_items') }}</h5>
    </div>
    <div class="card-body">
      @hookupdate('panel.orders.info.order_items')
      <table class="table products-table align-middle">
        <thead>
        <tr>
          <th>{{ __('panel/common.id') }}</th>
          <th>{{ __('panel/order.product') }}</th>
          <th>{{ __('panel/order.sku_code') }}</th>
          <th>{{ __('panel/order.quantity') }}</th>
          <th>{{ __('panel/order.unit_price') }}</th>
          <th>{{ __('panel/order.subtotal') }}</th>
        </tr>
        </thead>
        <tbody>
        @php
          // Create a map of item IDs that have custom data for quick lookup
          $itemsWithCustomDataMap = [];
          if (isset($itemsWithCustomData)) {
            foreach ($itemsWithCustomData as $itemData) {
              $itemsWithCustomDataMap[$itemData['item']->id] = true;
            }
          }
        @endphp

        @foreach ($order->items as $item)
          <tr>
            <td>{{ $item->id }}</td>
            <td>
              <div class="product-item d-flex align-items-center">
                <div class="product-image wh-40 border"><img src="{{ $item->image }}" class="img-fluid">
                </div>
                <div class="product-info ms-2">
                  <div class="name">
                    {{ $item->name }}
                    @if(isset($itemsWithCustomDataMap[$item->id]))
                      <a href="#custom-info-section" class="badge bg-info ms-1" title="Has custom information - Click to view">
                        <i class="bi bi-person-vcard"></i>
                      </a>
                    @endif
                  </div>
                  @if($item->productSku->variantLabel ?? '')
                    <span class="small fst-italic">{{ $item->productSku->variantLabel }}</span>
                  @endif
                </div>
              </div>
            </td>
            <td>{{ $item->product_sku }}</td>
            <td>{{ $item->quantity }}</td>
            <td>{{ $item->price_format }}</td>
            <td>{{ $item->subtotal_format }}</td>
          </tr>
        @endforeach
        @foreach ($order->fees as $total)
          <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td><strong>{{ $total->title }}</strong></td>
            <td>{{ $total->value_format }}</td>
          </tr>
        @endforeach
        <tr>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td><strong>{{ __('panel/order.total') }}</strong></td>
          <td>{{ $order->total_format }}</td>
        </tr>
        </tbody>
      </table>
      @endhookupdate
    </div>
  </div>

  @hookinsert('panel.orders.info.order_items.after')

  <div class="card mb-4">
    <div class="card-header">
      <h5 class="card-title mb-0">{{ __('panel/order.address') }}</h5>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-12 col-md-6">
          <div class="address-card">
            <div class="address-card-header mb-3">
              <h5 class="address-card-title">{{ __('panel/order.shipping_address') }}</h5>
            </div>
            <div class="address-card-body">
              <p>{{ __('common/address.name') }}: {{ $order->shipping_customer_name }}</p>
              <p>{{ __('common/address.phone') }}: {{ $order->shipping_telephone }}</p>
              <p>{{ __('common/address.zipcode') }}: {{ $order->shipping_zipcode }}</p>
              <p>{{ __('common/address.address_1') }}: {{ $order->shipping_address_1 }}</p>
              @if($order->shipping_address_2)
                <p>{{ __('common/address.address_2') }}: {{ $order->shipping_address_2 }}</p>
              @endif
              <p>{{ __('common/address.region') }}: {{ $order->shipping_city }}, {{ $order->shipping_state }}
                , {{ $order->shipping_country }}</p>
            </div>
          </div>
        </div>
        <div class="col-12 col-md-6">
          <div class="address-card">
            <div class="address-card-header mb-3">
              <h5 class="address-card-title">{{ __('panel/order.billing_address') }}</h5>
            </div>
            <div class="address-card-body">
              <p>{{ __('common/address.name') }}: {{ $order->billing_customer_name }}</p>
              <p>{{ __('common/address.phone') }}: {{ $order->billing_telephone }}</p>
              <p>{{ __('common/address.zipcode') }}: {{ $order->billing_zipcode }}</p>
              <p>{{ __('common/address.address_1') }}: {{ $order->billing_address_1 }}</p>
              @if($order->billing_address_2)
                <p>{{ __('common/address.address_2') }}: {{ $order->billing_address_2 }} </p>
              @endif
              <p>{{ __('common/address.region') }}: {{ $order->billing_city }}, {{ $order->billing_state }}
                , {{ $order->billing_country }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  @hookinsert('panel.orders.info.addresses.after')

  <div class="mt-4">
    <div class="card mb-4">
      <div class="card-header">
        <h5 class="card-title mb-0">{{ __('front/checkout.order_comment') }}</h5>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-12 col-md-6 mb-4">
            <h6 class="fs-5">{{ __('panel/order.customer_remarks') }}</h6>
            <div class="mb-0 p-3 bg-light rounded">{!! nl2br(e($order->comment)) !!}</div>
          </div>
          <div class="col-12 col-md-6 mb-3">
            <h6 class="fs-5">{{ __('panel/order.administrator_remarks') }}</h6>
            <div class="mb-0 p-3 bg-light rounded">{!! nl2br(e($order->admin_note)) !!}</div>
            <button class="btn btn-sm btn-primary mt-2" data-bs-toggle="modal">
              {{ __('panel/common.edit') }}
            </button>

            <div class="modal fade" id="admin_note" tabindex="-1" aria-labelledby="admin_noteLabel" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border border-secondary rounded">
                  <div class="modal-header">
                    <h4 class="modal-title" id="admin_noteLabel">{{ __('panel/order.administrator_remarks') }}</h4>
                  </div>
                  <div class="modal-body">
                    <textarea class="form-control admin-comment-input" rows="5"
                              data-order-id="{{ $order->id }}">{{ $order->admin_note }}</textarea>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-default"
                            data-bs-dismiss="modal">{{ __('panel/order.close') }}</button>
                    <button type="button" class="btn btn-primary"
                            onclick="submitComment()">{{ __('panel/order.submit') }}</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  @hookinsert('panel.orders.info.comment.after')

  <div class="card mb-4" id="custom-info-section">
    <div class="card-header bg-light">
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <h5 class="card-title mb-0"><i class="bi bi-person-vcard me-2"></i>{{ __('panel/order.custom_information') ?? 'Custom Information' }}</h5>
          @if(auth()->user() && auth()->user()->hasRole('admin'))
            <div class="small text-muted mt-1">
              <i class="bi bi-info-circle me-1"></i> Custom information is now automatically saved from product forms. No need to add "=== CUSTOM INFORMATION ===" to comments.
            </div>
          @endif
        </div>
        @if(isset($itemsWithCustomData) && count($itemsWithCustomData) > 0)
          <span class="badge bg-primary rounded-pill">{{ count($itemsWithCustomData) }} {{ Str::plural('item', count($itemsWithCustomData)) }}</span>
        @endif
      </div>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-12">
          <table class="table table-bordered table-hover">
            <thead class="table-light">
              <tr>
                <th width="30%">Field</th>
                <th width="70%">Value</th>
              </tr>
            </thead>
            <tbody>
              @php
                // Create an array to store custom data for each order item
                $itemsWithCustomData = [];
                $hasCustomData = false;

                // Check if the order has items with custom data
                foreach ($order->items as $item) {
                  $itemCustomData = null;

                  // Check for custom_data field in the order item
                  if (isset($item->custom_data) && !empty($item->custom_data)) {
                    if (is_array($item->custom_data)) {
                      $itemCustomData = $item->custom_data;
                    } else {
                      try {
                        $decodedData = json_decode($item->custom_data, true);
                        if (is_array($decodedData)) {
                          $itemCustomData = $decodedData;
                        }
                      } catch (\Exception $e) {
                        // Invalid JSON, ignore
                      }
                    }
                  }

                  // Try to extract custom data from the item name as fallback
                  if (empty($itemCustomData) && strpos($item->name, 'customerName:') !== false) {
                    $itemCustomData = [];
                    $nameParts = explode('\n', $item->name);
                    foreach ($nameParts as $part) {
                      if (strpos($part, ':') !== false) {
                        list($key, $value) = explode(':', $part, 2);
                        $itemCustomData[trim($key)] = trim($value);
                      }
                    }
                  }

                  // Check if the item has a reference field
                  if (empty($itemCustomData) && isset($item->reference) && is_array($item->reference)) {
                    $itemCustomData = $item->reference;
                  }

                  // If we found custom data for this item, add it to our array
                  if (!empty($itemCustomData)) {
                    $hasCustomData = true;
                    $itemsWithCustomData[] = [
                      'item' => $item,
                      'customData' => $itemCustomData
                    ];
                  }
                }

                // If no items have custom data, check the order level custom data
                if (empty($itemsWithCustomData)) {
                  $orderCustomData = null;

                  // Check for custom_data field in the order
                  if (isset($order->custom_data) && !empty($order->custom_data)) {
                    if (is_array($order->custom_data)) {
                      $orderCustomData = $order->custom_data;
                    } else {
                      try {
                        $decodedData = json_decode($order->custom_data, true);
                        if (is_array($decodedData)) {
                          if (isset($decodedData['panel_order_custom_information'])) {
                            $orderCustomData = $decodedData['panel_order_custom_information'];
                          } else {
                            $orderCustomData = $decodedData;
                          }
                        }
                      } catch (\Exception $e) {
                        // Invalid JSON, ignore
                      }
                    }
                  }

                  // Parse custom information from order comment as fallback (legacy support)
                  if (empty($orderCustomData) && $order->comment && is_string($order->comment)) {
                    // Try to parse JSON from comment
                    try {
                      $commentData = json_decode($order->comment, true);
                      if (is_array($commentData)) {
                        $orderCustomData = $commentData;
                      }
                    } catch (\Exception $e) {
                      // Not valid JSON
                    }

                    // Note: The '=== CUSTOM INFORMATION ===' parsing is no longer needed for new orders
                    // as custom data is now properly stored in the database. This code is kept for
                    // backward compatibility with older orders only.

                    // Legacy support for older orders with custom information in comments
                    if (empty($orderCustomData) && strpos($order->comment, '=== CUSTOM INFORMATION ===') !== false) {
                      // Log that we're using the legacy parser for debugging purposes
                      if (config('app.debug')) {
                        \Log::info('Using legacy custom information parser for order #' . $order->id);
                      }

                      $orderCustomData = [];
                      $parts = explode('=== CUSTOM INFORMATION ===', $order->comment);
                      if (isset($parts[1])) {
                        $customInfoText = trim($parts[1]);
                        $lines = preg_split('/\r\n|\r|\n/', $customInfoText);

                        // Simple mapping of known fields
                        $fieldMappings = [
                          '姓名 Name' => 'customerName',
                          '性别 Gender' => 'customerGender',
                          '阳历生日' => 'customerDOB',
                          'Date of Birth (Solar)' => 'customerDOB',
                          '农历生日' => 'customerLunarDOB',
                          'Date of Birth (Lunar)' => 'customerLunarDOB',
                          '生肖' => 'customerZodiac',
                          'Chinese Zodiac' => 'customerZodiac',
                          '出生时间' => 'customerTimeOfBirth',
                          'Time of Birth' => 'customerTimeOfBirth',
                          '联络号码' => 'customerWhatsApp',
                          'WhatsApp' => 'customerWhatsApp'
                        ];

                        foreach ($lines as $line) {
                          $line = trim($line);
                          if (empty($line) || strpos($line, ':') === false) continue;

                          list($field, $value) = explode(':', $line, 2);
                          $field = trim($field);
                          $value = trim($value);

                          // Check against our mapping
                          foreach ($fieldMappings as $searchText => $mappedField) {
                            if (strpos($field, $searchText) !== false) {
                              $orderCustomData[$mappedField] = $value;
                              break;
                            }
                          }
                        }
                      }
                    }
                  }

                  // If we found order-level custom data, add it to our array
                  if (!empty($orderCustomData)) {
                    $hasCustomData = true;
                    // Use the first order item as a placeholder
                    $firstItem = $order->items->first();
                    $itemsWithCustomData[] = [
                      'item' => $firstItem,
                      'customData' => $orderCustomData,
                      'isOrderLevel' => true
                    ];
                  }
                }
              @endphp

              @if(count($itemsWithCustomData) > 0)
                @foreach($itemsWithCustomData as $index => $itemData)
                  @php
                    $item = $itemData['item'];
                    $customData = $itemData['customData'];
                    $isOrderLevel = $itemData['isOrderLevel'] ?? false;
                  @endphp

                  <!-- Product header with item information -->
                  <tr class="table-{{ $index % 2 == 0 ? 'primary' : 'info' }} text-dark">
                    <td colspan="2" class="py-3">
                      <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 me-3">
                          <span class="badge bg-{{ $index % 2 == 0 ? 'primary' : 'info' }} rounded-circle p-2">
                            <i class="bi bi-{{ $isOrderLevel ? 'file-earmark-text' : 'box-seam' }} fs-5"></i>
                          </span>
                        </div>
                        <div class="flex-grow-1">
                          <h5 class="mb-0">{{ $item->name }}</h5>
                          <div class="small">
                            <span class="me-3"><i class="bi bi-upc me-1"></i>SKU: {{ $item->product_sku }}</span>
                            @if($item->variant_label)
                              <span class="me-3"><i class="bi bi-tags me-1"></i>Variant: {{ $item->variant_label }}</span>
                            @endif
                            <span><i class="bi bi-123 me-1"></i>Quantity: {{ $item->quantity }}</span>
                          </div>
                        </div>
                      </div>
                    </td>
                  </tr>

                  <!-- Custom information fields -->
                  @php
                    $hasAnyCustomField = !empty($customData['customerName']) ||
                                        !empty($customData['customerGender']) ||
                                        !empty($customData['customerDOB']) ||
                                        !empty($customData['customerLunarDOB']) ||
                                        !empty($customData['customerZodiac']) ||
                                        !empty($customData['customerTimeOfBirth']) ||
                                        !empty($customData['customerWhatsApp']);
                  @endphp

                  @if(!$hasAnyCustomField)
                    <tr>
                      <td colspan="2" class="text-center text-muted py-3">
                        <i class="bi bi-info-circle me-2"></i>This product has custom data enabled but no specific fields were filled in.
                      </td>
                    </tr>
                  @endif

                  @if (!empty($customData['customerName']))
                    <tr>
                      <td class="fw-medium"><i class="bi bi-person me-2"></i>姓名 Name</td>
                      <td>{{ $customData['customerName'] }}</td>
                    </tr>
                  @endif

                  @if (!empty($customData['customerGender']))
                    <tr>
                      <td class="fw-medium"><i class="bi bi-gender-ambiguous me-2"></i>性别 Gender</td>
                      <td>
                        @if(strtolower($customData['customerGender']) == 'male')
                          <span class="badge bg-primary">Male 男</span>
                        @elseif(strtolower($customData['customerGender']) == 'female')
                          <span class="badge bg-danger">Female 女</span>
                        @else
                          {{ $customData['customerGender'] }}
                        @endif
                      </td>
                    </tr>
                  @endif

                  @if (!empty($customData['customerDOB']))
                    <tr>
                      <td class="fw-medium"><i class="bi bi-calendar-date me-2"></i>阳历生日 Date of Birth (Solar)</td>
                      <td>{{ $customData['customerDOB'] }}</td>
                    </tr>
                  @endif

                  @if (!empty($customData['customerLunarDOB']))
                    <tr>
                      <td class="fw-medium"><i class="bi bi-calendar-heart me-2"></i>农历生日 Date of Birth (Lunar)</td>
                      <td>{{ $customData['customerLunarDOB'] }}</td>
                    </tr>
                  @endif

                  @if (!empty($customData['customerZodiac']))
                    <tr>
                      <td class="fw-medium"><i class="bi bi-stars me-2"></i>生肖 Chinese Zodiac</td>
                      <td>
                        <span class="badge bg-secondary">{{ $customData['customerZodiac'] }}</span>
                      </td>
                    </tr>
                  @endif

                  @if (!empty($customData['customerTimeOfBirth']))
                    <tr>
                      <td class="fw-medium"><i class="bi bi-clock-history me-2"></i>出生时间 Time of Birth</td>
                      <td>{{ $customData['customerTimeOfBirth'] }}</td>
                    </tr>
                  @endif

                  @if (!empty($customData['customerWhatsApp']))
                    <tr>
                      <td class="fw-medium"><i class="bi bi-whatsapp me-2"></i>联络号码 WhatsApp</td>
                      <td>
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $customData['customerWhatsApp']) }}" target="_blank" class="text-decoration-none">
                          {{ $customData['customerWhatsApp'] }} <i class="bi bi-box-arrow-up-right ms-1 small"></i>
                        </a>
                      </td>
                    </tr>
                  @endif

                  @if(auth()->user() && auth()->user()->hasRole('admin'))
                    <!-- Debug section for this specific item (only visible to admins) -->
                    <tr class="table-light">
                      <td colspan="2">
                        <div class="accordion accordion-flush" id="debugAccordion{{ $index }}">
                          <div class="accordion-item">
                            <h2 class="accordion-header">
                              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseDebug{{ $index }}" aria-expanded="false">
                                <i class="bi bi-bug me-2"></i>Debug Information (Admin Only)
                              </button>
                            </h2>
                            <div id="collapseDebug{{ $index }}" class="accordion-collapse collapse" data-bs-parent="#debugAccordion{{ $index }}">
                              <div class="accordion-body bg-light">
                                <h6>Item Data:</h6>
                                <pre class="mb-3" style="max-height: 150px; overflow: auto;">{{ json_encode($item, JSON_PRETTY_PRINT) }}</pre>
                                <h6>Custom Data:</h6>
                                <pre class="mb-0" style="max-height: 150px; overflow: auto;">{{ json_encode($customData, JSON_PRETTY_PRINT) }}</pre>
                              </div>
                            </div>
                          </div>
                        </div>
                      </td>
                    </tr>
                  @endif

                  <!-- Spacer row between products -->
                  @if(!$loop->last)
                    <tr>
                      <td colspan="2" class="border-0 py-2"></td>
                    </tr>
                  @endif
                @endforeach
              @else
                <tr>
                  <td colspan="2" class="text-center">No custom information available</td>
                </tr>

                @if(auth()->user() && auth()->user()->hasRole('admin'))
                <!-- Debug section to show all available data (only visible to admins) -->
                <tr>
                  <td colspan="2">
                    <div class="mt-3">
                      <h6 class="text-muted"><i class="bi bi-bug me-2"></i>Technical Information (Admin Only)</h6>
                      <div class="accordion accordion-flush" id="debugAccordion">
                        <div class="accordion-item">
                          <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                              <i class="bi bi-box-seam me-2"></i>Order Items Data
                            </button>
                          </h2>
                          <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#debugAccordion">
                            <div class="accordion-body bg-light">
                              <pre class="mb-0" style="max-height: 300px; overflow: auto;">{{ json_encode($order->items, JSON_PRETTY_PRINT) }}</pre>
                            </div>
                          </div>
                        </div>
                        <div class="accordion-item">
                          <h2 class="accordion-header" id="headingTwo">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                              <i class="bi bi-chat-square-text me-2"></i>Order Comment
                            </button>
                          </h2>
                          <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#debugAccordion">
                            <div class="accordion-body bg-light">
                              <pre class="mb-0" style="max-height: 300px; overflow: auto;">{{ $order->comment }}</pre>
                            </div>
                          </div>
                        </div>
                        <div class="accordion-item">
                          <h2 class="accordion-header" id="headingThree">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                              <i class="bi bi-cash-coin me-2"></i>Order Fees Data
                            </button>
                          </h2>
                          <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#debugAccordion">
                            <div class="accordion-body bg-light">
                              <pre class="mb-0" style="max-height: 300px; overflow: auto;">{{ json_encode($order->fees, JSON_PRETTY_PRINT) }}</pre>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </td>
                </tr>
                @endif
              @endif
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="card-title mb-0">{{ __('panel/order.shipping_information') }}</h5>
      <button class="btn btn-sm btn-primary mt-2" id="addRow">{{ __('panel/order.add') }}</button>
    </div>
    <div class="card-body">
      <table class="table table-response align-middle table-bordered" id="logisticsTable">
        <thead>
        <tr>
          <td>ID</td>
          <th>{{ __('panel/order.express_company') }}</th>
          <th>{{ __('panel/order.express_number') }}</th>
          <th>{{ __('panel/order.create_time') }}</th>
          <th>{{ __('panel/order.operation') }}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($order->shipments as $shipment)
          <tr>
            <td data-title="id">{{ $shipment->id }}</td>
            <td data-title="express_company">{{ $shipment->express_company }}</td>
            <td data-title="express_number">{{ $shipment->express_number }}</td>
            <td data-title="created_at">{{ $shipment->created_at }}</td>
            <td>
              <button class="btn btn-sm btn-primary deleteRow"
                      onclick="deleteShipment('{{ $shipment->id }}')">{{ __('panel/order.delete') }}</button>
              <button class="btn btn-sm btn-primary viewRow"
                      onclick="viewShipmentDetails('{{ $shipment->id }}')">{{ __('panel/order.view') }}</button>
            </td>
          </tr>
        @endforeach
        </tbody>
      </table>
    </div>
  </div>

  @hookinsert('panel.orders.info.shipping.after')

  <div class="card mb-4">
    <div class="card-header">
      <h5 class="card-title mb-0">{{ __('panel/order.history') }}</h5>
    </div>
    <div class="card-body">
      <table class="table table-response align-middle">
        <thead>
        <tr>
          <th>{{ __('panel/order.status') }}</th>
          <th>{{ __('panel/order.comment') }}</th>
          <th>{{ __('panel/order.date_time') }}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($order->histories->sortByDesc('id') as $history)
          <tr>
            <td>{{ $history->status }}</td>
            <td>{{ $history->comment }}</td>
            <td>{{ $history->created_at }}</td>
          </tr>
        @endforeach
        </tbody>
      </table>
    </div>
  </div>

  @hookinsert('panel.orders.info.history.after')

  <div class="modal fade" id="newShipmentModal" tabindex="-1" aria-labelledby="newShipmentModalLabel"
       aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="newShipmentModalLabel">{{ __('panel/order.shipment_information') }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <table class="table">
            <tbody>
            <tr>
              <th class="col-3">{{ __('panel/order.time') }}</th>
              <th class="col-9">{{ __('panel/order.logistics_information') }}</th>
            </tr>
            </tbody>
            <tbody>
            </tbody>
          </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" data-bs-dismiss="modal">{{ __('panel/order.confirm') }}</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="editModal" tabindex="-1" aria-bs-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editModalLabel">{{ __('panel/order.edit_logistics_information') }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form>
            <div class="mb-3">
              <label for="logisticsCompany" class="form-label">{{ __('panel/order.express_company') }}</label>
              <select class="form-control" id="logisticsCompany">
                @php
                  $logistics = system_setting('logistics', []);
                  $logisticsArray = is_array($logistics) ? $logistics : [];
                @endphp
                @foreach($logisticsArray as $expressCompany)
                  <option value="{{ $expressCompany['code'] ?? '' }}">{{ $expressCompany['company'] ?? '' }}</option>
                @endforeach
              </select>
            </div>
            <div class="mb-3">
              <label for="trackingNumber" class="form-label">{{ __('panel/order.express_number') }}</label>
              <input type="text" class="form-control" id="trackingNumber">
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary"
                  data-bs-dismiss="modal">{{ __('panel/order.close') }}</button>
          <button type="button" class="btn btn-primary"
                  onclick="submitEdit()">{{ __('panel/order.save_changes') }}</button>
        </div>
      </div>
    </div>
  </div>

@endsection

@push('footer')
  <script>
    var admin_note = new bootstrap.Modal(document.getElementById('admin_note'));
    document.querySelector('[data-bs-toggle="modal"]').addEventListener('click', function () {
      admin_note.show();
    });

    $(document).ready(function () {

      $('.admin-comment-input').on('keydown', function (event) {
        if (event.keyCode === 13) {
          event.preventDefault();
          var comment = $(this).val();
          var orderId = $(this).data('order-id');
          var apiUrl = `${urls.api_base}/orders/${orderId}/notes`;
          axios.post(apiUrl, {
            admin_note: comment,
          })
            .then(function (res) {
              inno.msg(res.message);
              $('.admin-comment-input').val(res.data.admin_note);
              window.location.reload()
            })
        }
      });

      $('#addRow').click(function () {
        $('#editModal').modal('show');
      });

      $(document).on('click', '.deleteRow', function () {
        $(this).closest('tr').remove();
      });

      window.viewShipmentDetails = function (shipmentId) {
        axios.get(`${urls.api_base}/shipments/${shipmentId}/traces`)
          .then(function (response) {
            if (response.data && response.data.traces) {
              const tbody = $('#newShipmentModal .modal-body table tbody').last();
              tbody.empty();
              response.data.traces.forEach(trace => {
                const row = `<tr>
                            <td>${trace.time}</td>
                            <td>${trace.station}</td>
                         </tr>`;
                tbody.append(row);
              });
              var newShipmentModal = new bootstrap.Modal(document.getElementById('newShipmentModal'));
              newShipmentModal.show();
            }
          })
          .catch(function (error) {
            inno.msg('{{ __('panel/order.no_logistics_information') }}');
          });
      }
    });

    function submitComment() {
      let elment = $('.admin-comment-input');
      let comment = elment.val();
      let orderId = elment.data('order-id');
      let apiUrl = `${urls.api_base}/orders/${orderId}/notes`;
      axios.post(apiUrl, {
        admin_note: comment,
      })
        .then(function (res) {
          inno.msg(res.message);
          var admin_note = bootstrap.Modal.getInstance(document.getElementById('admin_note'));
          if (admin_note) {
            admin_note.hide();
          }
          $('.admin-comment-input').val(res.data.admin_note);
          window.location.reload();
        })
    }

    function submitEdit() {
      const logisticsCompany = $('#logisticsCompany').val();
      const trackingNumber = $('#trackingNumber').val();
      const selectedCompanyName = $('#logisticsCompany option:selected').text();
      const orderId = {{ $order->id }};
      axios.post(`${urls.api_base}/orders/${orderId}/shipments`, {
        express_code: logisticsCompany,
        express_company: selectedCompanyName,
        express_number: trackingNumber,
      }).then(function (response) {
        inno.msg('{{ __('panel/order.add_successfully') }}');
        $('#editModal').modal('hide');
        window.location.reload();
      }).catch(function (res) {
        inno.msg('{{ __('panel/order.add_failed!') }}');
      });
    }

    function deleteShipment(shipmentId) {
      const apiUrl = `${urls.api_base}/shipments/${shipmentId}`;
      axios.delete(apiUrl)
        .then(function (response) {
          inno.msg('{{ __('panel/order.delete_successfully') }}');
          window.location.reload();
        })
    }

    const {createApp, ref} = Vue
    const api = @json(panel_route('orders.change_status', $order));
    const statusApp = createApp({
      setup() {
        const statusDialog = ref(false)
        const comment = ref('')
        let status = '';

        const edit = (code) => {
          statusDialog.value = true
          status = code
        }

        const submit = () => {
          axios.put(api, {status: status, comment: comment.value}).then(() => {
            statusDialog.value = false
            window.location.reload()
          })
        }

        return {
          edit,
          submit,
          comment,
          statusDialog,
        }
      }
    })
    statusApp.use(ElementPlus);
    statusApp.mount('#status-app');
  </script>
@endpush
