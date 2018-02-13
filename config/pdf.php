<?php
return [
    'format' => 'A4-L',
    'display_mode' => 'fullpage',
    'default_font_size' => '25',
    'creator' => 'Laravel Pdf',
    'custom_font_path' => public_path('fonts/thsarabun/'),
    'custom_font_data' => [
        'thsarabunnew' => [
            'R' => 'THSarabunNew.ttf',    // regular font
            'B' => 'THSarabunNew-Bold.ttf',       // optional: bold font
            'I' => 'THSarabunNew-Italic.ttf',     // optional: italic font
            'BI' => 'THSarabunNew-Bold-Italic.ttf', // optional: bold-italic font
        ]
    ]
];