
如果开启了url_rewrite且是nginx则nginx需要做如下的配置：

location / {
    index  index.html index.htm index.php;
    if (!-e $request_filename) {
        rewrite  ^(.*)$  /index.php?s=$1  last;
        break;
    }
}
