<!-- WhatsApp Floating Button -->
<div class="whatsapp-float">
  <a href="javascript:void(0)" id="whatsapp-float-btn" title="Chat with us on WhatsApp">
    <img src="{{ asset('images/whatsapp-logo.png') }}" alt="WhatsApp Chat">
  </a>
</div>

<!-- WhatsApp Popup -->
<div class="whatsapp-popup" id="whatsapp-popup">
  <div class="whatsapp-popup-header">
    <h3>CUSTOMER SERVICE</h3>
    <button class="whatsapp-popup-close" id="whatsapp-popup-close">&times;</button>
  </div>
  <div class="whatsapp-popup-content">
    <p class="whatsapp-popup-description">
      我们的客服专员回复时间为早上9点至下午6点半。若尚未收到回复消息，请耐心等候。谢谢。
    </p>
    <div class="whatsapp-option">
      <div class="whatsapp-option-icon">
        <img src="{{ asset('images/product-enquiry-icon.png') }}" alt="Product Enquiry" onerror="this.src='{{ asset('images/whatsapp-logo.png') }}'">
      </div>
      <div class="whatsapp-option-info">
        <a href="https://wa.me/60134315111" target="_blank" rel="noopener noreferrer">
          <h4>产品询问热线</h4>
          <p>Product Enquiry Hotline</p>
        </a>
      </div>
    </div>
    <div class="whatsapp-option">
      <div class="whatsapp-option-icon">
        <img src="{{ asset('images/fengshui-enquiry-icon.png') }}" alt="Feng Shui Enquiry" onerror="this.src='{{ asset('images/whatsapp-logo.png') }}'">
      </div>
      <div class="whatsapp-option-info">
        <a href="https://wa.me/60134315111" target="_blank" rel="noopener noreferrer">
          <h4>命理 / 风水询问热线</h4>
          <p>Feng Shui Enquiry Hotline</p>
        </a>
      </div>
    </div>
  </div>
</div>

<style>
  /* WhatsApp Floating Button Styles */
  .whatsapp-float {
    position: fixed;
    bottom: 20px;
    right: 20px;
    z-index: 1000;
    transition: all 0.3s ease;
  }

  .whatsapp-float a {
    display: flex;
    width: 60px;
    height: 60px;
    border-radius: 50%;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    align-items: center;
    justify-content: center;
    animation: whatsapp-pulse 2s infinite;
    position: relative;
    will-change: transform, box-shadow;
    overflow: hidden; /* Ensure image is clipped to circle */
    padding: 0px; /* Remove padding to make logo bigger */
  }

  .whatsapp-float a:hover {
    transform: scale(1.1);
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
    animation: none;
  }
  
  /* Active state for WhatsApp button when popup is open */
  .whatsapp-float a.active {
    transform: scale(1.1) rotate(360deg);
    animation: none;
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3);
    transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    filter: brightness(0.9); /* Slightly darken the image when active */
  }
  
  /* Transition back to normal state */
  .whatsapp-float a.active:hover {
    transform: scale(1.15) rotate(360deg);
  }

  .whatsapp-float img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 50%;
    display: block;
  }

  @keyframes whatsapp-pulse {
    0% {
      box-shadow: 0 0 0 0 rgba(0, 0, 0, 0.2);
      transform: scale(1);
    }
    50% {
      box-shadow: 0 0 0 12px rgba(0, 0, 0, 0);
      transform: scale(1.05);
    }
    100% {
      box-shadow: 0 0 0 0 rgba(0, 0, 0, 0);
      transform: scale(1);
    }
  }

  /* WhatsApp Popup Styles */
  .whatsapp-popup {
    position: fixed;
    bottom: 90px;
    right: 20px;
    width: 320px;
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 5px 25px rgba(0, 0, 0, 0.2);
    z-index: 1001;
    overflow: hidden;
    visibility: hidden;
    opacity: 0;
    transform: translateY(20px) scale(0.95);
    transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
    max-height: calc(100vh - 120px);
    overflow-y: auto;
    will-change: transform, opacity, visibility;
    transform-origin: bottom right;
  }

  .whatsapp-popup.visible {
    visibility: visible;
    opacity: 1;
    transform: translateY(0) scale(1);
  }
  
  /* Animation for options when popup appears */
  .whatsapp-popup.visible .whatsapp-option {
    animation: option-appear 0.5s forwards;
    opacity: 0;
    transform: translateY(15px);
  }
  
  .whatsapp-popup.visible .whatsapp-option:nth-child(1) {
    animation-delay: 0.1s;
  }
  
  .whatsapp-popup.visible .whatsapp-option:nth-child(2) {
    animation-delay: 0.2s;
  }
  
  @keyframes option-appear {
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  .whatsapp-popup.hiding {
    opacity: 0;
    transform: translateY(20px) scale(0.95);
    transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
  }

  .whatsapp-popup-header {
    background-color: #075E54;
    color: white;
    padding: 15px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    position: relative;
  }

  .whatsapp-popup-header:before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0) 100%);
    pointer-events: none;
  }

  .whatsapp-popup-header h3 {
    margin: 0;
    font-size: 18px;
    font-weight: 600;
    letter-spacing: 0.5px;
    display: flex;
    align-items: center;
  }

  .whatsapp-popup-header h3:before {
    content: '';
    display: inline-block;
    width: 20px;
    height: 20px;
    background-image: url('{{ asset('images/whatsapp-logo.png') }}');
    background-size: contain;
    background-repeat: no-repeat;
    margin-right: 8px;
  }

  .whatsapp-popup-close {
    background: none;
    border: none;
    color: white;
    font-size: 24px;
    cursor: pointer;
    padding: 0;
    line-height: 1;
    transition: transform 0.2s ease;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
  }

  .whatsapp-popup-close:hover {
    background-color: rgba(255, 255, 255, 0.1);
    transform: rotate(90deg);
  }

  .whatsapp-popup-content {
    padding: 18px;
  }

  .whatsapp-popup-description {
    margin-bottom: 18px;
    font-size: 14px;
    color: #555;
    line-height: 1.6;
    padding: 12px;
    background-color: #f5f5f5;
    border-radius: 8px;
    border-left: 3px solid #25D366;
  }

  .whatsapp-option {
    display: flex;
    align-items: center;
    padding: 15px;
    border-radius: 10px;
    background-color: #f9f9f9;
    margin-bottom: 12px;
    transition: all 0.3s ease;
    cursor: pointer;
    border: 1px solid #eaeaea;
    position: relative;
    overflow: hidden;
  }

  .whatsapp-option:hover {
    background-color: #f0f0f0;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
    border-color: #d9d9d9;
  }

  .whatsapp-option:after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 4px;
    height: 100%;
    background-color: #25D366;
    opacity: 0;
    transition: opacity 0.3s ease;
  }

  .whatsapp-option:hover:after {
    opacity: 1;
  }

  .whatsapp-option-icon {
    width: 55px;
    height: 55px;
    border-radius: 50%;
    overflow: hidden;
    margin-right: 15px;
    flex-shrink: 0;
    border: 2px solid #eaeaea;
    padding: 2px;
    background-color: white;
    transition: all 0.3s ease;
  }

  .whatsapp-option:hover .whatsapp-option-icon {
    border-color: #25D366;
    transform: scale(1.05);
  }

  .whatsapp-option-icon img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 50%;
  }

  .whatsapp-option-info {
    flex-grow: 1;
  }

  .whatsapp-option-info a {
    text-decoration: none;
    color: inherit;
    display: block;
  }

  .whatsapp-option-info h4 {
    margin: 0 0 5px 0;
    font-size: 16px;
    color: #333;
    font-weight: 600;
    transition: color 0.3s ease;
  }

  .whatsapp-option:hover .whatsapp-option-info h4 {
    color: #25D366;
  }

  .whatsapp-option-info p {
    margin: 0;
    font-size: 14px;
    color: #666;
  }

  /* Responsive Styles */
  @media (max-width: 768px) {
    .whatsapp-float {
      bottom: 15px;
      right: 15px;
    }

    .whatsapp-float a {
      width: 50px;
      height: 50px;
      overflow: hidden; /* Ensure image is clipped to circle */
      padding: 0; /* Remove padding to make logo bigger */
    }

    .whatsapp-float img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      border-radius: 50%;
    }

    .whatsapp-popup {
      bottom: 75px;
      right: 10px;
      width: calc(100% - 20px);
      max-width: 320px;
    }
  }
