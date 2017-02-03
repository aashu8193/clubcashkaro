$(document).ready(function(){
	$(".task-perform-button > form").submit(function(){
	
		$("#myModal").css("display", "block");
		
		var str = $(this).serialize();
		$.ajax({ 
            type: "POST",
            url: "http://clubcashkaro.com/task.perform.php",
            data: str,  
            success: function(msg){
			    if(msg == 'Success')
				{  
			      $("#modalh1").text("Yippee! Captain Cash has done the Task.Wait for page to reload.");
				  setTimeout(location.reload.bind(location), 3000);
			
			    }
			    else if(msg == 'Manual')
				{ 
			      $("#modalh1").text("Redirecting to Facebook to Perform Task.");
				  window.setTimeout(function(){window.location.href = "https://www.facebook.com";}, 3000);
			    }    
			}
	});	
return false;
});	
});



