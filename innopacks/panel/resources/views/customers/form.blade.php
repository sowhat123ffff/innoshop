@extends('panel::layouts.app')

@section('title', __('panel/menu.customers'))

@section('page-title-right')
<div class="title-right-btns">
  <a href="{{ panel_route('customers.login', [$customer->id]) }}" target="_blank" class="btn btn-primary">
    {{ __('panel/customer.login_frontend')}}
  </a>
  <button type="button" class="btn btn-outline-secondary ms-2 btn-back" onclick="window.history.back()">{{
    __('panel/common.btn_back') }}</button>
</div>
@endsection

@section('content')
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <ul class="nav nav-tabs mb-3" id="customerTab" role="tablist">
            <li class="nav-item" role="presentation">
              <button class="nav-link active" id="info-tab" data-bs-toggle="tab" data-bs-target="#info-tab-pane" type="button" role="tab">{{ __('panel/customer.basic_info') }}</button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="address-tab" data-bs-toggle="tab" data-bs-target="#address-tab-pane" type="button" role="tab">{{ __('panel/customer.address_manage') }}</button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="member-data-tab" data-bs-toggle="tab" data-bs-target="#member-data-tab-pane" type="button" role="tab">{{ __('panel/customer.member_data') }}</button>
            </li>
            @hookinsert('panel.customer.edit.tab.nav.bottom')
          </ul>

          <div class="tab-content" id="customerTabContent">
            <div class="tab-pane fade show active" id="info-tab-pane" role="tabpanel" tabindex="0">
              <form class="needs-validation" novalidate id="app-form"
                    action="{{ $customer->id ? panel_route('customers.update', [$customer->id]) : panel_route('customers.store') }}"
                    method="POST">
                @csrf
                @method($customer->id ? 'PUT' : 'POST')

                <div class="row">
                  <div class="col-md-6">
                    <div class="mb-3">
                      <x-common-form-image title="{{ __('panel/customer.avatar') }}" name="avatar" value="{{ old('avatar', $customer->avatar) }}" required/>
                    </div>
                    <div class="mb-3">
                      <x-common-form-input title="{{ __('panel/customer.from') }}" name="from" value="{{ old('from', $customer->from) }}" placeholder="{{ __('panel/customer.from') }}"/>
                    </div>
                    <div class="mb-3">
                      <x-common-form-input title="{{ __('panel/customer.name') }}" name="name" value="{{ old('name', $customer->name) }}" required placeholder="{{ __('panel/customer.name') }}"/>
                    </div>
                    <div class="mb-3">
                      <x-common-form-input title="{{ __('panel/customer.password') }}" name="password" value="" placeholder="{{ __('panel/customer.password') }}"/>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="mb-3">
                      <x-common-form-input title="{{ __('panel/customer.email') }}" name="email" value="{{ old('email', $customer->email) }}" required placeholder="{{ __('panel/customer.email') }}"/>
                    </div>
                    <div class="mb-3 customersmt">
                      <x-common-form-select title="{{ __('panel/customer.group') }}" name="customer_group_id" :options="$groups" key="id" label="name" value="{{ old('customer_group_id', $customer->customer_group_id) }}"/>
                    </div>
                    @hookinsert('panel.customer.form.group.after')
                    <div class="mb-3">
                      <x-common-form-select title="{{ __('panel/customer.locale') }}" name="locale" :options="$locales" key="code" label="name" value="{{ old('locale', $customer->locale) }}"/>
                    </div>
                    <div class="mb-3">
                      <x-common-form-switch-radio title="{{ __('panel/common.whether_enable') }}" name="active" :value="old('active', $page->active ?? true)" placeholder="{{ __('panel/common.whether_enable') }}"/>
                    </div>
                  </div>
                </div>

                <div class="text-center mt-3">
                  <button type="submit" class="btn btn-primary">{{ __('提交') }}</button>
                </div>
              </form>
            </div>

            <div class="tab-pane fade" id="address-tab-pane" role="tabpanel" tabindex="0">
              <div class="d-flex justify-content-end mb-3">
                <button class="btn btn-sm add-address btn-outline-primary">{{ __('panel/common.add') }}</button>
              </div>
              <table class="table table-bordered">
                <thead>
                <tr>
                  <th>{{ __('panel/common.id') }}</th>
                  <th>{{ __('common/address.name') }}</th>
                  <th>{{ __('common/address.address') }}</th>
                  <th>{{ __('common/address.phone') }}</th>
                  <th>{{ __('panel/common.created_at') }}</th>
                  <th class="text-end"></th>
                </tr>
                </thead>
                <tbody>
                @foreach ($addresses as $address)
                  <tr data-id="{{ $address['id'] }}">
                    <td>{{ $address['id'] }}</td>
                    <td>{{ $address['name'] }}</td>
                    <td>{{ $address['address_1'] }}</td>
                    <td>{{ $address['phone'] }}</td>
                    <td>{{ $address['created_at'] }}</td>
                    <td class="text-end">
                      <button type="button" class="btn btn-sm edit-address btn-outline-primary">{{ __('panel/common.edit') }}</button>
                      <button type="button" class="btn btn-sm btn-outline-danger delete-address">{{ __('panel/common.delete') }}</button>
                    </td>
                  </tr>
                @endforeach
                </tbody>
              </table>
            </div>

            <div class="tab-pane fade" id="member-data-tab-pane" role="tabpanel" tabindex="0">
              <div class="d-flex justify-content-end mb-3">
                <button type="button" class="btn btn-sm btn-outline-primary add-member-data">{{ __('panel/common.add') }}</button>
              </div>
              <table class="table table-bordered">
                <thead>
                <tr>
                  <th>{{ __('panel/common.id') }}</th>
                  <th>{{ __('panel/customer.name') }}</th>
                  <th>Gender</th>
                  <th>Zodiac</th>
                  <th>Birth Date</th>
                  <th>WhatsApp</th>
                  <th class="text-end">{{ __('panel/common.actions') }}</th>
                </tr>
                </thead>
                <tbody id="member-data-table-body">
                @if(isset($customer->id))
                  @php
                    $memberData = \InnoShop\Common\Repositories\MemberDataRepo::getInstance()->builder(['customer_id' => $customer->id])->get();
                  @endphp
                  @foreach ($memberData as $member)
                    <tr data-id="{{ $member->id }}">
                      <td>{{ $member->id }}</td>
                      <td>{{ $member->member_data['name'] ?? '' }}</td>
                      <td>{{ $member->member_data['gender'] ?? '' }}</td>
                      <td>{{ $member->member_data['zodiac'] ?? '' }}</td>
                      <td>{{ $member->member_data['birth_date'] ?? '' }}</td>
                      <td>{{ $member->member_data['whatsapp'] ?? '' }}</td>
                      <td class="text-end">
                        <button type="button" class="btn btn-sm btn-outline-primary edit-member-data"
                                data-id="{{ $member->id }}"
                                data-name="{{ $member->member_data['name'] ?? '' }}"
                                data-gender="{{ $member->member_data['gender'] ?? '' }}"
                                data-zodiac="{{ $member->member_data['zodiac'] ?? '' }}"
                                data-birth-date="{{ $member->member_data['birth_date'] ?? '' }}"
                                data-lunar-date="{{ $member->member_data['lunar_date'] ?? '' }}"
                                data-birth-time="{{ $member->member_data['birth_time'] ?? '' }}"
                                data-whatsapp="{{ $member->member_data['whatsapp'] ?? '' }}">
                          {{ __('panel/common.edit') }}
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-danger delete-member-data" data-id="{{ $member->id }}">
                          {{ __('panel/common.delete') }}
                        </button>
                      </td>
                    </tr>
                  @endforeach
                @endif
                </tbody>
              </table>
            </div>

            @hookinsert('panel.customer.edit.tab.pane.bottom')

          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="addressModal" tabindex="-1" aria-labelledby="addressModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addressModalLabel">{{ __('common/address.address') }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          @include('panel::shared.address-form')
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="memberDataModal" tabindex="-1" aria-labelledby="memberDataModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="memberDataModalLabel">{{ __('panel/customer.member_data') }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="member-data-form" class="needs-validation" novalidate>
            @csrf
            <input type="hidden" id="member_data_id" name="id" value="">
            <input type="hidden" id="member_data_customer_id" name="customer_id" value="{{ $customer->id ?? '' }}">
            <input type="hidden" name="_method" id="member_data_method" value="POST">

            <div class="mb-3">
              <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="member_data_name" name="name" required>
              <div class="invalid-feedback">Please enter Name</div>
            </div>

            <div class="mb-3">
              <label class="form-label">Gender <span class="text-danger">*</span></label>
              <div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="member_data[gender]" id="genderMale" value="男 Male" required>
                  <label class="form-check-label" for="genderMale">男 Male</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="member_data[gender]" id="genderFemale" value="女 Female" required>
                  <label class="form-check-label" for="genderFemale">女 Female</label>
                </div>
                <div class="invalid-feedback">Please select Gender</div>
              </div>
            </div>

            <div class="mb-3">
              <label for="zodiac" class="form-label">Zodiac <span class="text-danger">*</span></label>
              <select class="form-select" id="member_data_zodiac" name="member_data[zodiac]" required>
                <option value="">Please select</option>
                <option value="鼠 Rat">鼠 Rat</option>
                <option value="牛 Ox">牛 Ox</option>
                <option value="虎 Tiger">虎 Tiger</option>
                <option value="兔 Rabbit">兔 Rabbit</option>
                <option value="龙 Dragon">龙 Dragon</option>
                <option value="蛇 Snake">蛇 Snake</option>
                <option value="马 Horse">马 Horse</option>
                <option value="羊 Goat">羊 Goat</option>
                <option value="猴 Monkey">猴 Monkey</option>
                <option value="鸡 Rooster">鸡 Rooster</option>
                <option value="狗 Dog">狗 Dog</option>
                <option value="猪 Pig">猪 Pig</option>
              </select>
              <div class="invalid-feedback">Please select Zodiac</div>
            </div>

            <div class="mb-3">
              <label for="birth_date" class="form-label">Birth Date <span class="text-danger">*</span></label>
              <div class="input-group">
                <input type="text" class="form-control" id="member_data_birth_date" name="member_data[birth_date]" placeholder="YYYY-MM-DD" pattern="\d{4}-\d{2}-\d{2}" required>
                <span class="input-group-text"><i class="bi bi-calendar"></i></span>
              </div>
              <div class="invalid-feedback">Please enter Birth Date</div>
              <div class="form-text small text-muted">
                Format: YYYY-MM-DD (e.g., 1990-01-15)
              </div>
            </div>

            <div class="mb-3">
              <label for="lunar_date" class="form-label">Lunar Date</label>
              <input type="text" class="form-control" id="member_data_lunar_date" name="member_data[lunar_date]" readonly>
            </div>

            <div class="mb-3">
              <label for="birth_time" class="form-label">Birth Time <span class="text-danger">*</span></label>
              <select class="form-select" id="member_data_birth_time" name="member_data[birth_time]" required>
                <option value="">Please select</option>
                <option value="吉时（如不懂出生时辰）">吉时（如不懂出生时辰）</option>
                <option value="子时 ( 11:00pm - 00:59am )">子时 ( 11:00pm - 00:59am )</option>
                <option value="丑时 ( 01:00am - 02:59am )">丑时 ( 01:00am - 02:59am )</option>
                <option value="寅时 ( 03:00am - 04:59am )">寅时 ( 03:00am - 04:59am )</option>
                <option value="卯时 ( 05:00am - 06:59am )">卯时 ( 05:00am - 06:59am )</option>
                <option value="辰时 ( 07:00am - 08:59am )">辰时 ( 07:00am - 08:59am )</option>
                <option value="巳时 ( 09:00am - 10:59am )">巳时 ( 09:00am - 10:59am )</option>
                <option value="午时 ( 11:00am - 12:59pm )">午时 ( 11:00am - 12:59pm )</option>
                <option value="未时 ( 01:00pm - 02:59pm )">未时 ( 01:00pm - 02:59pm )</option>
                <option value="申时 ( 03:00pm - 04:59pm )">申时 ( 03:00pm - 04:59pm )</option>
                <option value="酉时 ( 05:00pm - 06:59pm )">酉时 ( 05:00pm - 06:59pm )</option>
                <option value="戌时 ( 07:00pm - 08:59pm )">戌时 ( 07:00pm - 08:59pm )</option>
                <option value="亥时 ( 09:00pm - 10:59pm )">亥时 ( 09:00pm - 10:59pm )</option>
              </select>
              <div class="invalid-feedback">Please select Birth Time</div>
            </div>

            <div class="mb-3">
              <label for="whatsapp" class="form-label">WhatsApp <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="member_data_whatsapp" name="member_data[whatsapp]" required>
              <div class="invalid-feedback">Please enter WhatsApp number</div>
            </div>

            <div class="d-flex justify-content-end mt-4">
              <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-primary">Save</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('footer')
  <script>
    const addresses = @json($addresses);

    // Address functionality
    $('.add-address').on('click', function () {
      $('.address-form').find('input, select').each(function () {
        $(this).val('')
      })

      $('#addressModal').modal('show');
    });

    $('.edit-address').on('click', function () {
      const id = $(this).parents('tr').data('id');
      const address = addresses.find(address => address.id === id);

      getZones(address.country_code, function () {
        $('.address-form').find('input, select').each(function () {
          $(this).val(address[$(this).attr('name')])
        })
      })

      $('#addressModal').modal('show');
    });

    $('.delete-address').on('click', function () {
      const id = $(this).parents('tr').data('id');

      layer.confirm('{{ __('front/common.delete_confirm') }}', {
        btn: ['{{ __('front/common.confirm') }}', '{{ __('front/common.cancel') }}']
      }, function () {
        axios.delete(`{{ account_route('addresses.index') }}/${id}`).then(function (res) {
          if (res.success) {
            layer.msg(res.message, {icon: 1, time: 1000}, function () {
              window.location.reload()
            });
          }
        })
      });
    });

    function updateAddress(params) {
      const id = new URLSearchParams(params).get('id');
      const href = @json(account_route('addresses.index'));
      const method = id ? 'put' : 'post'
      const url = id ? `${href}/${id}` : href

      axios[method](url, params).then(function (res) {
        if (res.success) {
          $('#addressModal').modal('hide');
          inno.msg(res.message);
          window.location.reload();
        }
      })
    }

    // Member Data functionality
    // Initialize datepicker for birth date
    $('#member_data_birth_date').datepicker({
      format: 'yyyy-mm-dd',
      autoclose: true,
      todayHighlight: true,
      endDate: new Date() // Can't select future dates
    }).on('changeDate', function(e) {
      // Get the selected date and convert to lunar
      const dateStr = $('#member_data_birth_date').val();
      convertToLunar(dateStr);
    });

    // Handle manual input
    $('#member_data_birth_date').on('change', function() {
      let dateStr = $(this).val();

      // Try to format the date if it's not in the correct format
      if (dateStr) {
        // Remove any non-digit or non-hyphen characters
        dateStr = dateStr.replace(/[^0-9-]/g, '');

        // Try to format as YYYY-MM-DD if possible
        const parts = dateStr.split('-');
        if (parts.length === 3) {
          // Ensure year has 4 digits
          let year = parts[0].padStart(4, '0');
          // Ensure month and day have 2 digits
          let month = parts[1].padStart(2, '0');
          let day = parts[2].padStart(2, '0');

          // Update the field with formatted date
          dateStr = `${year}-${month}-${day}`;
          $(this).val(dateStr);
        }
      }

      const isValid = convertToLunar(dateStr);

      if (!isValid) {
        // If conversion failed, show error
        $(this).addClass('is-invalid');
      } else {
        $(this).removeClass('is-invalid');
      }
    });

    // Make calendar icon clickable
    $('#member_data_birth_date').next('.input-group-text').on('click', function() {
      $('#member_data_birth_date').datepicker('show');
    });

    // Function to convert solar date to lunar date using lunar-javascript library
    function convertToLunar(dateStr) {
      try {
        // Check if the date string matches YYYY-MM-DD format
        const dateRegex = /^(\d{4})-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01])$/;
        const match = dateStr.match(dateRegex);

        if (!match) {
          console.log('Date format does not match YYYY-MM-DD pattern:', dateStr);
          return false;
        }

        const year = parseInt(match[1], 10);
        const month = parseInt(match[2], 10);
        const day = parseInt(match[3], 10);

        // Additional validation for valid date
        const date = new Date(year, month - 1, day);
        if (date.getFullYear() !== year || date.getMonth() !== month - 1 || date.getDate() !== day) {
          console.log('Invalid date values:', year, month, day);
          return false;
        }

        // Check if lunar-javascript library is loaded
        if (typeof Solar === 'undefined') {
          console.error('Lunar library not loaded');
          return false;
        }

        // Chinese numerals for years
        const yearChars = ['零', '一', '二', '三', '四', '五', '六', '七', '八', '九'];

        // Convert year to Chinese numerals (e.g., 2025 -> 二零二五)
        let yearStr = '';
        const yearDigits = year.toString();
        for (let i = 0; i < yearDigits.length; i++) {
          yearStr += yearChars[parseInt(yearDigits[i])];
        }

        // Use lunar-javascript library for conversion
        // Create a solar date object
        const solar = Solar.fromYmd(year, month, day);

        // Convert to lunar date
        const lunar = solar.getLunar();

        // Get lunar month and day in Chinese
        const lunarMonthStr = lunar.getMonthInChinese();
        const lunarDayStr = lunar.getDayInChinese();

        // Display lunar date in Chinese format
        $('#member_data_lunar_date').val(`${yearStr}年${lunarMonthStr}月${lunarDayStr}`);

        // Auto-select zodiac based on lunar year
        const animalIndex = lunar.getYearZhiIndex();
        const zodiacValues = ['鼠 Rat', '牛 Ox', '虎 Tiger', '兔 Rabbit', '龙 Dragon', '蛇 Snake', '马 Horse', '羊 Goat', '猴 Monkey', '鸡 Rooster', '狗 Dog', '猪 Pig'];
        $('#member_data_zodiac').val(zodiacValues[animalIndex]);

        return true;
      } catch (error) {
        console.error('Error converting date:', error);
        return false;
      }
    }

    // Add Member Data button click handler
    $('.add-member-data').on('click', function() {
      // Reset form
      $('#member-data-form')[0].reset();
      $('#member_data_id').val('');
      $('#member_data_method').val('POST'); // Set method to POST for new records

      // Set customer ID
      $('#member_data_customer_id').val('{{ $customer->id ?? '' }}');

      // Set modal title
      $('#memberDataModalLabel').text('Add Member Data');

      // Show modal
      $('#memberDataModal').modal('show');
    });

    // Convert date on page load if it exists
    $(document).ready(function() {
      // Initialize any existing date fields when the modal is shown
      $('#memberDataModal').on('shown.bs.modal', function() {
        const dateStr = $('#member_data_birth_date').val();
        if (dateStr) {
          convertToLunar(dateStr);
        }
      });
    });

    // Edit Member Data button click handler
    $(document).on('click', '.edit-member-data', function() {
      const id = $(this).data('id');
      const name = $(this).data('name');
      const gender = $(this).data('gender');
      const zodiac = $(this).data('zodiac');
      const birthDate = $(this).data('birth-date');
      const lunarDate = $(this).data('lunar-date');
      const birthTime = $(this).data('birth-time');
      const whatsapp = $(this).data('whatsapp');

      // Set form values
      $('#member_data_id').val(id);
      $('#member_data_name').val(name);
      $('#member_data_method').val('PUT'); // Set method to PUT for updates

      // Set gender radio button
      if (gender === '男 Male') {
        $('#genderMale').prop('checked', true);
      } else if (gender === '女 Female') {
        $('#genderFemale').prop('checked', true);
      }

      // Set other fields
      $('#member_data_zodiac').val(zodiac);
      $('#member_data_birth_date').val(birthDate);
      $('#member_data_lunar_date').val(lunarDate);
      $('#member_data_birth_time').val(birthTime);
      $('#member_data_whatsapp').val(whatsapp);

      // Set modal title
      $('#memberDataModalLabel').text('Edit Member Data');

      // Show modal
      $('#memberDataModal').modal('show');
    });

    // Delete Member Data button click handler
    $(document).on('click', '.delete-member-data', function() {
      const id = $(this).data('id');

      layer.confirm('{{ __('front/common.delete_confirm') }}', {
        btn: ['{{ __('front/common.confirm') }}', '{{ __('front/common.cancel') }}']
      }, function() {
        // Send delete request using jQuery AJAX
        $.ajax({
          url: `{{ panel_route('member_data.destroy', ['id' => '__ID__']) }}`.replace('__ID__', id),
          type: 'POST',
          data: {
            '_method': 'DELETE',
            '_token': '{{ csrf_token() }}'
          },
          dataType: 'json',
          success: function(response) {
            console.log('Delete success response:', response);

            if (response.success) {
              layer.msg(response.message || 'Deleted successfully', {icon: 1, time: 1000});

              // Remove the row from the table
              $(`tr[data-id="${id}"]`).remove();
            } else {
              layer.msg(response.message || 'Failed to delete', {icon: 2, time: 2000});
            }
          },
          error: function(xhr, status, error) {
            console.error('Delete error response:', xhr.responseText);

            // Try to parse the error response
            let errorMessage = 'An error occurred while deleting';
            try {
              const errorResponse = JSON.parse(xhr.responseText);
              if (errorResponse.message) {
                errorMessage = errorResponse.message;
              } else if (errorResponse.error) {
                errorMessage = errorResponse.error;
              }
            } catch (e) {
              console.error('Error parsing delete error response:', e);
            }

            layer.msg(errorMessage, {icon: 2, time: 2000});
          }
        });
      });
    });

    // Form submission handler
    $('#member-data-form').on('submit', function(e) {
      e.preventDefault();

      // Form validation
      if (!this.checkValidity()) {
        e.stopPropagation();
        $(this).addClass('was-validated');
        return;
      }

      // Get form data
      const form = $(this);
      const id = $('#member_data_id').val();

      // Determine if this is an update or create operation
      const isUpdate = id !== '';
      const url = isUpdate
        ? `{{ panel_route('member_data.update', ['id' => '__ID__']) }}`.replace('__ID__', id)
        : '{{ panel_route('member_data.store') }}';

      // Set the method
      $('#member_data_method').val(isUpdate ? 'PUT' : 'POST');

      // Get form data
      const formData = new FormData(this);

      // Log form data for debugging
      console.log('Form data:', {
        id: id,
        isUpdate: isUpdate,
        url: url,
        method: isUpdate ? 'PUT' : 'POST',
        name: $('#member_data_name').val(),
        gender: $('input[name="member_data[gender]"]:checked').val(),
        zodiac: $('#member_data_zodiac').val(),
        birth_date: $('#member_data_birth_date').val(),
        lunar_date: $('#member_data_lunar_date').val(),
        birth_time: $('#member_data_birth_time').val(),
        whatsapp: $('#member_data_whatsapp').val()
      });

      // Send request using jQuery AJAX to better handle form data
      $.ajax({
        url: url,
        type: 'POST', // Always use POST, the _method field will handle the actual method
        data: form.serialize(),
        dataType: 'json',
        success: function(response) {
          console.log('Success response:', response);

          if (response.success) {
            // Show success message
            layer.msg(response.message || 'Saved successfully', {icon: 1, time: 1000});

            // Close modal
            $('#memberDataModal').modal('hide');

            // Reload page to show updated data
            window.location.reload();
          } else {
            // Show error message
            layer.msg(response.message || 'Failed to save', {icon: 2, time: 2000});
          }
        },
        error: function(xhr, status, error) {
          console.error('Error response:', xhr.responseText);

          // Try to parse the error response
          let errorMessage = 'An error occurred while saving';
          try {
            const errorResponse = JSON.parse(xhr.responseText);
            if (errorResponse.message) {
              errorMessage = errorResponse.message;
            } else if (errorResponse.error) {
              errorMessage = errorResponse.error;
            }
          } catch (e) {
            console.error('Error parsing error response:', e);
          }

          layer.msg(errorMessage, {icon: 2, time: 2000});
        }
      });
    });
  </script>
@endpush

@push('header')
  <!-- Bootstrap Datepicker -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>

  <!-- Lunar Calendar Conversion Library -->
  <script src="https://cdn.jsdelivr.net/npm/lunar-javascript/lunar.js"></script>

  <style>
    .input-group-text {
      cursor: pointer;
    }
    .datepicker-dropdown {
      z-index: 1060 !important;
    }
    #member_data_lunar_date {
      background-color: #f8f9fa;
    }
  </style>
@endpush