<?php
return [
    [
        'name'      => 'key_id',
        'label'     => 'Razorpay Key ID',
        'type'      => 'string',
        'required'  => true,
        'rules'     => 'required|string',
    ],
    [
        'name'      => 'key_secret',
        'label'     => 'Razorpay Key Secret',
        'type'      => 'string',
        'required'  => true,
        'rules'     => 'required|string',
    ],
    [
        'name'      => 'test_mode',
        'label'     => 'Test Mode',
        'type'      => 'bool',
        'required'  => false,
        'rules'     => 'boolean',
    ]
];