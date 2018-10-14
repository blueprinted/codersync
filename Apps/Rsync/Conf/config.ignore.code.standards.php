<?php

//用于配置哪些项目绕过强制代码规范检测
//有些项目目录里面的文件太多，生成过滤文件的时候导致用到的内存超出了，没法生成过滤文件，故增加此配置用于绕过强制代码规范检测

return array(
    'APP_IGNORE' => array(
        'shouji' => array(
            2,
        ),
        'pc' => array(
            7,
        ),
        'qq' => array(
        ),
        'srv' => array(
        ),
    ),
);
