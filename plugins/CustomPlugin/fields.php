<?php

return [
    // 第一个产品区块
    [
        'name'      => 'enable_custom_section',
        'label_key' => 'CustomPlugin::common.enable_custom_section',
        'type'      => 'bool',
        'required'  => false,
        'default'   => false
    ],
    [
        'name'      => 'section_title',
        'label_key' => 'CustomPlugin::common.section_title',
        'type'      => 'multi-string',
        'required'  => false,
        'default'   => [
            'en' => 'Custom Products',
            'zh_cn' => '自定义产品'
        ]
    ],
    [
        'name'      => 'section_subtitle',
        'label_key' => 'CustomPlugin::common.section_subtitle',
        'type'      => 'multi-string',
        'required'  => false,
        'default'   => [
            'en' => 'Discover our special items',
            'zh_cn' => '探索我们的特色产品'
        ]
    ],
    [
        'name'      => 'product_ids_1',
        'label_key' => 'CustomPlugin::common.product_ids',
        'type'      => 'textarea',
        'required'  => false,
        'default'   => '',
        'help_key'  => 'CustomPlugin::common.product_ids_help'
    ],
    
    // 第二个产品区块
    [
        'name'      => 'enable_custom_section_2',
        'label_key' => 'CustomPlugin::common.enable_custom_section_2',
        'type'      => 'bool',
        'required'  => false,
        'default'   => false
    ],
    [
        'name'      => 'section_title_2',
        'label_key' => 'CustomPlugin::common.section_title_2',
        'type'      => 'multi-string',
        'required'  => false,
        'default'   => [
            'en' => 'Featured Products',
            'zh_cn' => '精选产品'
        ]
    ],
    [
        'name'      => 'section_subtitle_2',
        'label_key' => 'CustomPlugin::common.section_subtitle_2',
        'type'      => 'multi-string',
        'required'  => false,
        'default'   => [
            'en' => 'Our selection of top products',
            'zh_cn' => '我们精心挑选的顶级产品'
        ]
    ],
    [
        'name'      => 'product_ids_2',
        'label_key' => 'CustomPlugin::common.product_ids_2',
        'type'      => 'textarea',
        'required'  => false,
        'default'   => '',
        'help_key'  => 'CustomPlugin::common.product_ids_help'
    ],
    
    // 第三个产品区块
    [
        'name'      => 'enable_custom_section_3',
        'label_key' => 'CustomPlugin::common.enable_custom_section_3',
        'type'      => 'bool',
        'required'  => false,
        'default'   => false
    ],
    [
        'name'      => 'section_title_3',
        'label_key' => 'CustomPlugin::common.section_title_3',
        'type'      => 'multi-string',
        'required'  => false,
        'default'   => [
            'en' => 'Popular Products',
            'zh_cn' => '热门产品'
        ]
    ],
    [
        'name'      => 'section_subtitle_3',
        'label_key' => 'CustomPlugin::common.section_subtitle_3',
        'type'      => 'multi-string',
        'required'  => false,
        'default'   => [
            'en' => 'Most loved by our customers',
            'zh_cn' => '深受客户喜爱的产品'
        ]
    ],
    [
        'name'      => 'product_ids_3',
        'label_key' => 'CustomPlugin::common.product_ids_3',
        'type'      => 'textarea',
        'required'  => false,
        'default'   => '',
        'help_key'  => 'CustomPlugin::common.product_ids_help'
    ]
]; 