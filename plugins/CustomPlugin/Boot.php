<?php
/**
 * 自定义内容插件
 */

namespace Plugin\CustomPlugin;

use InnoShop\Common\Repositories\ProductRepo;

class Boot
{
    /**
     * 插件初始化方法
     */
    public function init(): void
    {
        // 在主题设置页面的侧边栏菜单中添加Content选项
        listen_blade_insert('themes.settings.nav', function ($data) {
            return '<a class="nav-link" href="#" data-bs-toggle="tab" data-bs-target="#tab-setting-content">' . trans('CustomPlugin::common.content') . '</a>';
        });

        // 添加Content标签对应的内容面板
        listen_blade_insert('themes.settings.tabs', function ($data) {
            return '
              <div class="tab-pane fade" id="tab-setting-content">
                <div class="row">
                  <div class="col-12">
                    <div class="card">
                      <div class="card-header">' . trans('CustomPlugin::common.content_settings') . '</div>
                      <div class="card-body">
                        <div class="mb-3">
                          <label class="form-label">' . trans('CustomPlugin::common.page_title') . '</label>
                          <input type="text" name="content_title" class="form-control"
                                 value="' . system_setting('content_title', '') . '">
                        </div>
                        <div class="mb-3">
                          <label class="form-label">' . trans('CustomPlugin::common.meta_description') . '</label>
                          <textarea name="content_description" class="form-control" rows="3">' . system_setting('content_description', '') . '</textarea>
                        </div>
                        <div class="mb-3">
                          <label class="form-label">' . trans('CustomPlugin::common.featured_content') . '</label>
                          <textarea name="featured_content" class="form-control" rows="5">' . system_setting('featured_content', '') . '</textarea>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>';
        });

        // 在首页添加自定义产品区块1
        $this->setupProductSection(1);

        // 在首页添加自定义产品区块2
        $this->setupProductSection(2);

        // 在首页添加自定义产品区块3
        $this->setupProductSection(3);

        // 添加产品选择面板的钩子（在产品编辑页面）
        listen_blade_insert('panel.product.edit.tab.nav.bottom', function ($data) {
            return '
              <li class="nav-item" role="presentation">
                <button class="nav-link" id="custom-section-tab" data-bs-toggle="tab"
                  data-bs-target="#custom-section-tab-pane" type="button" role="tab"
                  aria-controls="custom-section-tab-pane" aria-selected="false">
                  ' . trans('CustomPlugin::common.product_selection') . '
                </button>
              </li>';
        });

        // 添加产品选择面板内容
        listen_blade_insert('panel.product.edit.tab.pane.bottom', function ($data) {
            return '
              <div class="tab-pane fade" id="custom-section-tab-pane" role="tabpanel"
                aria-labelledby="custom-section-tab" tabindex="0">
                <div class="row mb-3">
                  <div class="col-12">
                    <h5 class="my-3">' . trans('CustomPlugin::common.selected_products') . '</h5>
                    <p class="text-muted">' . trans('CustomPlugin::common.product_ids_help') . '</p>

                    <div class="form-check my-3">
                      <input class="form-check-input" type="checkbox" id="add-to-section-1">
                      <label class="form-check-label" for="add-to-section-1">
                        ' . trans('CustomPlugin::common.enable_custom_section') . ' (' . $this->getMultiLangSetting('section_title', 'Custom Products 1') . ')
                      </label>
                    </div>

                    <div class="form-check my-3">
                      <input class="form-check-input" type="checkbox" id="add-to-section-2">
                      <label class="form-check-label" for="add-to-section-2">
                        ' . trans('CustomPlugin::common.enable_custom_section_2') . ' (' . $this->getMultiLangSetting('section_title_2', 'Custom Products 2') . ')
                      </label>
                    </div>

                    <div class="form-check my-3">
                      <input class="form-check-input" type="checkbox" id="add-to-section-3">
                      <label class="form-check-label" for="add-to-section-3">
                        ' . trans('CustomPlugin::common.enable_custom_section_3') . ' (' . $this->getMultiLangSetting('section_title_3', 'Custom Products 3') . ')
                      </label>
                    </div>

                    <div class="d-grid gap-2 d-md-block mt-3">
                      <button type="button" class="btn btn-primary" id="save-product-sections">
                        ' . trans('panel/common.save') . '
                    </button>
                    </div>

                    <script>
                      document.addEventListener("DOMContentLoaded", function() {
                        // 获取当前产品ID
                        const productIdFromUrl = window.location.pathname.split("/").pop();
                        // 如果是纯数字则使用，否则尝试从表单获取
                        let productId = /^\d+$/.test(productIdFromUrl) ? productIdFromUrl : null;

                        // 如果URL中的不是数字ID，尝试从表单获取
                        if (!productId) {
                          const form = document.getElementById("product-form");
                          if (form) {
                            const actionUrl = form.getAttribute("action");
                            if (actionUrl) {
                              const matches = actionUrl.match(/\/products\/(\d+)$/);
                              if (matches && matches[1]) {
                                productId = matches[1];
                              }
                            }
                          }
                        }

                        // 如果没有有效的产品ID，则隐藏或禁用相关功能
                        if (!productId || productId === "edit" || isNaN(parseInt(productId))) {
                          console.log("有效的产品ID不可用，可能是新产品创建页面");
                          // 禁用选择区块的功能，等待产品保存后再使用
                          document.getElementById("save-product-sections").disabled = true;
                          return;
                        }

                        // 初始化选择状态
                        const initSectionCheck = async function() {
                          try {
                            const response = await fetch(urls.base_url + "/plugins/CustomPlugin/check-product?id=" + productId);
                            const data = await response.json();

                            if(data.section1) document.getElementById("add-to-section-1").checked = true;
                            if(data.section2) document.getElementById("add-to-section-2").checked = true;
                            if(data.section3) document.getElementById("add-to-section-3").checked = true;
                          } catch(e) {
                            console.error("Error loading product section data:", e);
                          }
                        };

                        // 保存选择
                        document.getElementById("save-product-sections").addEventListener("click", async function() {
                          const section1 = document.getElementById("add-to-section-1").checked;
                          const section2 = document.getElementById("add-to-section-2").checked;
                          const section3 = document.getElementById("add-to-section-3").checked;

                          try {
                            const response = await fetch(urls.base_url + "/plugins/CustomPlugin/save-product-sections", {
                              method: "POST",
                              headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": document.querySelector("meta[name=\'csrf-token\']").getAttribute("content")
                              },
                              body: JSON.stringify({
                                productId,
                                section1,
                                section2,
                                section3
                              })
                            });

                            const result = await response.json();

                            if(result.success) {
                              inno.msg("Product sections saved successfully");
                            } else {
                              inno.msg("Error saving product sections", "error");
                            }
                          } catch(e) {
                            console.error("Error saving product section data:", e);
                            inno.msg("Error saving product sections", "error");
                          }
                        });

                        // 初始化
                        initSectionCheck();
                      });
                    </script>
                    </div>
                </div>
            </div>';
        });
    }

