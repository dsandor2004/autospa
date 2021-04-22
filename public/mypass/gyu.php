<?php

require_once('dbconnect.php');
   
//1425259736986
//1425262625
//https://freedom15.ro/profile/load-code?code=byjjapfl3&_=1425259736977


//ECHO strtotime('2015-03-07 00:27:00') . '<br>';

//$_SESSION['submitted'] = 0;
?>
<span id="holder"></span>
<input type="text" id="code" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
        <script>
            $(document).ready(function() {
               $("#code").keyup(function() {
				   if ($(this).val().length == 9) {
					$.post('getgy.php', {code : $(this).val() }, function(resp) {
						$("#holder").html(resp);
					});
				} else {
					$("#holder").html('');
				}
               });
            });
        </script>