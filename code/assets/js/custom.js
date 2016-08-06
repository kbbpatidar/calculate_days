$(function() {
	/* jQuery Datepicker to select date */
	$("#start_date").datepicker({
		changeMonth : true,
		changeYear : true,
		altField : "#start_alternate",
		altFormat : "DD, d MM, yy",
		dateFormat: 'yy-mm-dd',
		showAnim: 'slide'
	});

	$("#end_date").datepicker({
		changeMonth : true,
		changeYear : true,
		altField : "#end_alternate",
		altFormat : "DD, d MM, yy",
		dateFormat: 'yy-mm-dd',
		showAnim: 'slide'
	});
	/* jQuery Datepicker to select date */
	
	/* When user clicks on Calculate button, this below function will call the ajax and make calculations */
	$('form.jsform').on('submit', function(e){
	    e.preventDefault();
	    
	    /* FOR METHOD 2 ONLY */
	    var start_date_timestamp = $("#start_date").datepicker('getDate') / 1000;
		var end_date_timestamp = $("#end_date").datepicker('getDate') / 1000;
		/* FOR METHOD 2 END ONLY */
		
	    $.ajax({
	      url: '/datediff/validate',
	      type: 'post',
	      data: {'action': 'calculate', 
	    	  	'start_date': $("#start_date").val(),
	    	  	'start_date_timestamp': start_date_timestamp,		/* Passing this parameter to get result using METHOD 2, IF WE NEED TO GO WITH METHOD 1 THEN REMOVE THIS LINE. */
	    	  	'end_date': $("#end_date").val(),
	    	  	'end_date_timestamp': end_date_timestamp,			/* Passing this parameter to get result using METHOD 2, IF WE NEED TO GO WITH METHOD 1 THEN REMOVE THIS LINE. */
	    	  	'include_day': $("#include_day").is(':checked') ? 1 : 0
	    	  	},
	      dataType:'json',											/* Get response of ajax in json format */	  	
	      success: function(data) {
	    	  
	    	  $('#result').html('');								/* Remove older content in result div to append new content as per the result. */
	    	  
	    	  $('.jsError, #invert-warning').html('').removeClass('show').addClass('hide');
	    	  
	    	  /* IF response status is true. */
	    	  if (data.status == true) {	
	    		
	    		  /* for warning message when The Start date was after the End date */
	    		  if (data.result.invert == true) {	
		    			$('#invert-warning').removeClass('hide').addClass('show');
	    		  }
		    			
	    		  /* Create html content for result and set it to result div. */
	    		  var result_div = '';
	    		  result_div = "<div>";
	    		  if (data.include_day == true) {
	    			
	    			result_div += "<p>" +
    		  		"From and including: " +
    		  		"<strong>"+ $('#start_alternate').val() +"</strong>" +
    		  		"<br>To and including: <strong>" + $('#end_alternate').val() + "</strong>" +
    		  		"</p>";
	    		  }
	    		  else {
	    			  result_div += "<p>" +
	    		  		"From and including: " +
	    		  		"<strong>"+ $('#start_alternate').val() +"</strong>" +
	    		  		"<br>To and but not including: <strong>" + $('#end_alternate').val() + "</strong>" +
	    		  		"</p>";
	    		  }
	    		  
	    		  result_div += "<br><i class='glyphicon glyphicon-hand-right'></i>  " +
	    		  		"	<strong class='resultdays'>Result: " + data.result.days + " day(s) </strong>  " +
	    		  		"	<i class='glyphicon glyphicon-hand-left'></i>";
	    		  
	    		  /* Uncomment below code if we need to give Months and Years */ 
	    		  
	    		  /*if (data.result.m > 0) {
	    			  result_div += "<br><strong>" + data.result.m + " months </strong>";
	    			  
	    			  if (data.result.d > 0) {
	    				  result_div += "& <strong> " + data.result.d + " days </strong>";
	    			  }
	    		  }
	    		  if (data.result.y > 0) {
	    			  result_div += "<br><strong>" + data.result.y + " years </strong>";  
	    		  }*/
	    		  
	    		  
	    		  /* If selected dates are same then give user a message */
	    		  if (data.result.days == 0) {
	    			  result_div += "<br> <span class='text-danger'>Note: Looks like date are the same.</span>"
	    		  }
	    		  
	    		  result_div += "</div>";
	    		  $('#result').html(result_div).removeClass('hide').addClass('show');
	    	  }
	      },
	      error: function(xhr, desc, err) {
	        console.log(xhr);
	        console.log("Details: " + desc + "\nError:" + err);
	        
	        /* SHOW ERROR(S) TO USER */
	        $('.jsError').html(xhr.responseText).removeClass('hide').addClass('show');
	        
	      }
	    }); /* End ajax call */
	});
	
});