    /**
     * 设置产品区块
     */
    private function setupProductSection(int $sectionNumber): void
    {
        // 设置钩子位置
        $hookPosition = $sectionNumber === 1 ? 'home.content.bottom' : 'home.content.bottom' . $sectionNumber;

        // 设置配置键名
        $enableKey = $sectionNumber === 1 ? 'enable_custom_section' : 'enable_custom_section_' . $sectionNumber;
        $titleKey = $sectionNumber === 1 ? 'section_title' : 'section_title_' . $sectionNumber;
        $subtitleKey = $sectionNumber === 1 ? 'section_subtitle' : 'section_subtitle_' . $sectionNumber;
        $productIdsKey = 'product_ids_' . $sectionNumber;

        // 监听对应钩子
        listen_blade_insert($hookPosition, function ($data) use ($sectionNumber, $enableKey, $titleKey, $subtitleKey, $productIdsKey) {
            // Debug信息
            $debugInfo = '<!-- CustomPlugin区块' . $sectionNumber . '钩子被触发! 开关状态: ' .
                        (plugin_setting('CustomPlugin', $enableKey, false) ? '开启' : '关闭') . ' -->';

            // 检查开关是否打开
            if (!plugin_setting('CustomPlugin', $enableKey, false)) {
                return $debugInfo . '<!-- 开关关闭，不显示自定义产品 -->';
            }

            // 获取产品数据
            $customProductIds = plugin_setting('CustomPlugin', $productIdsKey, '');
            $debugInfo .= '<!-- 原始产品ID配置: "' . $customProductIds . '" -->';
            $selectedProductIds = [];

            // 解析自定义产品ID
            if (!empty($customProductIds)) {
                $selectedProductIds = array_filter(array_map('trim', explode("\n", $customProductIds)));
                $debugInfo .= '<!-- 解析后的产品ID: ' . json_encode($selectedProductIds) . ' -->';
            } else {
                $debugInfo .= '<!-- 未配置产品ID -->';
            }

            // 如果有选定的产品ID，则获取这些产品
            if (!empty($selectedProductIds)) {
                $randomProducts = [];
                $errorProducts = [];

                // 直接通过ID获取产品，而不是过滤最新产品
                foreach ($selectedProductIds as $productId) {
                    try {
                        // 使用正确的方法获取产品，InnoShop不提供findById方法
                        // 先获取全部产品，然后找到匹配ID的产品
                        $product = null;
                        // 使用getById方法，这是标准的Repository模式中通常提供的方法
                        if (method_exists(ProductRepo::getInstance(), 'getById')) {
                            $product = ProductRepo::getInstance()->getById($productId);
                        } else if (method_exists(ProductRepo::getInstance(), 'get')) {
                            // 尝试使用get方法
                            $product = ProductRepo::getInstance()->get($productId);
                        } else {
                            // 回退到获取所有产品然后查找
                            $allProducts = ProductRepo::getInstance()->getLatestProducts(100);
                            foreach ($allProducts as $p) {
                                if ((string)$p->id === (string)$productId) {
                                    $product = $p;
                                    break;
                                }
                            }
                        }

                        if ($product && $product->active) {
                            // 确保产品URL属性存在
                            if (!isset($product->url)) {
                                $product->url = url('products/' . $product->id);
                            }
                            $randomProducts[] = $product;
                            $debugInfo .= '<!-- 成功获取产品ID: ' . $productId . ' -->';
                        } else {
                            $errorProducts[] = $productId;
                            $debugInfo .= '<!-- 产品ID: ' . $productId . ' 不存在或未激活 -->';
                        }
                    } catch (\Exception $e) {
                        $errorProducts[] = $productId;
                        $debugInfo .= '<!-- 获取产品ID: ' . $productId . ' 失败: ' . $e->getMessage() . ' -->';
                    }
                }

                // 添加详细调试信息
                $debugInfo .= '<!-- 已选产品ID: ' . implode(',', $selectedProductIds) . ' -->';
                $debugInfo .= '<!-- 成功获取产品数量: ' . count($randomProducts) . ' -->';
                if (!empty($errorProducts)) {
                    $debugInfo .= '<!-- 获取失败的产品ID: ' . implode(',', $errorProducts) . ' -->';
                }

                $customProducts = [];
            } else {
                // 默认获取最新和热销产品
                if ($sectionNumber === 1) {
                    $randomProducts = ProductRepo::getInstance()->getLatestProducts(8);
                    $customProducts = ProductRepo::getInstance()->getBestSellerProducts(8);
                } elseif ($sectionNumber === 2) {
                    $randomProducts = ProductRepo::getInstance()->getLatestProducts(4);
                    $customProducts = ProductRepo::getInstance()->getBestSellerProducts(8);
                } else {
                    $randomProducts = ProductRepo::getInstance()->getLatestProducts(6);
                    $customProducts = ProductRepo::getInstance()->getBestSellerProducts(6);
                }
                $debugInfo .= '<!-- 使用默认产品数据 -->';
            }

            // Debug信息
            $debugInfo .= '<!-- 产品数量: ' . (empty($selectedProductIds) ? '最新产品=' . count($randomProducts) . '个, 热销产品=' . count($customProducts) . '个' : '选定产品=' . count($randomProducts) . '个') . ' -->';

            // 检查是否有产品数据
            if (empty($randomProducts) && empty($customProducts)) {
                return $debugInfo . '<!-- 没有找到产品数据 -->';
            }

            // 添加调试信息
            $debugInfo .= '<!-- 开始构建产品区块，发现' . (empty($selectedProductIds) ?
                '默认产品（最新=' . count($randomProducts) . '，热销=' . count($customProducts) . '）' :
                '已选产品=' . count($randomProducts) . '个') . ' -->';

            // 构建自定义标签页数据
            if (!empty($selectedProductIds)) {
                $customTabs = [
                    [
                        'tab_title' => trans('CustomPlugin::common.selected_products'),
                        'products' => $randomProducts
                    ]
                ];
            } else {
                $customTabs = [
                    [
                        'tab_title' => trans('CustomPlugin::common.new_arrivals'),
                        'products' => $randomProducts
                    ],
                    [
                        'tab_title' => trans('CustomPlugin::common.best_sellers'),
                        'products' => $customProducts
                    ]
                ];
            }

            // 生成区块ID（确保每个区块的ID不重复）
            $blockId = 'custom-product-section-' . $sectionNumber;

            // 返回自定义产品区块HTML
            return $debugInfo . '
            <section class="module-line" id="' . $blockId . '">
              <div class="module-product-tab">
                <div class="container">
                  <div class="module-title-wrap">
                    <div class="module-title">' . $this->getMultiLangSetting($titleKey, 'Custom Products ' . $sectionNumber) . '</div>
                    <div class="module-sub-title">' . $this->getMultiLangSetting($subtitleKey, 'Discover our special items') . '</div>
                  </div>

                  <ul class="nav nav-tabs">
                    ' . $this->renderTabButtons($customTabs, $sectionNumber) . '
                  </ul>

                  <div class="tab-content">
                    ' . $this->renderTabContent($customTabs, $sectionNumber) . '
                  </div>
                </div>
              </div>
            </section>';
        });
    }

