	</div>
</div>
<?php

//if (isset($tempatch) && $tempatch == 'redirect')
	echo '</div>';

?>
<div id='footer'>
<div id='stuinfo' style="float: left; position: fixed; 
bottom: 30px; background: #000; 
background-image: url('bauble/asfalt.png'); 
border: 1px solid #F63; padding: 5px; visibility:hidden; " > 
 Concept by,<br>
<b>Arunkumar G.</b><br>
 Sr. Lecturer<br><br>
 Desing and Code by,<br>
<b>Sarath Kumar C.U.</b><br>
 Student
 of Batch: 2011-2015.<br>
<!--Department<?php /*&#8211;*/ ?> of Information Technology.-->
</div>
<div id='sirinfo' style="float: left; position: fixed; 
bottom: 30px; background: #000;
background-image: url('bauble/asfalt.png'); 
border: 1px solid #111; padding: 2px; visibility: hidden;"><?php /*
 Sr. Lecturer,<br>
 Department of <?php /*&#8211;* / ?>Information Technology. */ ?>
 &#169; <a style=" color: #F12;">&#126;<b>cuSK &nbsp;</b></a>
</div>
<div id='cuSK' onmouseover='mover2()' onmouseout='mout2()'>&#169; 2014 <a class='foofighter'>AIHT</a></div>
<div class='footcontent'>
Developed and Maintained by &#8212; <!--<a class='foofighter' onmouseover='mover2()' onmouseout='mout2()'> G. Arun Kumar</a>.
Design & Code by &#8212; --><a class='foofighter' onmouseover='mover1()' onmouseout='mout1()'>Department of Information Technology</a>.
</div></div>
<script type='text/javascript'>
function mover1(){
	document.getElementById('stuinfo').style.visibility = 'visible';
}
function mout1(){
	document.getElementById('stuinfo').style.visibility = 'hidden';
}
function mover2(){
	document.getElementById('sirinfo').style.visibility = 'visible';
}
function mout2(){
	document.getElementById('sirinfo').style.visibility = 'hidden';
}
</script>
</body>
</html>