@extends('layouts.app')
@section('body-class', 'page-account')

@section('title', 'Member Data')

@section('content')
  <x-front-breadcrumb type="route" value="account.member_data.index" title="Member Data"/>

  <div class="container">
    <div class="row">
      <div class="col-12 col-lg-3">
        @include('shared.account-sidebar')
      </div>
      <div id="content" class="col-12 col-lg-9">
        <!-- Header with title -->
        <div class="mb-4">
          <h2 class="mb-0 fw-bold">Member Data</h2>
        </div>

        @if(count($members) > 0)
          <div class="row g-4">
            @foreach($members as $member)
              <div class="col-12">
                <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
                  <div class="card-body p-0">
                    <div class="row g-0">
                      <div class="col-md-8 p-4">
                        <h3 class="card-title fw-bold mb-3">{{ $member->member_data['name'] ?? '' }}</h3>
                        <div class="row">
                          <div class="col-md-6 mb-2">
                            <div class="d-flex align-items-center">
                              <i class="bi bi-gender-ambiguous text-secondary me-2"></i>
                              <span>{{ $member->member_data['gender'] ?? '' }}</span>
                            </div>
                          </div>
                          <div class="col-md-6 mb-2">
                            <div class="d-flex align-items-center">
                              <i class="bi bi-stars text-secondary me-2"></i>
                              <span>{{ $member->member_data['zodiac'] ?? '' }}</span>
                            </div>
                          </div>
                          <div class="col-md-6 mb-2">
                            <div class="d-flex align-items-center">
                              <i class="bi bi-calendar-date text-secondary me-2"></i>
                              <span>{{ $member->member_data['birth_date'] ?? '' }}</span>
                            </div>
                          </div>
                          <div class="col-md-6 mb-2">
                            <div class="d-flex align-items-center">
                              <i class="bi bi-moon-stars text-secondary me-2"></i>
                              <span>{{ $member->member_data['lunar_date'] ?? '' }}</span>
                            </div>
                          </div>
                          <div class="col-md-6 mb-2">
                            <div class="d-flex align-items-center">
                              <i class="bi bi-clock text-secondary me-2"></i>
                              <span>{{ $member->member_data['birth_time'] ?? '' }}</span>
                            </div>
                          </div>
                          <div class="col-md-6 mb-2">
                            <div class="d-flex align-items-center">
                              <i class="bi bi-whatsapp text-secondary me-2"></i>
                              <span>{{ $member->member_data['whatsapp'] ?? '' }}</span>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-4 bg-light d-flex flex-column justify-content-center align-items-center p-4">
                        <a href="{{ account_route('member_data.edit', $member) }}" class="btn btn-outline-primary w-100 mb-2">
                          <i class="bi bi-pencil-square me-2"></i>Edit
                        </a>
                        <a href="{{ account_route('member_data.destroy', $member) }}"
                           class="btn btn-outline-danger w-100"
                           onclick="event.preventDefault(); if(confirm('Are you sure?')) document.getElementById('delete-form-{{ $member->id }}').submit();">
                          <i class="bi bi-trash me-2"></i>Delete
                        </a>
                        <form id="delete-form-{{ $member->id }}" action="{{ account_route('member_data.destroy', $member) }}" method="POST" style="display: none;">
                          @csrf
                          @method('DELETE')
                        </form>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            @endforeach
          </div>
        @else
          <div class="card shadow-sm border-0 rounded-3">
            <div class="card-body p-5 text-center">
              <i class="bi bi-exclamation-circle fs-1 text-secondary mb-3"></i>
              <p class="mb-0">No Data</p>
            </div>
          </div>
        @endif

        <div class="mt-4 d-flex justify-content-between">
          <div>
            <a href="{{ account_route('index') }}" class="btn btn-outline-secondary">
              <i class="bi bi-arrow-left me-2"></i>Back
            </a>
          </div>
          <div>
            <a href="{{ account_route('member_data.create') }}" class="btn btn-primary">
              <i class="bi bi-plus-circle me-2"></i>New Member
            </a>
            <a href="javascript:void(0)" id="member-data-help-btn" class="btn btn-outline-info ms-2">
              <i class="bi bi-question-circle me-1"></i>Help
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

