jQuery(function($) {'use strict',

	//#main-slider
	$(function(){
		$('#main-slider.carousel').carousel({
			interval: 8000
		});
	});


	// accordian
	$('.accordion-toggle').on('click', function(){
		$(this).closest('.panel-group').children().each(function(){
		$(this).find('>.panel-heading').removeClass('active');
		 });

	 	$(this).closest('.panel-heading').toggleClass('active');
	});

	//Initiat WOW JS
	new WOW().init();

	// portfolio filter
	$(window).load(function(){'use strict';
		var $portfolio_selectors = $('.portfolio-filter >li>a');
		var $portfolio = $('.portfolio-items');
		$portfolio.isotope({
			itemSelector : '.portfolio-item',
			layoutMode : 'fitRows'
		});
		
		$portfolio_selectors.on('click', function(){
			$portfolio_selectors.removeClass('active');
			$(this).addClass('active');
			var selector = $(this).attr('data-filter');
			$portfolio.isotope({ filter: selector });
			return false;
		});
	});

	// Contact form
	var form = $('#main-contact-form');
	form.submit(function(event){
		
		$(form).find(".form-control").each(function(){
		
				var formelement = $(this).attr("name");
				// if($(this).val()=="")
				// {
					$(this).next("span").html("");
				// }
		});


		$(form).find(".form-control").each(function(){
		
				var formelement = $(this).attr("name");
				if($(this).val()=="")
				{
					$(this).next("span").html("value is required and cant be empty");
				}
		});

		var email = $("#contactemail").val();
		var number = $("#contactnumber").val();

		if(/^(([^<>()\[\]\.,;:\s@\"]+(\.[^<>()\[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/.test(email) == true)
		{
			$("#contactemail").next("span").html("");
			
			if(/[0-9]/.test(number) == true){

					if($("#filesUp").length){

						if($("#filesUp").val()==""){
							$("#filesUp").next("span").html("Please choose a file to be uploaded");
						}
						else {
							ext = $("#filesUp").val().replace(/^.*\./, '');
							// alert(ext);

							if(ext == "pdf" || ext == "doc" || ext == "docx"){
								alert(" we are processing your request press ok to continue:" );
								return true
							}
							else $("#filesUp").next("span").html("Please check file type You are using");
			return false;
						}
							 carrier = true;
					}

					var send = true;
					 carrier=false;
			}
			else 
				$("#contactnumber").next("span").html("number should be numeric");

		}
		else 
		$("#contactemail").next("span").html("email is not valid");

		if(send==true && carrier==false){
		var queryString = form.serialize();
		// event.preventDefault();
		// var form_status = $('<div class="form_status"></div>');
		$.ajax({
			beforeSend: function() {
        // setting a timeout
        		$("#mailsendmsg").show().html("message setting...");
    		},
			url: "http://localhost"+$(this).attr('action'),
			type: "post",
			async: "false",
			data:queryString,
			success:function(data){
				alert(data);
			},
			complete: function() {
        		$("#mailsendmsg").hide();
       		 
    		}
		})
		// alert("true");
	}
	// alert("http://localhost"+$(this).attr('action')+"");
		return false;	
	});

	
	//goto top
	$('.gototop').click(function(event) {
		event.preventDefault();
		$('html, body').animate({
			scrollTop: $("body").offset().top
		}, 500);
	});	

	//Pretty Photo
	$("a[rel^='prettyPhoto']").prettyPhoto({
		social_tools: false
	});	
});


function validfeedform (id) {

	// $("#"+id).find("input[type=radio]").each(function(){
	// 	var elementname = $(this).val();
	// 	// $(this).next()
	// 	alert(elementname);
	// });
// alert(id);
	return true;
}