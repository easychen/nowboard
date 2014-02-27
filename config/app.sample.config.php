<?php
$GLOBALS['config']['site_name'] = 'NowBoard - 实时信息流展板';
$GLOBALS['config']['site_domain'] = 'lazy.sinaapp.com';
$GLOBALS['config']['site_icon'] = image('nowban.txt.png');


$GLOBALS['config']['naoban_version'] = '0.1';

// 首先，你要有一个微博应用。没审核过的也行。
$GLOBALS['config']['weibo_akey'] = '请填入APPKEY';
$GLOBALS['config']['weibo_skey'] = '请填入APPSercret';

// 只有以下微博用户才能看到新消息
$GLOBALS['config']['user_weiboid'] = array
(
    '微博UID',
    '微博UID2'
);

// 为API接口分配渠道名称和对应的APPKEY
// 调用接口时必须带上APPKEY
$GLOBALS['config']['whois'] = array();
$GLOBALS['config']['whois']['appkey1'] = 'SAE';
$GLOBALS['config']['whois']['appkey2'] = 'MOS';

