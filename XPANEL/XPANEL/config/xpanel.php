<?php

return [
    'useMD5'                    =>      true,
    'langtype_selector'         =>      true,
    'default_lang'              =>      'US',                       // Langtypes: 'US', 'ES', 'PT', 'FR'
    'langs_available'           =>      ['US', 'ES', 'PT', 'FR'],
    'use_separate_log_db'       =>      false,                      // If you want to use a separate database for logs, set this to true
    'emulator' => 'rA', // Emulators: 'rA', 'Hercules'
    'donations' => [
        'shop_type'             =>      'packages',                 // Shop Types: 'packages', 'points'
        'currency'              =>      'USD',
        'points_per_currency'   =>      1,                          // Example: 1 USD * 1 (<points_per_currency>) = 1 CashPoint
        'transfer_type'         =>      'item',                     // Transfer Types: 'item', 'variable'
        'donation_variable'     =>      '#CASHPOINTS',              // Only used if transfer_type is 'variable'
        'donation_item_id'      =>      40068,                      // Only used if transfer_type is 'item' (item id of the item that will be sent to the player)
        'methods' => [
            'paypal' => [
                'enabled'       =>      true,
                'name'          =>      'Paypal',
                'icon'          =>      "/img/paypal_method.png",
            ],
            'mercadopago' => [
                'enabled'       =>      true,
                'name'          =>      "MercadoPago",
                'icon'          =>      "/img/mercado_method.jpg",
            ],
        ]
    ],
];
