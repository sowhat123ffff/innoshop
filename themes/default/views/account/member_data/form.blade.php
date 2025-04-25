@extends('layouts.app')
@section('body-class', 'page-account')

@section('title', isset($member) ? 'Edit Member Data' : 'Add Member Data')

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
    #lunar_date {
      background-color: #f8f9fa;
    }
  </style>
@endpush

@section('content')
  <x-front-breadcrumb type="route" value="account.member_data.index" title="Member Data"/>

  <div class="container">
    <div class="row">
      <div class="col-12 col-lg-3">
        @include('shared.account-sidebar')
      </div>
      <div class="col-12 col-lg-9">
        <div class="account-card-box">
          <div class="account-card-title">
            <span class="fw-bold">{{ isset($member) ? 'Edit Member Data' : 'Add Member Data' }}</span>
          </div>
          <div class="account-card-body">
            <form class="needs-validation" action="{{ isset($member) ? account_route('member_data.update', $member) : account_route('member_data.store') }}" method="POST" novalidate>
              @csrf
              @if(isset($member))
                @method('PUT')
              @endif



              <div class="mb-3">
                <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $member->member_data['name'] ?? '') }}" required>
                <div class="invalid-feedback">Please enter Name</div>
              </div>

              <div class="mb-3">
                <label class="form-label">Gender <span class="text-danger">*</span></label>
                <div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="member_data[gender]" id="genderMale" value="男 Male" {{ old('member_data.gender', $member->member_data['gender'] ?? '') == '男 Male' ? 'checked' : '' }} required>
                    <label class="form-check-label" for="genderMale">男 Male</label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="member_data[gender]" id="genderFemale" value="女 Female" {{ old('member_data.gender', $member->member_data['gender'] ?? '') == '女 Female' ? 'checked' : '' }} required>
                    <label class="form-check-label" for="genderFemale">女 Female</label>
                  </div>
                  <div class="invalid-feedback">Please select Gender</div>
                </div>
              </div>

              <div class="mb-3">
                <label for="zodiac" class="form-label">Zodiac <span class="text-danger">*</span></label>
                <select class="form-select" id="zodiac" name="member_data[zodiac]" required>
                  <option value="">Please select</option>
                  <option value="鼠 Rat" {{ old('member_data.zodiac', $member->member_data['zodiac'] ?? '') == '鼠 Rat' ? 'selected' : '' }}>鼠 Rat</option>
                  <option value="牛 Ox" {{ old('member_data.zodiac', $member->member_data['zodiac'] ?? '') == '牛 Ox' ? 'selected' : '' }}>牛 Ox</option>
                  <option value="虎 Tiger" {{ old('member_data.zodiac', $member->member_data['zodiac'] ?? '') == '虎 Tiger' ? 'selected' : '' }}>虎 Tiger</option>
                  <option value="兔 Rabbit" {{ old('member_data.zodiac', $member->member_data['zodiac'] ?? '') == '兔 Rabbit' ? 'selected' : '' }}>兔 Rabbit</option>
                  <option value="龙 Dragon" {{ old('member_data.zodiac', $member->member_data['zodiac'] ?? '') == '龙 Dragon' ? 'selected' : '' }}>龙 Dragon</option>
                  <option value="蛇 Snake" {{ old('member_data.zodiac', $member->member_data['zodiac'] ?? '') == '蛇 Snake' ? 'selected' : '' }}>蛇 Snake</option>
                  <option value="马 Horse" {{ old('member_data.zodiac', $member->member_data['zodiac'] ?? '') == '马 Horse' ? 'selected' : '' }}>马 Horse</option>
                  <option value="羊 Goat" {{ old('member_data.zodiac', $member->member_data['zodiac'] ?? '') == '羊 Goat' ? 'selected' : '' }}>羊 Goat</option>
                  <option value="猴 Monkey" {{ old('member_data.zodiac', $member->member_data['zodiac'] ?? '') == '猴 Monkey' ? 'selected' : '' }}>猴 Monkey</option>
                  <option value="鸡 Rooster" {{ old('member_data.zodiac', $member->member_data['zodiac'] ?? '') == '鸡 Rooster' ? 'selected' : '' }}>鸡 Rooster</option>
                  <option value="狗 Dog" {{ old('member_data.zodiac', $member->member_data['zodiac'] ?? '') == '狗 Dog' ? 'selected' : '' }}>狗 Dog</option>
                  <option value="猪 Pig" {{ old('member_data.zodiac', $member->member_data['zodiac'] ?? '') == '猪 Pig' ? 'selected' : '' }}>猪 Pig</option>
                </select>
                <div class="invalid-feedback">Please select Zodiac</div>
              </div>

              <div class="mb-3">
                <label for="birth_date" class="form-label">Birth Date <span class="text-danger">*</span></label>
                <div class="input-group">
                  <input type="text" class="form-control" id="birth_date" name="member_data[birth_date]" value="{{ old('member_data.birth_date', $member->member_data['birth_date'] ?? '') }}" placeholder="YYYY-MM-DD" pattern="\d{4}-\d{2}-\d{2}" required>
                  <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                </div>
                <div class="invalid-feedback">Please enter Birth Date</div>
                <div class="form-text small text-muted">
                  Format: YYYY-MM-DD (e.g., 1990-01-15)
                </div>
              </div>

              <div class="mb-3">
                <label for="lunar_date" class="form-label">Lunar Date</label>
                <input type="text" class="form-control" id="lunar_date" name="member_data[lunar_date]" value="{{ old('member_data.lunar_date', $member->member_data['lunar_date'] ?? '') }}" readonly>
              </div>

              <div class="mb-3">
                <label for="birth_time" class="form-label">Birth Time <span class="text-danger">*</span></label>
                <select class="form-select" id="birth_time" name="member_data[birth_time]" required>
                  <option value="">Please select</option>
                  <option value="子时 ( 11:00pm - 00:59am )" {{ old('member_data.birth_time', $member->member_data['birth_time'] ?? '') == '子时 ( 11:00pm - 00:59am )' ? 'selected' : '' }}>子时 ( 11:00pm - 00:59am )</option>
                  <option value="丑时 ( 01:00am - 02:59am )" {{ old('member_data.birth_time', $member->member_data['birth_time'] ?? '') == '丑时 ( 01:00am - 02:59am )' ? 'selected' : '' }}>丑时 ( 01:00am - 02:59am )</option>
                  <option value="寅时 ( 03:00am - 04:59am )" {{ old('member_data.birth_time', $member->member_data['birth_time'] ?? '') == '寅时 ( 03:00am - 04:59am )' ? 'selected' : '' }}>寅时 ( 03:00am - 04:59am )</option>
                  <option value="卯时 ( 05:00am - 06:59am )" {{ old('member_data.birth_time', $member->member_data['birth_time'] ?? '') == '卯时 ( 05:00am - 06:59am )' ? 'selected' : '' }}>卯时 ( 05:00am - 06:59am )</option>
                  <option value="辰时 ( 07:00am - 08:59am )" {{ old('member_data.birth_time', $member->member_data['birth_time'] ?? '') == '辰时 ( 07:00am - 08:59am )' ? 'selected' : '' }}>辰时 ( 07:00am - 08:59am )</option>
                  <option value="巳时 ( 09:00am - 10:59am )" {{ old('member_data.birth_time', $member->member_data['birth_time'] ?? '') == '巳时 ( 09:00am - 10:59am )' ? 'selected' : '' }}>巳时 ( 09:00am - 10:59am )</option>
                  <option value="午时 ( 11:00am - 12:59pm )" {{ old('member_data.birth_time', $member->member_data['birth_time'] ?? '') == '午时 ( 11:00am - 12:59pm )' ? 'selected' : '' }}>午时 ( 11:00am - 12:59pm )</option>
                  <option value="未时 ( 01:00pm - 02:59pm )" {{ old('member_data.birth_time', $member->member_data['birth_time'] ?? '') == '未时 ( 01:00pm - 02:59pm )' ? 'selected' : '' }}>未时 ( 01:00pm - 02:59pm )</option>
                  <option value="申时 ( 03:00pm - 04:59pm )" {{ old('member_data.birth_time', $member->member_data['birth_time'] ?? '') == '申时 ( 03:00pm - 04:59pm )' ? 'selected' : '' }}>申时 ( 03:00pm - 04:59pm )</option>
                  <option value="酉时 ( 05:00pm - 06:59pm )" {{ old('member_data.birth_time', $member->member_data['birth_time'] ?? '') == '酉时 ( 05:00pm - 06:59pm )' ? 'selected' : '' }}>酉时 ( 05:00pm - 06:59pm )</option>
                  <option value="戌时 ( 07:00pm - 08:59pm )" {{ old('member_data.birth_time', $member->member_data['birth_time'] ?? '') == '戌时 ( 07:00pm - 08:59pm )' ? 'selected' : '' }}>戌时 ( 07:00pm - 08:59pm )</option>
                  <option value="亥时 ( 09:00pm - 10:59pm )" {{ old('member_data.birth_time', $member->member_data['birth_time'] ?? '') == '亥时 ( 09:00pm - 10:59pm )' ? 'selected' : '' }}>亥时 ( 09:00pm - 10:59pm )</option>
                </select>
                <div class="invalid-feedback">Please select Birth Time</div>
              </div>

              <div class="mb-3">
                <label for="whatsapp" class="form-label">WhatsApp <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="whatsapp" name="member_data[whatsapp]" value="{{ old('member_data.whatsapp', $member->member_data['whatsapp'] ?? '') }}" required>
                <div class="invalid-feedback">Please enter WhatsApp number</div>
              </div>

              <div class="d-flex justify-content-between mt-4">
                <a href="{{ account_route('member_data.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Save</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('footer')
