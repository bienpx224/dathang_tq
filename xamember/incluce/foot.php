<?php 
require_once($_SERVER['DOCUMENT_ROOT'].'/js/xingaoJS.php');//通用PHP JS
if($enlarge){require_once($_SERVER['DOCUMENT_ROOT'].'/public/enlarge/call.html');}//图片扩大插件
if($showbox){require_once($_SERVER['DOCUMENT_ROOT'].'/public/showbox.php');}//操作弹窗
?>


<?php if(!$alonepage){?>
	  </div>
	  <!-- BEGIN PAGE --> 
	</div>
	<!-- END CONTAINER --> 
<?php }?>

<br>

<?php
if(!$alonepage)
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/template/incluce/footer_call.php');
}
?>

<IFRAME src="/xamember/autoOut.php" name="out" width="0" height="0" border=0  marginWidth=0 frameSpacing=0 marginHeight=0  frameBorder=0 noResize scrolling=no vspale="0" style="display:none"></IFRAME>


<!-- 导航上的购物车和信息ajax调用 -->
<script src="/js/member_ajax_nav_cart-msg.js" type="text/javascript"></script>
<script>
  $(function(){       
	if($('[Id="cart_number"]').length>0&&$('[Id="cart_list"]').length>0){	cart_update();}
	if($('[Id="msg_number"]').length>0&&$('[Id="msg_list"]').length>0) {msg_update();}
  });
</script>

<!--后台有对照说明-->
<!-- BEGIN CORE PLUGINS -->
<!--[if lt IE 9]>
   <script src="/bootstrap/plugins/respond.min.js"></script>
   <script src="/bootstrap/plugins/excanvas.min.js"></script> 
<![endif]-->

<script src="/bootstrap/plugins/jquery-migrate-1.2.1.min.js" type="text/javascript"></script>
<script src="/bootstrap/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="/bootstrap/plugins/bootstrap/js/bootstrap2-typeahead.min.js" type="text/javascript"></script>
<script src="/bootstrap/plugins/bootstrap-hover-dropdown/twitter-bootstrap-hover-dropdown.min.js" type="text/javascript" ></script>
<script src="/bootstrap/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<script src="/bootstrap/plugins/jquery.blockui.min.js" type="text/javascript"></script>
<script src="/bootstrap/plugins/jquery.cookie.min.js" type="text/javascript"></script>
<script src="/bootstrap/plugins/uniform/jquery.uniform.min.js" type="text/javascript" ></script>
<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script type="text/javascript" src="/bootstrap/plugins/fuelux/js/spinner.min.js"></script>
<!--<script type="text/javascript" src="/bootstrap/plugins/bootstrap-fileupload/bootstrap-fileupload.js"></script>-->
<script type="text/javascript" src="/bootstrap/plugins/select2/select2.min.js"></script>
<!--<script type="text/javascript" src="/bootstrap/plugins/bootstrap-wysihtml5/wysihtml5-0.3.0.js"></script>-->
<!--<script type="text/javascript" src="/bootstrap/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.js"></script>-->
<script type="text/javascript" src="/bootstrap/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="/bootstrap/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js"></script>
<script type="text/javascript" src="/bootstrap/plugins/clockface/js/clockface.js"></script>
<script type="text/javascript" src="/bootstrap/plugins/bootstrap-daterangepicker/moment.min.js"></script>
<script type="text/javascript" src="/bootstrap/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>
<script type="text/javascript" src="/bootstrap/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js"></script>
<script type="text/javascript" src="/bootstrap/plugins/bootstrap-timepicker/js/bootstrap-timepicker.js"></script>
<script type="text/javascript" src="/bootstrap/plugins/jquery-inputmask/jquery.inputmask.bundle.min.js"></script>
<script type="text/javascript" src="/bootstrap/plugins/jquery.input-ip-address-control-1.0.min.js"></script>
<script type="text/javascript" src="/bootstrap/plugins/jquery-multi-select/js/jquery.multi-select.js"></script>
<script type="text/javascript" src="/bootstrap/plugins/jquery-multi-select/js/jquery.quicksearch.js"></script>
<!--<script src="/bootstrap/plugins/jquery.pwstrength.bootstrap/src/pwstrength.js" type="text/javascript" ></script>-->
<script src="/bootstrap/plugins/bootstrap-switch/static/js/bootstrap-switch.min.js" type="text/javascript" ></script>
<script src="/bootstrap/plugins/jquery-tags-input/jquery.tagsinput.min.js" type="text/javascript" ></script>
<script src="/bootstrap/plugins/bootstrap-markdown/js/bootstrap-markdown.js" type="text/javascript" ></script>
<script src="/bootstrap/plugins/bootstrap-markdown/lib/markdown.js" type="text/javascript" ></script>
<script src="/bootstrap/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js" type="text/javascript" ></script>

<script src="/bootstrap/plugins/bootstrap-toastr/toastr.min.js"></script>  
<script src="/bootstrap/scripts/ui-toastr.js"></script>    
<!-- END PAGE LEVEL PLUGINS -->

<!-- BEGIN PAGE LEVEL PLUGINS -->
<script type="text/javascript" src="/bootstrap/plugins/data-tables/jquery.dataTables.js"></script>
<script type="text/javascript" src="/bootstrap/plugins/data-tables/DT_bootstrap.js"></script>
<!-- END PAGE LEVEL PLUGINS -->

<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="/bootstrap/scripts/app.js"></script>
<script src="/bootstrap/scripts/form-components.js"></script>
<script src="/bootstrap/scripts/table-managed.js"></script>
<!-- END PAGE LEVEL SCRIPTS -->
<script>
  $(function(){       
	 // initiate layout and plugins
	 App.init();
	 FormComponents.init();
	 TableManaged.init();
  });
</script>
<!-- BEGIN GOOGLE RECAPTCHA -->
<script type="text/javascript">
var RecaptchaOptions = {
   theme : 'custom',
   custom_theme_widget: 'recaptcha_widget'
};
</script>
<!-- END GOOGLE RECAPTCHA -->
<!-- END JAVASCRIPTS -->

<?php if(!$alonepage){require_once($_SERVER['DOCUMENT_ROOT'].'/template/incluce/service.php');}?>

</body>
<!-- END BODY -->
</html>