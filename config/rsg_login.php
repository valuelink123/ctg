<?php

return [

    /*
    用户注册成功后，给用户发送一个带激活链接的邮件。
    */

    'email_host' => env('RSG_EMAIL_HOST', 'ssl://smtp.exmail.qq.com'),
    'email_port' => env('RSG_EMAIL_PORT', 465),
    'email_smtpauth' => env('RSG_EMAIL_SMTPAUTH', true),
    'email_username' => env('RSG_EMAIL_USERNAME', 'support@claimthegift.com'),
    'email_password' => env('RSG_EMAIL_PASSWORD', '2019VLctgisbest'),
    'email_smtpsecure' => env('RSG_EMAIL_SMTPSECURE', 'ssl'),
    'email_from_name' => env('RSG_EMAIL_FROM_NAME', 'CTG Suppport'),
    'email_reply_to_name' => env('RSG_EMAIL_REPLY_TO_NAME', 'Information'),

];
