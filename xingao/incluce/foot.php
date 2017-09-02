<?php 
require_once($_SERVER['DOCUMENT_ROOT'].'/js/xingaoJS.php');//通用PHP JS
if($enlarge){require_once($_SERVER['DOCUMENT_ROOT'].'/public/enlarge/call.html');}//图片扩大插件
if($showbox){require_once($_SERVER['DOCUMENT_ROOT'].'/public/showbox.php');}//操作弹窗
?>

<?php if(!$alonepage){?>
	   <!--PC版主内容部分结束--> 
	  </div>
	  <!-- BEGIN PAGE --> 
	</div>
	<!-- END CONTAINER --> 
	<!-- BEGIN FOOTER -->
	<div class="footer">
	  <?php if($off_jishu){?>
	  <div class="footer-inner"> <a href="http://www.xingaowl.com" target="_blank">技术支持:
		<?=$jishu?>
		<?=$banben?>
		</a> </div>
	  <?php }?>
	  <div class="footer-tools"> <span class="go-top"> <i class="icon-angle-up"></i> </span> </div>
	</div>
	<!-- END FOOTER --> 
    
    <!-- 导航上的信息ajax调用 -->
    <script src="/js/manage_ajax_nav_msg.js" type="text/javascript"></script>
    <script>
      $(function(){       
        if($('[Id="msg_number"]').length>0&&$('[Id="msg_list"]').length>0) {msg_update();}
      });
    </script>

<?php }?>


<?php if(!$login){?>
<IFRAME src="/xingao/autoOut.php" name="out" width="0" height="0" border=0  marginWidth=0 frameSpacing=0 marginHeight=0  frameBorder=0 noResize scrolling=no vspale="0" style="display:none"></IFRAME>
<?php }?>



<!-- BEGIN CORE PLUGINS -->
<!--[if lt IE 9]>
   <script src="/bootstrap/plugins/respond.min.js"></script>
   <script src="/bootstrap/plugins/excanvas.min.js"></script> 
   <![endif]-->
<script src="/bootstrap/plugins/jquery-migrate-1.2.1.min.js" type="text/javascript"></script><!--迁移 -->
<script src="/bootstrap/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script><!--主要 -->
<script src="/bootstrap/plugins/bootstrap/js/bootstrap2-typeahead.min.js" type="text/javascript"></script>
<script src="/bootstrap/plugins/bootstrap-hover-dropdown/twitter-bootstrap-hover-dropdown.min.js" type="text/javascript" ></script><!--导航下拉-->
<script src="/bootstrap/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script><!--小滚动条-->
<script src="/bootstrap/plugins/jquery.blockui.min.js" type="text/javascript"></script>
<script src="/bootstrap/plugins/jquery.cookie.min.js" type="text/javascript"></script><!--cookie-->
<script src="/bootstrap/plugins/uniform/jquery.uniform.min.js" type="text/javascript" ></script><!--表单-->
<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script type="text/javascript" src="/bootstrap/plugins/fuelux/js/spinner.min.js"></script><!--微调控制项-->
<!--<script type="text/javascript" src="/bootstrap/plugins/bootstrap-fileupload/bootstrap-fileupload.js"></script>--><!--文件上传-->
<script type="text/javascript" src="/bootstrap/plugins/select2/select2.min.js"></script>
<!--<script type="text/javascript" src="/bootstrap/plugins/bootstrap-wysihtml5/wysihtml5-0.3.0.js"></script>--><!--wysihtml5编辑器-->
<!--<script type="text/javascript" src="/bootstrap/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.js"></script>-->
<script type="text/javascript" src="/bootstrap/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script><!--日期选择器 -->
<script type="text/javascript" src="/bootstrap/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js"></script><!--日期时间选择器 -->
<script type="text/javascript" src="/bootstrap/plugins/clockface/js/clockface.js"></script><!--钟面-->
<script type="text/javascript" src="/bootstrap/plugins/bootstrap-daterangepicker/moment.min.js"></script><!--时刻-->
<script type="text/javascript" src="/bootstrap/plugins/bootstrap-daterangepicker/daterangepicker.js"></script><!--日期范围选择器 -->
<script type="text/javascript" src="/bootstrap/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js"></script><!--颜色选择器 -->
<script type="text/javascript" src="/bootstrap/plugins/bootstrap-timepicker/js/bootstrap-timepicker.js"></script><!--时间选择器 -->
<script type="text/javascript" src="/bootstrap/plugins/jquery-inputmask/jquery.inputmask.bundle.min.js"></script><!--输入掩码-->
<script type="text/javascript" src="/bootstrap/plugins/jquery.input-ip-address-control-1.0.min.js"></script><!--IP地址输入限制-->
<script type="text/javascript" src="/bootstrap/plugins/jquery-multi-select/js/jquery.multi-select.js"></script><!--多选下拉-->
<script type="text/javascript" src="/bootstrap/plugins/jquery-multi-select/js/jquery.quicksearch.js"></script><!--快速搜索 -->
<!--<script src="/bootstrap/plugins/jquery.pwstrength.bootstrap/src/pwstrength.js" type="text/javascript" ></script>--><!--判断密码强度-->
<script src="/bootstrap/plugins/bootstrap-switch/static/js/bootstrap-switch.min.js" type="text/javascript" ></script><!--单选开关-->
<script src="/bootstrap/plugins/jquery-tags-input/jquery.tagsinput.min.js" type="text/javascript" ></script><!--标签输入-->
<script src="/bootstrap/plugins/bootstrap-markdown/js/bootstrap-markdown.js" type="text/javascript" ></script><!--减价（文件小）-->
<script src="/bootstrap/plugins/bootstrap-markdown/lib/markdown.js" type="text/javascript" ></script>
<script src="/bootstrap/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js" type="text/javascript" ></script><!--最大长度-->

<script src="/bootstrap/plugins/bootstrap-toastr/toastr.min.js"></script><!--页面中任意位置提示框-->
<script src="/bootstrap/scripts/ui-toastr.js"></script> <!--页面中任意位置提示框-->   
<!-- END PAGE LEVEL PLUGINS -->

<!-- BEGIN PAGE LEVEL PLUGINS -->
<script type="text/javascript" src="/bootstrap/plugins/data-tables/jquery.dataTables.js"></script><!--数据表格-->
<script type="text/javascript" src="/bootstrap/plugins/data-tables/DT_bootstrap.js"></script><!--数据表格-->
<!-- END PAGE LEVEL PLUGINS -->

<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="/bootstrap/scripts/app.js"></script><!--功能菜单缩放-->
<script src="/bootstrap/scripts/form-components.js"></script><!--组件-->
<script src="/bootstrap/scripts/table-managed.js"></script><!--管理-->
<!-- END PAGE LEVEL SCRIPTS -->
<script>
      $(function(){       
         // initiate layout and plugins
         App.init();
         FormComponents.init();//表单组件
		 TableManaged.init();//表管理 
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

</body>
<!-- END BODY -->
</html>