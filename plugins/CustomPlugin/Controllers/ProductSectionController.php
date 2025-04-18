<?php

namespace Plugin\CustomPlugin\Controllers;

use Illuminate\Http\Request;
use InnoShop\Common\Controllers\Panel\Controller;
use InnoShop\Common\Repositories\ProductRepo;

class ProductSectionController extends Controller
{
    /**
     * 检查产品是否在某个区块中
     */
    public function checkProduct(Request $request)
    {
        $productId = $request->input('id');
        
        // 增强参数验证，确保productId是数字
        if (!$productId || !is_numeric($productId)) {
            return response()->json([
                'section1' => false,
                'section2' => false,
                'section3' => false,
                'error' => '无效的产品ID'
            ]);
        }
        
        // 获取各个区块的产品ID
        $section1Ids = $this->getProductIds('product_ids_1');
        $section2Ids = $this->getProductIds('product_ids_2');
        $section3Ids = $this->getProductIds('product_ids_3');
        
        return response()->json([
            'section1' => in_array($productId, $section1Ids),
            'section2' => in_array($productId, $section2Ids),
            'section3' => in_array($productId, $section3Ids)
        ]);
    }
    
    /**
     * 保存产品区块选择
     */
    public function saveProductSections(Request $request)
    {
        $productId = $request->input('productId');
        $section1 = $request->input('section1');
        $section2 = $request->input('section2');
        $section3 = $request->input('section3');
        
        // 验证参数
        if (!$productId || !is_numeric($productId)) {
            return response()->json(['success' => false, 'message' => '无效的产品ID']);
        }
        
        // 获取产品信息确认存在
        $found = false;
        $allProducts = ProductRepo::getInstance()->getLatestProducts(50);
        
        foreach ($allProducts as $product) {
            if ((string)$product->id === (string)$productId) {
                $found = true;
                break;
            }
        }
        
        if (!$found) {
            return response()->json(['success' => false, 'message' => 'Product not found']);
        }
        
        // 更新各个区块
        $this->updateProductInSection('product_ids_1', $productId, $section1);
        $this->updateProductInSection('product_ids_2', $productId, $section2);
        $this->updateProductInSection('product_ids_3', $productId, $section3);
        
        return response()->json(['success' => true]);
    }
    
    /**
     * 从插件设置中获取产品ID列表
     */
    private function getProductIds($settingKey)
    {
        $productIds = plugin_setting('CustomPlugin', $settingKey, '');
        if (empty($productIds)) {
            return [];
        }
        
        // 将值分割成数组，移除空行，并且清理每一项的空白字符
        return array_filter(array_map('trim', explode("\n", $productIds)), function($id) {
            return !empty($id);
        });
    }
    
    /**
     * 更新产品在区块中的状态
     */
    private function updateProductInSection($settingKey, $productId, $enabled)
    {
        $productIds = $this->getProductIds($settingKey);
        $productId = (string)$productId; // 确保ID是字符串
        
        if ($enabled) {
            // 添加产品ID（如果不存在）
            if (!in_array($productId, $productIds)) {
                $productIds[] = $productId;
            }
        } else {
            // 移除产品ID（如果存在）
            $productIds = array_filter($productIds, function($id) use ($productId) {
                return (string)$id !== $productId;
            });
        }
        
        // 保存更新后的产品ID列表
        $newValue = implode("\n", $productIds);
        plugin_setting(['CustomPlugin' => [$settingKey => $newValue]]);
        
        return true;
    }
} 