<!-- Member Data Help Popup -->
<div id="member-data-help-popup" class="member-data-help-popup">
  <div class="member-data-help-content">
    <div class="member-data-help-header">
      <h3>Member Data Help</h3>
      <h3>成员资料解答</h3>
      <button id="member-data-help-close">&times;</button>
    </div>
    <div class="member-data-help-body">
      <!-- Q&A Section -->
      <div class="member-data-qa">
        <div class="member-data-question" data-qa="1">
          <p>(Q) What is Member Data?</p>
          <p>(问) 什么是成员资料?</p>
          <span class="qa-toggle"><i class="bi bi-plus"></i></span>
        </div>
        <div class="member-data-answer" id="qa-answer-1">
          <p>(A) Member Data are key personal records like Chinese name, gender, Solar and Lunar Dates of Birth, mobile number and etc.</p>
          <p>(答) 成员资料是主要的个人记录，如中文姓名、性别、出生日期、手机号码等。</p>
        </div>
      </div>

      <div class="member-data-qa">
        <div class="member-data-question" data-qa="2">
          <p>(Q) When is Member Data saved?</p>
          <p>(问) 成员资料什么时候保存?</p>
          <span class="qa-toggle"><i class="bi bi-plus"></i></span>
        </div>
        <div class="member-data-answer" id="qa-answer-2">
          <p>(A) When a customer logs in and adds a product or service (such as Light Offering Dharma Service 光明灯服务) to a shopping cart, certain key personal records are stored in the customer's Member Data.</p>
          <p>(答) 当客户登录并将产品或服务（例如光明灯服务）添加到购物车时，某些关键个人记录将存储在客户的成员资料中。</p>
        </div>
      </div>

      <div class="member-data-qa">
        <div class="member-data-question" data-qa="3">
          <p>(Q) What are the benefits of using Member Data?</p>
          <p>(问) 使用成员资料有什么好处?</p>
          <span class="qa-toggle"><i class="bi bi-plus"></i></span>
        </div>
        <div class="member-data-answer" id="qa-answer-3">
          <p>(A) This Member Data can be reused when the customer purchases other products that require these personal records to be filled. If you are a regular customer who purchased products or services for family members, this feature will simplify your job of filling in the data for those family members.</p>
          <p>(答) 当客户购买其他需要填写这些个人记录的产品时，可以重复使用这些成员资料。如果您是为家庭成员购买产品或服务的老客户，此功能将简化您填写这些家庭成员资料的工作。</p>
        </div>
      </div>

      <div class="member-data-qa">
        <div class="member-data-question" data-qa="4">
          <p>(Q) Can customers add, edit and delete Member Data?</p>
          <p>(问) 客户可以添加、编辑和删除成员资料吗?</p>
          <span class="qa-toggle"><i class="bi bi-plus"></i></span>
        </div>
        <div class="member-data-answer" id="qa-answer-4">
          <p>(A) Yes, customer can go to My Account > Member Data to add, edit and delete Member Data.</p>
          <p>(答) 是的，客户可以前往 My Account > Member Data 添加、编辑和删除成员资料。</p>
        </div>
      </div>

      <div class="member-data-qa">
        <div class="member-data-question" data-qa="5">
          <p>(Q) How to use Member Data when purchasing a product or service?</p>
          <p>(问) 购买产品或服务时如何使用成员资料?</p>
          <span class="qa-toggle"><i class="bi bi-plus"></i></span>
        </div>
        <div class="member-data-answer" id="qa-answer-5">
          <p>(A) First, the customer must be logged in. If the customer already has a saved Member Data, clicking on the main product field (usually the Chinese Name) will display the Member Data selection list. Select this item and key personal records will be automatically populated in the corresponding fields.</p>
          <p>(答) 首先，客户必须登录系统。如果客户已经保存了成员资料，点击主产品项目（通常是中文姓名）将显示成员资料选择列表。选择此项目，关键个人记录将自动填充到相应字段中。</p>
        </div>
      </div>

      <div class="member-data-privacy">
        <a href="javascript:void(0)">Privacy Policy 隐私政策</a>
      </div>
    </div>
  </div>
</div>

