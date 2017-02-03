$(document).ready(function(){
	$(".task-perform-button > form").submit(function(){
	
		
		var str = $(this).serialize();
		$.ajax({ 
            type: "POST",
            url: "http://clubcashkaro.com/redeem.points.php",
            data: str,
            success: function(msg){
				if(msg == 'Success')
				{ 			
			      $("#myModal").css("display", "block");
				  $("#modalh1").text("Your Reward Request Has Been Successful.");
				  setTimeout(location.reload.bind(location), 2000);			
			    }
			    else if(msg == 'Failed')
				{ 
			      $("#myModal").css("display", "block");
				  $("#modalh1").text("Sorry. You Don't Have Enough Points To Redeem.");
				  setTimeout(location.reload.bind(location), 2000);
			    }    
			}
	});	
return false;
});	
});