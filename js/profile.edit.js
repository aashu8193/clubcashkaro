$(document).ready(function(){
	$(".profile-sec > form").submit(function(){
		var str = $(this).serialize();
		$.ajax({ 
            type: "POST",
            url: "http://clubcashkaro.com/profile.edit.php",
            data: str,
            success: function(msg){
				if(msg == 'Success')
				{ 			
			      window.location.reload();		
			    }   
			}
	});	
return false;
});	
});