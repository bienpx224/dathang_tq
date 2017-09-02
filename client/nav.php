<!--导航-开始-->
<div class="xa_header_bg">
<div class="xa_container">
    	<div class="xa_header">
            <a class="xa_logo"><img src="/images/logo.png"/></a>
            <nav class="xa_navbar">
              <div class="xa_collapse">
                <ul class="xa_nav">
                <li class="<?=$ac1?>"><a href="/yundan/status.php"><?=$LG['front.1']?></a></li>
                 <?php if($off_upload_cert){?><li class="<?=$ac2?>"><a href="/yundan/upload.php"><?=$LG['front.2']?></a></li><?php }?>
                </ul>
              </div>
            </nav>
        </div>
    </div>
</div>
<!--导航-结束-->

<script type="text/javascript">
$(document).ready(function(e) {
	$('.xa_dropdown').hover(function(){
		$(this).find('.xa_dropdown-menu').show();
		$(this).addClass("xa_open");
	},
	function(){
		$(this).find('.xa_dropdown-menu').hide();
		$(this).removeClass("xa_open");
	});
});
</script>

