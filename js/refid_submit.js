$(document).ready(function(){
	$("#reg-left > form").submit(function(){
		var str = $(this).serialize();
		$.ajax({
            type: "POST",
            url: "http://clubcashkaro.com/refid_submit.php",
            data: str,  
            success: function(msg){ 
				if(msg == 'Success')
				{  window.location.reload();}
			    else if(msg == 'Failed')
				{ $('#register-error').html("<span style='color:#cc0000'>Email Id Already Exists!</span>");}
			}
	});	
return false;
});	
});