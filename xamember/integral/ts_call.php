&raquo; 
<?=LGtag($LG['integral.ts_call_1'],
	'<tag1>=='.$integral_bili.'||'.
	'<tag2>=='.$MCurrencyn.'||'.
	'<tag3>=='.$integral_1.'||'.
	'<tag4>=='.$MCurrencyn.'||'.
	'<tag5>=='.$integral_2.'||'.
	'<tag6>=='.$integral_3.'||'.
	'<tag7>=='.$integral_5.'||'.
	'<tag8>=='.$MCurrencyn
 )?>

<?php
if($Muserid){$userid=$Muserid;}else{$userid=$rs['userid'];}
if($userid)
{
    if(!$integral){$integral=FeData('member','integral',"userid='{$userid}'");}
    if($integral_bili>0){$integral_hb=$integral/$integral_bili;}else{$integral_hb=0;}
    
    LGtag($LG['integral.ts_call_2'],'<tag1>=='.$integral.'||'.
	'<tag2>=='.spr($integral_hb)
     );	
}
?> 
<script>
function show(n)
{
	document.getElementById(n).style.display="block";
}
function hide(n)
{
	document.getElementById(n).style.display="none";
}
</script> 