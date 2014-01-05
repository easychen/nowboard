Nowboard
========

基于SAE Channel服务的实时信息流展板。通过微博帐号登入、可聊天；支持API发布信息，可用于服务器调试信息、错误信息展示。

# SAE服务配置
* 开通Channel服务，内测期可找@sinaappengine 申请。
* 开通Memcache服务，1M就够。
* TaskQueue服务，并在TaskQueue服务中创建一个名叫nowboard的顺序队列。这是因为Channel服务接口有时候很慢，所以用TaskQueue改为异步。

# 创建微博应用
* 到open.weibo.com创建一个应用，要得到APP key和App Secret。
* 如果没通过审核，记得要在应用管理里边添加测试用户。不然微博授权会出错。

# 修改配置文件
* 将config/app.sample.config.php 改名为 config/app.config.php。
* 按提示将微博应用的APP key和App Secret填入。 
* 将要使用本应用的用户的微博UID按提示加入 talkman 数组。
* 如果要允许其他应用发送信息，按提示加入 whois 数组，key为appkey，value为来源文案。

# 其他说明
* 为实时信息流设计，没有存储，后打开的用户看不见之前用户的对话。
* 只显示最新50条信息

# API调用说明
* API接口地址为 *.sinaapp.com/?a=api
* GET/POST方法均可
* 接收参数如下
  * source - appkey
  * data - 要传输的数据
  * ckeys - 用,分割的tag，对应界面左侧的tag列表和计数，建议用英文
    
# CURL调用示例
为避免网络超时，调用时应强制超时时间。

```php
function nblog( $content , $tags = null )
{
    $baseurl = 'http://*.sinaapp.com/?a=api&data=' . u($content) ;

    if( $tags != null ) 
        $baseurl = $baseurl.'&ckeys='.u($tags) ;

    // SAE
    $url = $baseurl.'&source=528342357';
    // meituan
    //$url = $baseurl.'&source=33333456';

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,3 ); 
    curl_setopt($ch, CURLOPT_TIMEOUT, 5 ); 
    $response = curl_exec($ch);
    curl_close($ch);

}
```