@push('footer')
<style>
  .card {
    transition: transform 0.2s ease-in-out;
  }
  .card:hover {
    transform: translateY(-3px);
  }
  .btn {
    border-radius: 0.375rem;
    padding: 0.5rem 1rem;
    transition: all 0.2s;
  }
  .btn-outline-primary:hover, .btn-outline-danger:hover, .btn-outline-info:hover {
    transform: translateY(-2px);
  }

  /* Member Data Help Popup Styles */
  .member-data-help-popup {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 1050;
    overflow-y: auto;
    animation: fadeIn 0.3s ease-out;
  }

  .member-data-help-content {
    position: relative;
    background-color: #fff;
    margin: 50px auto;
    padding: 0;
    width: 90%;
    max-width: 600px;
    border-radius: 8px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
    animation: slideIn 0.3s ease-out;
  }

  .member-data-help-header {
    padding: 15px 20px;
    border-bottom: 1px solid #e9ecef;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: #f8f9fa;
    border-radius: 8px 8px 0 0;
  }

  .member-data-help-header h3 {
    margin: 0;
    font-size: 1.25rem;
    color: #333;
  }

  .member-data-help-header button {
    background: none;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
    color: #6c757d;
    transition: color 0.2s;
  }

  .member-data-help-header button:hover {
    color: #dc3545;
  }

  .member-data-help-body {
    padding: 20px;
    max-height: 70vh;
    overflow-y: auto;
  }

  .member-data-qa {
    margin-bottom: 20px;
    border-bottom: 1px solid #e9ecef;
    padding-bottom: 15px;
  }

  .member-data-qa:last-child {
    border-bottom: none;
  }

  .member-data-question {
    margin-bottom: 0;
    font-weight: 600;
    color: #495057;
    padding: 15px;
    background-color: #f8f9fa;
    border-radius: 5px;
    cursor: pointer;
    position: relative;
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
    justify-content: center;
  }

  .member-data-question:hover {
    background-color: #e9ecef;
  }

  .member-data-question p {
    margin: 0;
  }

  .qa-toggle {
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 1.2rem;
    color: #6c757d;
    transition: all 0.3s ease;
  }

  .member-data-question.active .qa-toggle i {
    transform: rotate(45deg);
  }

  .member-data-answer {
    background-color: #ffffff;
    padding: 0 15px;
    max-height: 0;
    overflow: hidden;
    transition: all 0.3s ease;
    border-left: 3px solid #0d6efd;
    margin-left: 15px;
    opacity: 0;
  }

  .member-data-answer.active {
    padding: 15px;
    max-height: 500px;
    opacity: 1;
  }

  .member-data-answer p {
    margin: 0;
    line-height: 1.5;
  }

  .member-data-privacy {
    text-align: center;
    margin-top: 20px;
    padding-top: 15px;
    border-top: 1px solid #e9ecef;
  }

  .member-data-privacy a {
    color: #0d6efd;
    text-decoration: none;
  }

  .member-data-privacy a:hover {
    text-decoration: underline;
  }

  @keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
  }

  @keyframes slideIn {
    from { transform: translateY(-20px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
  }

  @media (max-width: 768px) {
    .member-data-help-content {
      width: 95%;
      margin: 30px auto;
    }

    .member-data-help-header {
      flex-direction: column;
      text-align: center;
    }

    .member-data-help-header button {
      position: absolute;
      top: 10px;
      right: 10px;
    }
  }
</style>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Get elements
    const helpButton = document.getElementById('member-data-help-btn');
    const helpPopup = document.getElementById('member-data-help-popup');
    const closeButton = document.getElementById('member-data-help-close');
    const questions = document.querySelectorAll('.member-data-question');

    // Initially hide all answers
    document.querySelectorAll('.member-data-answer').forEach(answer => {
      answer.classList.remove('active');
    });

    // Show popup when help button is clicked
    helpButton.addEventListener('click', function() {
      helpPopup.style.display = 'block';
      document.body.style.overflow = 'hidden'; // Prevent scrolling behind popup
    });

    // Hide popup when close button is clicked
    closeButton.addEventListener('click', function() {
      helpPopup.style.display = 'none';
      document.body.style.overflow = ''; // Restore scrolling

      // Reset all questions/answers when closing
      resetAllQuestions();
    });

    // Hide popup when clicking outside the content
    helpPopup.addEventListener('click', function(e) {
      if (e.target === helpPopup) {
        helpPopup.style.display = 'none';
        document.body.style.overflow = ''; // Restore scrolling

        // Reset all questions/answers when closing
        resetAllQuestions();
      }
    });

    // Close popup when ESC key is pressed
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape' && helpPopup.style.display === 'block') {
        helpPopup.style.display = 'none';
        document.body.style.overflow = ''; // Restore scrolling

        // Reset all questions/answers when closing
        resetAllQuestions();
      }
    });

    // Toggle answer when question is clicked
    questions.forEach(question => {
      question.addEventListener('click', function() {
        const qaId = this.getAttribute('data-qa');
        const answer = document.getElementById('qa-answer-' + qaId);

        // Check if this question is already active
        const isActive = this.classList.contains('active');

        // Reset all questions first
        resetAllQuestions();

        // If it wasn't active before, activate it now
        if (!isActive) {
          this.classList.add('active');
          answer.classList.add('active');
        }
      });
    });

    // Function to reset all questions to closed state
    function resetAllQuestions() {
      questions.forEach(q => {
        q.classList.remove('active');
      });

      document.querySelectorAll('.member-data-answer').forEach(a => {
        a.classList.remove('active');
      });
    }
  });
</script>
@endpush
