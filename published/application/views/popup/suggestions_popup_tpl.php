<script src="<?=base_url();?>media/js/plugins/forms/validation/jquery.validate.js"></script>
<!-- SUGGESTIONS FORM --> 
<style>
	.suggestions_input {
		width: 100% !important; 	
	}
</style>

	<div class="modal-dialog">
	  <div class="modal-content">
		<div class="modal-header">
		  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		  <h4 class="modal-title"  ><i class="icon20 i-bubble-12"></i>Suggestions/Comments</h4>
		</div>
		
	   <form class="form-horizontal pad15 pad-bottom0" role="form" name="suggestion_form" id="suggestion_form" style="margin: 0px !important; padding: 0px !important;  " >
		<div class="modal-body">
			
			<div class="form-groupx">
				<input id="suggestion_subject" name="suggestion_subject" class="suggestions_input required"  type="text" placeholder="Subject" minlength="10" >
			</div><!-- End .form-group  -->
			
			<div class="form-groupx">
				<textarea name="suggestion_message" id="suggestion_message" class="suggestions_input required" rows="6" placeholder="Type your suggestions/comments here ..." maxlength="250" minlength="20" ></textarea>
			</div><!-- End .form-group  --> 
			  
		</div>
		<div class="modal-footer">
		  <button type="submit" class="btn btn-primary">Submit</button>	
		  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		</div>
		</form> 
		
	  </div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
 

<script>
$(function(){  
	//Boostrap modal
	$('#suggestionsForm').modal({ 
		show: false, 
		keyboard: false
	});
	
	$("#suggestion_form").validate({
		 submitHandler: function(form) {  
			alert("submit");
		},
		ignore: null,
		//ignore: 'input[type="hidden"]',
		rules: {
			suggestion_subject: {
				required: true 
			}, 
			suggestion_message: "required" 
		},
		messages: {
			suggestion_subject: {
				required: "Please provide subject"
			}, 
			suggestion_message: {
				required: "Please provide message"
			}
			
		}
	}); 
 
});
</script>