$(function () {

// scroll with animate
	$('.navbar-nav li a').click(function(){

		$('html, body').animate({

			scrollTop: $("#" + $(this).data('value')).offset().top 

		},1500);

	})

		
	// Adults
	$("#plus_one").click(function(){
		oldValue = $("#Adults").val()
		if (oldValue >= 0 ) {
			newValue	= parseFloat(oldValue) + 1;
			$("#Adults").val(newValue);
		}
	}) 
	$("#menus_one").click(function(){
		oldValue = $("#Adults").val()
		if (oldValue >= 1 ) {
			newValue	= parseFloat(oldValue) - 1;
			$("#Adults").val(newValue);
		}
	}) 
	// Children
	$("#plus_one_Children").click(function(){
		oldValue = $("#Children").val()
		if (oldValue >= 0 ) {
			newValue	= parseFloat(oldValue) + 1;
			$("#Children").val(newValue);
		}
	}) 
	$("#menus_one_Children").click(function(){
		oldValue = $("#Children").val()
		if (oldValue >= 1 ) {
			newValue	= parseFloat(oldValue) - 1;
			$("#Children").val(newValue);
		}
	}) 
	// Baby
	$("#plus_one_Baby").click(function(){
		oldValue = $("#Baby").val()
		if (oldValue >= 0 ) {
			newValue	= parseFloat(oldValue) + 1;
			$("#Baby").val(newValue);
		}
	}) 
	$("#menus_one_Baby").click(function(){
		oldValue = $("#Baby").val()
		if (oldValue >= 1 ) {
			newValue	= parseFloat(oldValue) - 1;
			$("#Baby").val(newValue);
		}
	}) 

	// 
	$(document).on('click', '.slide_item',function() {
		let id = $(this).data('id');
		$('#desc-my-offer-'+id).toggle(),
		$(this).find(" .fa-arrow-down , .fa-arrow-up ").toggleClass(" fa-arrow-up  fa-arrow-down"),
		
		$('#desc-my-offer-'+id).click(function(e) {
			e.stopPropagation()
		})
	})

	$(".show_input_reject").click(function(){
		id = $(this).data('id');
		$("#Veiw_input_show_input-"+id).show(),
		$("#Veiw_input_show_chatting-"+id).hide();
		$("#Veiw_input_show_chatting-"+id+"-"+id).hide();
	})

	$(".show_input_chat").click(function(){
		id = $(this).data('id');
		$("#Veiw_input_show_chatting-"+id).show(),
		$("#Veiw_input_show_chatting-"+id+"-"+id).show();
		$("#Veiw_input_show_input-"+id).hide();
	})

	$("#show-input").click(function() {
		$(".big-info").toggle();
	});

	$(document).ready(function(){
		$('.rate-code').on('click','li', function(){
		  
		   //save cliked item
			 var self = $(this);
			 console.log(self.data('id'));
		   //save its number 
			 var mark = self.index() + 1;
		   //paint all previous items
			 self.addClass('active').prevAll().addClass('active');
			//remove color from all next items
			 self.nextAll().removeClass('active');
			//put item number into hide input
			 self.parents().find('input[name="RATING"]').attr('value', mark);
			// here prints the clicked icon value  
			 $.ajax({
				type: 'GET',
				url : url + '/' + self.data('id'),
				data : {rate:self.attr('value')},
				success : function(){
	
				},
				error : function(){
	
				}
			});
			 return false;
		   });
	   });

})