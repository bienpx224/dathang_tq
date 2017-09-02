<div class="foot clearfix">
<!--版权所有 © <?=date('Y',time())?> <?=cadd($sitename)?>  -->
Copyright © <?=ucfirst(str_ireplace('www.','',$_SERVER['HTTP_HOST']));?> All Right Reserved.
</div>

<?php if(!$member){?>
    <!--流量统计代码:推广账号的代码--> 
    <script>
    var _hmt = _hmt || [];
    (function() {
      var hm = document.createElement("script");
      hm.src = "https://hm.baidu.com/hm.js?ba8959dd1f9d63fd28746140657c1b79";
      var s = document.getElementsByTagName("script")[0]; 
      s.parentNode.insertBefore(hm, s);
    })();
    </script>
<?php }?>

<script src="/bootstrap/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>

<?php 
$m='/m';require_once($_SERVER['DOCUMENT_ROOT'].$m.'/template/incluce/service.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/js/xingaoJS.php');//通用PHP JS
?>

</body>
</html>