</style>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Get elements
    const whatsappButton = document.getElementById('whatsapp-float-btn');
    const whatsappPopup = document.getElementById('whatsapp-popup');
    const closeButton = document.getElementById('whatsapp-popup-close');

    // Toggle popup when WhatsApp button is clicked
    whatsappButton.addEventListener('click', function(e) {
      e.preventDefault();

      // If popup is already visible, close it
      if (whatsappPopup.classList.contains('visible')) {
        closeWhatsappPopup();
      } else {
        // Otherwise, show the popup
        openWhatsappPopup();
      }
    });

    // Function to open the popup with smooth animation
    function openWhatsappPopup() {
      // Make sure popup is ready for animation
      whatsappPopup.classList.remove('hiding');
      // Trigger reflow
      void whatsappPopup.offsetWidth;
      // Make popup visible with animation
      whatsappPopup.classList.add('visible');
      // Add active class to button
      whatsappButton.classList.add('active');
    }

    // Function to close the popup with smooth animation
    function closeWhatsappPopup() {
      // Add hiding class for animation
      whatsappPopup.classList.add('hiding');
      // Remove visible class
      whatsappPopup.classList.remove('visible');
      // Remove active class from button
      whatsappButton.classList.remove('active');
    }

    // Hide popup when close button is clicked
    closeButton.addEventListener('click', function() {
      closeWhatsappPopup();
    });

    // Hide popup when clicking outside
    document.addEventListener('click', function(e) {
      // Only process if the popup is visible and the click is outside the popup
      // We exclude the WhatsApp button because it has its own click handler
      if (whatsappPopup.classList.contains('visible') &&
          !whatsappPopup.contains(e.target) &&
          e.target !== whatsappButton &&
          !whatsappButton.contains(e.target)) {
        closeWhatsappPopup();
      }
    });

    // Make the WhatsApp options clickable for the entire div
    const whatsappOptions = document.querySelectorAll('.whatsapp-option');
    whatsappOptions.forEach(function(option) {
      const link = option.querySelector('a');
      if (link) {
        option.addEventListener('click', function() {
          window.open(link.href, '_blank');
        });
      }
    });
  });
</script>
