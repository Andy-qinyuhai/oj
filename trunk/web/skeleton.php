<?php
// 每增加一个公式就少一半读者, 每依赖一种框架就少一半开发者。
$cache_time = 30;    // 缓存时长
$OJ_CACHE_SHARE = false;   // 是否跨会话共享
// 缓存开启
require_once( './include/cache_start.php' );
require_once( './include/db_info.inc.php' );
require_once( './include/memcache.php' );
require_once( './include/setlang.php' );
require_once( './include/bbcode.php' );
$view_title = "Hello skeleton";

//演示如何查询数据库
$sql="select count(1) cnt from users where defunct=? ";
$result=pdo_query($sql,"N" );
if(!empty($result))
        $cnt=$result[0]['cnt'];

//创建一个变量，传递数据给视图页
$view_content="这是一个骨架页，如果您想二次开发一个自己的页面，可以分别在web目录和template/syzoj目录，复制两个skeleton.php到新的文件名:
```bash
sudo su
cd /home/judge/src/web
cp skeleton.php myfile.php
cd template/syzoj
cp skeleton.php myfile.php

```
然后开始修改自己的myfile.php";

// 在显示变量中附加查询结果
$view_content.="\n\n当前有效用户: $cnt 人";

// 执行当前模板下同名的视图页
/////////////////////////Template
require( "template/" . $OJ_TEMPLATE . "/".basename(__FILE__));

// 缓存结束
/////////////////////////Common foot
if ( file_exists( './include/cache_end.php' ) )
        require_once( './include/cache_end.php' );