<script>
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
      if (typeof Lunar === 'undefined') {
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
      $('#lunar_date').val(`${yearStr}年${lunarMonthStr}月${lunarDayStr}`);

      // Auto-select zodiac based on lunar year
      const animalIndex = lunar.getYearZhiIndex();
      const zodiacValues = ['鼠 Rat', '牛 Ox', '虎 Tiger', '兔 Rabbit', '龙 Dragon', '蛇 Snake', '马 Horse', '羊 Goat', '猴 Monkey', '鸡 Rooster', '狗 Dog', '猪 Pig'];
      $('#zodiac').val(zodiacValues[animalIndex]);

      return true;
    } catch (error) {
      console.error('Error converting date:', error);
      return false;
    }
  }

  // Initialize date picker
  const datepicker = $('#birth_date').datepicker({
    format: 'yyyy-mm-dd',
    autoclose: true,
    todayHighlight: true,
    endDate: new Date() // Can't select future dates
  }).on('changeDate', function(e) {
    // Get the selected date and convert
    const dateStr = $('#birth_date').val();
    convertToLunar(dateStr);
  });

  // Handle manual input
  $('#birth_date').on('change', function() {
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
  $('.input-group-text').on('click', function() {
    $('#birth_date').datepicker('show');
  });

  // Convert date on page load if it exists
  $(document).ready(function() {
    const dateStr = $('#birth_date').val();
    if (dateStr) {
      convertToLunar(dateStr);
    }
  });
</script>
@endpush

@push('footer')
<script>
  // Form validation
  (function () {
    'use strict'

    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.querySelectorAll('.needs-validation')

    // Loop over them and prevent submission
    Array.prototype.slice.call(forms)
      .forEach(function (form) {
        form.addEventListener('submit', function (event) {
          if (!form.checkValidity()) {
            event.preventDefault()
            event.stopPropagation()
          }

          form.classList.add('was-validated')
        }, false)
      })
  })()
</script>
@endpush