    /**
     * 渲染标签按钮
     */
    private function renderTabButtons(array $tabs, int $sectionNumber): string
    {
        $html = '';
        foreach ($tabs as $index => $item) {
            if (empty($item['products'])) {
                continue;
            }

            $html .= '
              <li class="nav-item" role="presentation">
                <button class="nav-link ' . ($index === 0 ? 'active' : '') . '" data-bs-toggle="tab"
                  data-bs-target="#custom-module-product-tab-' . $sectionNumber . '-' . ($index + 1) . '"
                  type="button">' . $item['tab_title'] . '</button>
              </li>';
        }
        return $html;
    }

    /**
     * 渲染标签内容
     */
    private function renderTabContent(array $tabs, int $sectionNumber): string
    {
        $html = '';
        foreach ($tabs as $index => $item) {
            if (empty($item['products'])) {
                continue;
            }

            $tabId = "custom-module-product-tab-{$sectionNumber}-" . ($index + 1);

            $html .= '
              <div class="tab-pane fade show ' . ($index === 0 ? 'active' : '') . '"
                id="' . $tabId . '" role="tabpanel">
                <div class="product-slider-container">
                  <div class="product-slider" id="product-slider-' . $tabId . '">
                    <div class="row product-slider-track">';

            if (empty($item['products'])) {
                $html .= '<div class="col-12"><p class="text-center">' . trans('CustomPlugin::common.no_products') . '</p></div>';
            } else {
                // 添加产品总数调试信息
                $html .= '<!-- 开始渲染产品列表，共' . count($item['products']) . '个产品 -->';

                foreach ($item['products'] as $product) {
                    // 添加每个产品的基本信息检查
                    $productDebug = '<!-- 产品ID: ' . ($product->id ?? 'unknown') . ' ';

                    // 简化产品属性检测
                    $hasName = false;
                    $hasImage = false;
                    $hasPrice = false;
                    $productName = 'Product #' . $product->id; // 默认产品名称

                    // 检查名称
                    if (isset($product->name) && !empty($product->name)) {
                        $hasName = true;
                        $productName = $product->name;
                    } elseif (isset($product->description) && isset($product->description->name) && !empty($product->description->name)) {
                        $hasName = true;
                        $productName = $product->description->name;
                    } elseif (method_exists($product, 'translate')) {
                        // 尝试使用translate方法获取名称
                        $translatedName = $product->translate(app()->getLocale(), 'name');
                        if (!empty($translatedName)) {
                            $hasName = true;
                            $productName = $translatedName;
                        }
                    }

                    // 检查图片
                    if (isset($product->image) && !empty($product->image)) {
                        $hasImage = true;
                    }

                    // 检查价格 - 改进为优先使用masterSku的价格
                    $productPrice = 0;
                    if (isset($product->masterSku) && isset($product->masterSku->price) && is_numeric($product->masterSku->price)) {
                        $hasPrice = true;
                        $productPrice = $product->masterSku->price;
                        // 尝试使用getFinalPrice方法，如果有的话
                        if (method_exists($product->masterSku, 'getFinalPrice')) {
                            $productPrice = $product->masterSku->getFinalPrice();
                        }
                    } elseif (isset($product->price) && is_numeric($product->price)) {
                        $hasPrice = true;
                        $productPrice = $product->price;
                    }

                    // 构建产品URL
                    $productUrl = isset($product->url) ? $product->url : url('products/' . $product->id);
                    $productId = $product->id ?? 0;
                    $skuId = isset($product->masterSku) && isset($product->masterSku->id) ? $product->masterSku->id : 0;

                    // 调试信息
                    $productDebug .= '名称: ' . ($hasName ? '有' : '无') . ' ';
                    $productDebug .= '图片: ' . ($hasImage ? '有' : '无') . ' ';
                    $productDebug .= '价格: ' . ($hasPrice ? '有' : '无') . ' -->';
                    $html .= $productDebug;

                    // 只有当产品缺少图片或价格时才跳过
                    if (!$hasImage || !$hasPrice) {
                        $html .= '<!-- 产品缺少图片或价格信息，已跳过 -->';
                        continue;
                    }

                    // 使用与原始innoshop一致的HTML结构，但修改为slider-item类
                    $html .= '
                      <div class="col-4 col-md-2 product-slider-item">
                        <div class="product-grid-item">
                          <div class="image">
                            <a href="' . $productUrl . '">
                              <img src="' . $product->image . '" class="img-fluid">
                            </a>
                            <div class="wishlist-container add-wishlist" data-in-wishlist="" data-id="' . $productId . '" data-price="' . $productPrice . '">
                              <i class="bi bi-heart"></i> Add to Wish List
                            </div>
                          </div>
                          <div class="product-item-info">
                            <div class="product-name">
                              <a href="' . $productUrl . '">
                                ' . $productName . '
                              </a>
                            </div>

                            <div class="product-bottom">
                              <div class="product-bottom-btns">
                                <div class="btn-add-cart cursor-pointer" data-id="' . $productId . '" data-price="' . $productPrice . '" data-sku-id="' . $skuId . '">Add to cart
                                </div>
                              </div>
                            <div class="product-price">
                                <div class="price-new">RM' . number_format($productPrice, 2) . '</div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>';
                }
            }

            $html .= '
                </div>
                  </div>
                  <button class="slider-nav slider-prev" id="prev-' . $tabId . '"><i class="bi bi-chevron-left"></i></button>
                  <button class="slider-nav slider-next" id="next-' . $tabId . '"><i class="bi bi-chevron-right"></i></button>
                </div>';

            // 添加产品轮播的JavaScript
            $html .= '
                <script>
                document.addEventListener("DOMContentLoaded", function() {
                  const slider = document.getElementById("product-slider-' . $tabId . '");
                  const track = slider.querySelector(".product-slider-track");
                  const items = slider.querySelectorAll(".product-slider-item");
                  const prevBtn = document.getElementById("prev-' . $tabId . '");
                  const nextBtn = document.getElementById("next-' . $tabId . '");

                  let currentPosition = 0;
                  const itemsPerView = 6;
                  const totalItems = items.length;
                  const maxPosition = Math.max(0, Math.ceil(totalItems / itemsPerView) - 1);

                  function updateSliderPosition() {
                    const translateX = -currentPosition * 100;
                    track.style.transform = `translateX(${translateX}%)`;

                    // 更新按钮状态
                    prevBtn.style.display = currentPosition <= 0 ? "none" : "flex";
                    nextBtn.style.display = currentPosition >= maxPosition ? "none" : "flex";
                  }

                  // 初始化按钮状态
                  updateSliderPosition();

                  // 添加事件监听器
                  prevBtn.addEventListener("click", function() {
                    if (currentPosition > 0) {
                      currentPosition--;
                      updateSliderPosition();
                    }
                  });

                  nextBtn.addEventListener("click", function() {
                    if (currentPosition < maxPosition) {
                      currentPosition++;
                      updateSliderPosition();
                    }
                  });
                });
                </script>';

            $html .= '
              </div>';
        }

        // 添加全局CSS样式
        $html .= '
        <style>
          .product-slider-container {
            position: relative;
            overflow: hidden;
            padding: 0 30px;
          }

          .product-slider {
            position: relative;
            overflow: hidden;
          }

          .product-slider-track {
            display: flex;
            flex-wrap: nowrap;
            transition: transform 0.5s ease;
            width: 100%;
          }

          .product-slider-item {
            flex: 0 0 16.666%;
            padding: 0 10px;
            box-sizing: border-box;
          }

          /* 确保产品名称完整显示 */
          .product-slider-item .product-name a {
            white-space: normal;
            overflow: visible;
            text-overflow: clip;
            display: block;
            height: auto;
            line-height: 1.4;
          }

          .slider-nav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.8);
            border: 1px solid #ddd;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 10;
          }

          .slider-prev {
            left: 0;
          }

          .slider-next {
            right: 0;
          }

          @media (max-width: 767px) {
            .product-slider-item {
              flex: 0 0 33.333%;
            }
          }
        </style>';

        return $html;
    }

    /**
     * 获取多语言设置
     */
    private function getMultiLangSetting($key, $default)
    {
        $value = plugin_setting('CustomPlugin', $key, $default);
        // 如果是数组，尝试获取当前语言的设置
        if (is_array($value)) {
            // 先尝试使用前端语言代码获取
            if (function_exists('front_locale_code')) {
                $locale = front_locale_code();
                if (isset($value[$locale])) {
                    return $value[$locale];
                }
            }

            // 如果前端语言没有对应设置，尝试使用当前应用语言
            $appLocale = app()->getLocale();
            if (isset($value[$appLocale])) {
                return $value[$appLocale];
            }

            // 如果两者都没有，尝试使用英语
            if (isset($value['en'])) {
                return $value['en'];
            }

            // 如果英语也没有，返回数组的第一个元素
            if (!empty($value)) {
                return reset($value);
            }
        }

        // 如果不是数组或者没有找到合适的语言设置，直接返回原值
        return $value;
    }
}