<?php
/**
 * zipCF - Create Zip File with directory content
 * Author : Abdul Awal
 * Article Url: http://go.abdulawal.com/1
 * Version: 1.0
 * Released on: February 26 2016
 */
?>
<!DOCTYPE html>
<html lang="en">
<body>
<script type="text/javascript" src="/js/mapping.js"></script>
<script type="text/javascript">
    var url=new URL(window.location.href);
    let idSklenice = url.searchParams.get('id');
    window.location.href = 'https://www.pardubicebezobalu.cz/vsechno-zbozi/' + map[idSklenice] + '.html';
</script>
</body>
</html>