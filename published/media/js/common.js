
/* 

*/ 

function rgb2hex(rgb){
 rgb = rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
 return "#" +
  ("0" + parseInt(rgb[1],10).toString(16)).slice(-2) +
  ("0" + parseInt(rgb[2],10).toString(16)).slice(-2) +
  ("0" + parseInt(rgb[3],10).toString(16)).slice(-2);
}

//SEO FRIENDLY
function name_to_url(name) {
    name = name.toLowerCase(); // lowercase
    name = name.replace(/^\s+|\s+$/g, ''); // remove leading and trailing whitespaces
    name = name.replace(/\s+/g, '-'); // convert (continuous) whitespaces to one -
    name = name.replace(/[^a-z-0-9]/g, ''); // remove everything that is not [a-z] or -
    return name;
}



//URL ENCODE
function urlencode(str) {

    str = (str+'').toString();
    
    return encodeURIComponent(str).replace(/!/g, '%21').replace(/'/g, '%27').replace(/\(/g, '%28').replace(/\)/g, '%29').replace(/\*/g, '%2A').replace(/%20/g, '+');
}


//TRIM FUNCTION 
String.prototype.trim = function() {

a = this.replace(/^\s+/, '');

return a.replace(/\s+$/, '');

};


function replaceAll(find, replace, str) {
  return str.replace(new RegExp(find, 'g'), replace);
}


function fadeOutRedirect(url){
	$("html").fadeOut(1000,function(){ window.location = url; });
}
 
var createMessage = function(container, message, category, callback) { 
	//success, info, error  
	container = (container == "")?$(".widget-content"):container
	$(".widget-content").find(".alert").remove();
	var content = "";
	if(category == "success")
	 {	
	 	 content = '<div class="alert alert-'+category+'"><button class="close" data-dismiss="alert" type="button">×</button><strong><i class="icon24 i-checkmark-circle"></i>Success! </strong>'+message+'</div>'; 
	 } 
	else if(category == "error")
	 {	
	 	 content = '<div class="alert alert-'+category+'"><button class="close" data-dismiss="alert" type="button">×</button><strong><i class="icon24 i-close-4"></i>Error! </strong>'+message+'</div>'; 
	 } 
	else if(category == "alert")
	 {	
	 	 content = '<div class="alert alert-'+category+'"><button class="close" data-dismiss="alert" type="button">×</button><strong><i class="icon24 i-warning"></i>Info! </strong>'+message+'</div>'; 
	 } 
	else if(category == "info")
	 {	
	 	 content = '<div class="alert alert-'+category+'"><button class="close" data-dismiss="alert" type="button">×</button><strong><i class="icon24 i-info"></i>Info! </strong>'+message+'</div>'; 
	 } 
	else if(category == "confirm")
	 {	
	 	 content = '<div class="alert alert-warning"><button class="close" data-dismiss="alert" type="button">×</button><strong>Please Confirm! </strong><p>'+message+'</p><p><a class="btn btn-danger" href="#" id="ConfirmYes" >Confirm</a>&nbsp;<a class="btn" href="#" id="ConfirmCancel" >Cancel</a></p></div><script>$("#ConfirmYes").click('+callback+');$("#ConfirmCancel").click(function(){$(".close").trigger("click")});</script>'; 
	 }  
	 
	container.prepend(content); 
	$(window).scrollTop(0);
	
	
} 


var createMessageMini = function(container, message, category, callback) { 
	//success, info, error   
	container = (container == "")?$(".widget-content"):container
	container.find(".alert").remove();
	var content = "";
	if(category == "success")
	 {	
	 	 content = '<div class="alert alert-'+category+'"><button class="close" data-dismiss="alert" type="button">×</button><strong><i class="icon24 i-checkmark-circle"></i>Success! </strong>'+message+'</div>'; 
	 } 
	else if(category == "error")
	 {	
	 	 content = '<div class="alert alert-'+category+'"><button class="close" data-dismiss="alert" type="button">×</button><strong><i class="icon24 i-close-4"></i>Error! </strong>'+message+'</div>'; 
	 } 
	else if(category == "alert")
	 {	
	 	 content = '<div class="alert alert-'+category+'"><button class="close" data-dismiss="alert" type="button">×</button><strong><i class="icon24 i-warning"></i>Info! </strong>'+message+'</div>'; 
	 } 
	else if(category == "info")
	 {	
	 	 content = '<div class="alert alert-'+category+'"><button class="close" data-dismiss="alert" type="button">×</button><strong><i class="icon24 i-info"></i>Info! </strong>'+message+'</div>'; 
	 } 
	else if(category == "confirm")
	 {	
	 	 content = '<div class="alert alert-warning"><button class="close" data-dismiss="alert" type="button">×</button><strong>Please Confirm! </strong><p>'+message+'</p><p><a class="btn btn-danger" href="#" id="ConfirmYes" >Confirm</a>&nbsp;<a class="btn" href="#" id="ConfirmCancel" >Cancel</a></p></div><script>$("#ConfirmYes").click('+callback+');$("#ConfirmCancel").click(function(){$(".close").trigger("click")});</script>'; 
	 }  
	 
	container.prepend(content);  
	
	$('html, body').animate({
		scrollTop: container.offset().top
	}, 1000);
	
	setTimeout(function() {
		container.find("button.close").trigger("click");
	}, 10000);
	
} 


function readURL(input, container) {  

    if (input.files && input.files[0]) {
        var reader = new FileReader();
		
        reader.onload = function (e) { 
            container.attr('src', e.target.result);
        }
		
        reader.readAsDataURL(input.files[0]);
    }
}


function changeActivityMethods(url, type, default_method, container, display, call){ 
 
	$.ajax({ 
		data: "rand="+Math.random()+"&type="+type,//$(this).serialize(),
		type:"POST",  
		dataType: "JSON",
		url: url,  
		beforeSend:function(){   
			 //$("#CountProcessActivityLoader .header_loader").remove();
			 //$("#CountProcessActivityLoader").append('<img src="<?=base_url()?>media/images/loader.gif" class="header_loader"  />'); 
			 if(type == "")
			  {	
			  	  container.html('<option value="">&nbsp;&nbsp;</option>');
			  	  container.attr('disabled', true); 
				  container.select2({placeholder: "Select"});
				  return false; 
			  }
		},
		success:function(newdata){      
		 	container.html('<option value="">&nbsp;&nbsp;</option>');
		  	if(newdata.length > 0)
			 {  
				container.removeAttr("disabled");
				$.each(newdata, function( index, value ) {   
					selected = (default_method == value.CategoryID)?'selected="selected"':'';
					new_string = '<option value="'+value.CategoryID+'" '+selected+'>'+value.Name+'</option>';  
					container.append(new_string);
				});
				
			 }
		    else
			 {  
				 container.attr('disabled', true); 
			 } 
			 container.select2({placeholder: "Select"});
			 //if(display==1)getActivities(); 
			 
			 $("#act_method").trigger('change'); 
		}
			
	}); //end ajax
}

function changeDepositMethods(url, currency, default_method, container, display){ 
 
	$.ajax({ 
		data: "rand="+Math.random()+"&currency="+currency,//$(this).serialize(),
		type:"POST",  
		dataType: "JSON",
		url: url,  
		beforeSend:function(){   
			 //$("#CountProcessActivityLoader .header_loader").remove();
			 //$("#CountProcessActivityLoader").append('<img src="<?=base_url()?>media/images/loader.gif" class="header_loader"  />'); 
			 if(currency == "")
			  {	
			  	  container.html('<option value=""></option>');
			  	  container.attr('disabled', true); 
				  container.select2({placeholder: "Select"});
				  return false; 
			  }
		},
		success:function(newdata){   
		 	container.html('<option value=""></option>');
		  	if(newdata.length > 0)
			 {  
				container.removeAttr("disabled");
				$.each(newdata, function( index, value ) {   
					selected = (default_method == value.Value)?'selected="selected"':'';
					new_string = '<option value="'+value.Value+'" '+selected+'>'+value.Name+'</option>';  
					container.append(new_string);
				});
				
			 }
		    else
			 {  
				 container.attr('disabled', true); 
			 } 
			 container.select2({placeholder: "Select"});
			 //if(display==1)getActivities(); 
			 
		}
			
	}); //end ajax
}


//ACTIVITY TAB
function tabContentXXXXX(element, loadurl, container) {
	
	if (element.hasClass('disabled')) {
		return false;
	}
	
	/*var $this = tab,
		loadurl = $this.attr('href'),
		targ = $this.attr('data-target');*/ 
		
	//clear 
	$("#ActivityModal").find(".tab-pane").html("");  
	$("#TabContainer").find(".tab-pane").removeClass("active"); 
	$('#TabContainer .nav-tabs li').removeClass("active");
 	
	$(".select2-drop, .select2-drop-mask").hide();
	
	$.get(loadurl, function(data) {   
		$("#"+container).html(data); 
	});
	
	//change border
	//var marker = $(this).attr("marker"); 
	$("#TabContainer").removeClass();//remove all class
	$("#TabContainer").addClass(container); 
	
	$("#TabContainer .tab-content #"+container).addClass("active");
	$('.nav-tabs').find('[marker="'+container+'"]').closest("li").addClass("active");
	
	element.tab('show');
	if(container)disableCurrentTab(container);   
	//return false;
	 
	
}
//END ACTIVITY TAB

//ACTIVITY TAB
function tabContent(element, loadurl, container) {
	
	var user12_id = ""; 
	user12_id = ($("#hidden_a12userid").val())?$("#hidden_a12userid").val():"";
	
	if (element.hasClass('disabled')) {
		return false;
	}
  
	$.ajax({ 
		data: "user12_id="+user12_id, 
		type:"POST",  
		url: loadurl, 
		//dataType: "HTML",  
		cache: false,
		beforeSend:function(){        
			$("#ActivityModal").find(".tab-pane").html("");  
			$("#TabContainer").find(".tab-pane").removeClass("active"); 
			$('#TabContainer .nav-tabs li').removeClass("active");
			
			$(".select2-drop, .select2-drop-mask").hide();    
			$("#"+container).html("<div class=\"center\" ><img src=\""+base_url+"media/images/loader.gif\" class=\"modal_loader\"  /></div>"); 
		},
		success:function(content){    
			$("#"+container).html(content);   
		}
		 
	}); //end ajax 
	
	//change border 
	$("#TabContainer").removeClass();//remove all class
	$("#TabContainer").addClass(container); 
	
	$("#TabContainer .tab-content #"+container).addClass("active");
	$('.nav-tabs').find('[marker="'+container+'"]').closest("li").addClass("active");
	
	element.tab('show');
	if(container)disableCurrentTab(container);
	  
}
//END ACTIVITY TAB
 
function clearActivityTab() {   
	$("#ActivityModal").find(".tab-pane").html("");  
	$("#TabContainer").find(".tab-pane").removeClass("active");  
	$("#TabContainer").removeClass();
	$('#TabContainer .nav-tabs li').removeClass("active"); 
	$("#TabContainer").find(".nav-tabs li, .nav-tabs li a").removeClass("disabled");
}

function disableCurrentTab(target_form) {  
	$("#TabContainer").find(".nav-tabs li, .nav-tabs li a").removeClass("disabled");
	var current_tab =  $("#TabContainer").find(".nav-tabs li a[marker='"+target_form+"']");
	current_tab.addClass("disabled");   
	current_tab.closest("li").addClass("disabled");   
	 
}

var deleteAttachment = function(attach_id, url) { 
	
	$.ajax({ 
		data: "attach_id="+attach_id, 
		type:"POST",  
		url: url, 
		dataType: "JSON",  
		cache: false,
		beforeSend:function(){        
			$("#BtnSubmitForm").addClass("disabled");  
			$("#BtnSubmitForm").attr("disabled", "disabled");    
		},
		success:function(msg){   
			$("#BtnSubmitForm").removeClass("disabled"); 
			$("#BtnSubmitForm").removeAttr("disabled", "disabled"); 
			if(msg.success > 0)
			 {    
				var holder = $(".uploaded-item[attach-id="+attach_id+"]"); 
				holder.hide("200", function() { holder.remove();}); 
			 } 
			 
		}
		 
	}); //end ajax
	   
}  

//load content to modal
var loadAjaxContent = function(url, container, default_tab) { 
	$.ajax({ 
		data: "rand="+Math.random(), 
		type:"POST",  
		url: url,    
		cache: false,
		beforeSend:function(){        
			$(".select2-drop, .select2-drop-mask").hide();
			container.html("");
			$("html, body").animate({ scrollTop: 0 }, "slow");
			//show loading
			container.closest(".modal-content").append("<img src=\""+base_url+"media/images/loader.gif\" class=\"modal_loader\"  />");
		},
		success:function(newdata){   
			container.hide();
			container.html(newdata); 
			container.show(); 
			$(".modal_loader").remove();  
			if(default_tab)$("div.details-tab[target='"+default_tab+"']" ).trigger("click");
		}
		 
	}); //end ajax
	   
}


//change promotion dropdown
function changePromotions(url, product, currency, default_promotion, container, display, category, is_active, is_expired){ 
     
	$.ajax({ 
		//data: "rand="+Math.random()+"&currency="+currency+"&product="+product+"&default="+default_promotion+"&category="+category,//$(this).serialize(), 
		data: {
				rand: Math.random(), 
				currency: (typeof currency == "undefined")?"":currency, 
				product: (typeof product == "undefined")?"":product, 
				default_promotion: default_promotion, 
				category: (typeof category == "undefined")?"":category,  
				is_active: (typeof is_active == "undefined")?1:is_active, 
				is_expired: (typeof is_expired == "undefined")?1:is_expired  
		},
		type:"POST",  
		dataType: "JSON",
		url: url,  
		cache: false,
		beforeSend:function(){   
			 //$("#CountProcessActivityLoader .header_loader").remove();
			 //$("#CountProcessActivityLoader").append('<img src="<?=base_url()?>media/images/loader.gif" class="header_loader"  />');  
			 if(currency == "")
			  {	
			  	  container.html('<option value="">&nbsp;</option><option value="N/A">- All N/A -</option>');
			  	  //container.attr('disabled', true); 
				  container.select2({placeholder: "Select"});
				  return false; 
			  }
		},
		success:function(newdata){     
		 	container.html('<option value="">&nbsp;</option><option value="N/A">- All N/A -</option>');
		  	if(newdata.length > 0)
			 {   
				container.removeAttr("disabled");
				$.each(newdata, function( index, value ) {   					
					var option_class = "";
					if(value.Status != '1')
					 {
					 	option_class = "dark";
					 }
					else
					 {
						 option_class = (value.IsExpire == '1')?"act-danger":"";
					 }
					 
					selected = (default_promotion == value.PromotionID)?'selected="selected"':'';
					//new_string = '<option value="'+value.PromotionID+'"  formula=""   '+selected+'>'+value.Name+'</option>';  
					new_string = "<option value=\""+value.PromotionID+"\" formula=\""+value.Formula+"\" wagering-formula=\""+value.WageringFormula+"\" minimum=\""+value.MinimumAmount+"\"  maximum=\""+value.MaximumAmount+"\" turnover=\""+value.Turnover+"\"  bonus-rate=\""+value.BonusRate+"\"  type=\""+value.Type+"\" "+selected+" start-date=\""+value.StartedDate+"\" end-date=\""+value.EndDate+"\"  class=\""+option_class+"\" >"+value.Name+"</option>";
					container.append(new_string);
				});
				
			 }
		    else
			 {  
				 container.attr('disabled', true); 
			 } 
			 container.select2({placeholder: "Select"});
			 //if(display==1)getActivities(); 
			 
		}
			
	}); //end ajax
}


//change promotion dropdown
function changeProduct(url, product, default_subproduct, container, display){ 
     
	if(typeof product == "undefined")
	  product = "";   
	 
	return $.ajax({ 
		data: "rand="+Math.random()+"&product="+product+"&default="+default_subproduct,//$(this).serialize(),
		type:"POST",  
		dataType: "JSON",
		url: url,  
		cache: false,
		beforeSend:function(){   
			 //$("#CountProcessActivityLoader .header_loader").remove();
			 //$("#CountProcessActivityLoader").append('<img src="<?=base_url()?>media/images/loader.gif" class="header_loader"  />'); 
			 if(product == "")
			  {	
			  	  container.html('<option value="">&nbsp;</option>');
			  	  container.attr('disabled', true); 
				  container.select2({placeholder: "Select"});
				  return false; 
			  }
		},
		success:function(newdata){      
		 	container.html('<option value="">&nbsp;</option>');
		  	if(newdata.length > 0)
			 {  
				container.removeAttr("disabled");
				$.each(newdata, function( index, value ) {   					
					selected = (default_subproduct == value.SubID)?'selected="selected"':''; 
					//new_string = '<option value="'+value.PromotionID+'"  formula=""   '+selected+'>'+value.Name+'</option>';  
					new_string = "<option value=\""+value.SubID+"\"  "+selected+" >"+value.Name+"</option>";
					container.append(new_string);
				});
				
			 }
		    else
			 {  
				 container.attr('disabled', true); 
			 } 
			 container.select2({placeholder: "Select"});
			 //if(display==1)getActivities(); 
			 
		}
			
	}); //end ajax
}

function searchLoading(action, loaderdiv){
	
	loaderdiv = (loaderdiv)?loaderdiv:"ActivityLoader"; 
	$(".popover").remove(); 
	
	if(action == "hide") 
	 {
		$(".btn_search").removeClass("disabled"); 
		$(".btn_search").removeAttr("disabled", "disabled");
		$('.dynamic-list tr').slice(1).remove();  
		$("#dataTable_info").show(); 
		$("#ActivitiyPagination").show(); 
	 }
	else
	 {
		$(".btn_search").addClass("disabled");  
		$(".btn_search").attr("disabled", "disabled"); 
		$('.dynamic-list tr').slice(1).remove();  
		$(".dynamic-list").append("<tr id=\""+loaderdiv+"\" ><td colspan=\"30\" class=\"center\" ><img src=\""+base_url+"media/images/loader.gif\" /> &nbsp; loading... </td></tr>"); 
		$("#dataTable_info").hide(); 
		$("#ActivitiyPagination").hide(); 
		$(".btn_export").hide(); 
		
	 } 
	  
	
	 //slide to top
	 try{
		$('html, body').animate({
			//scrollTop: $("#"+loaderdiv).offset().top 
			 scrollTop: $(".form-horizontal").offset().top
		}, 100); 
	 }catch(e){
		console.log(e);
	 }
}


function exportLoading(action, title, message){
	
	title = (title)?title:"<i class=\"icon20 i-file-excel\"></i>Exporting data... ";
	message = (message)?message:"<img src=\""+base_url+"media/images/preloaders/dark/1.gif?rand="+Math.random()+"\"> <br>Please wait while page is exporting data.";
	
	if(action == "hide") 
	 {
		$(".btn_export").removeClass("disabled"); 
		$(".btn_export").removeAttr("disabled", "disabled");
		$('#CommonModal').modal('hide');	 
	 }
	else
	 {		
		$(".btn_export").addClass("disabled");  
		$(".btn_export").attr("disabled", "disabled");  
		$("#CommonModal").find(".modal-title").html(title);
		$("#CommonModal").find(".ajax_content").html(message);
		
	 }
}


function searchLoadingRight(action, container){
	
	container = (container)?container:$('.widget'); 
 
	if(action == "hide") 
	 {
		$(".btn_search").removeClass("disabled"); 
		$(".btn_search").removeAttr("disabled", "disabled");
		//$('.dynamic-list tr').slice(1).remove(); 
		container.find(".loading-sign").remove();
		//$("#dataTable_info").show(); 
		//$("#ActivitiyPagination").show(); 
	 }
	else
	 { 
		$(".btn_search").addClass("disabled");  
		$(".btn_search").attr("disabled", "disabled"); 
		//$('.dynamic-list tr').slice(1).remove();  
		container.find(".widget-title").append("<div class=\"w-right loading-sign\"><img src=\""+base_url+"media/images/preloaders/dark/1.gif\"></div>");
		//$("#dataTable_info").hide(); 
		//$("#ActivitiyPagination").hide(); 
		$(".btn_export").hide(); 
		
	 }
}


function submitFormImage(container, callback, validExtensions) { 

	if(typeof validExtensions === 'undefined' || validExtensions==""){
	  	validExtensions = ['jpg', 'jpeg', 'gif', 'png'];  
	 };
	var invalid_message = validExtensions.join(", ").toUpperCase(); 
	 
	 
	if(container.val() == "")
	 {  
		callback("xxx"); 
	 }
	else
	 {
		var filename = container.val(); 
        var ext = filename.replace(/^.*\./, ''); 
		if(ext == filename) {
            ext = '';
         } 
		else {
            ext = ext.toLowerCase(); 
        }
		 
		if ($.inArray(ext, validExtensions) == -1){  
			to_upload = 0; 
			upload_error = 1; 
			$('#status').html('<label class="error" >Only ' + invalid_message + ' file(s) are allowed</label>'); 
			return false;
		 }
		else {
			formdata.submit(); // after submit call a function to update or insert data.  
			container.val(""); 
		}
		
	 }
	 
}//end submit form 


//CUSTOM UPLOAD
var customUpload = function(btnUpload, status, url, directory, base_url){ 
	$("input[name=uploadfile]").remove();
	formdata = new AjaxUpload(btnUpload, {
		//action: base_url + 'manage_banners/uploadImage?action=upload_image&directory=' + directory + '&rand=' + Math.random(), 
		action: base_url + url +'?action=upload_image&directory=' + directory + '&rand=' + Math.random(),
		name: 'uploadfile', 
		onChange: function(file, ext){ 
			 if (! (ext && /^(jpg|png|jpeg|gif)$/.test(ext))){ 
				// extension is not allowed 
				to_upload = 0; 
				upload_error = 1; 
				status.html('<label class="error" >Only JPG, PNG or GIF files are allowed</label>'); 
				return false;
			 }
			else{
				status.html('<span >'+file+'</span>');    
			}
			
		},
		onSubmit: function(file, ext){
			 if (! (ext && /^(jpg|png|jpeg|gif)$/.test(ext))){ 
				// extension is not allowed  
				to_upload = 0; 
				upload_error = 1;
				status.html('<label class="error" >Only JPG, PNG or GIF files are allowed12</label>');
				return false;
			}
		    
			status.html('<span class="upload_loading" > &nbsp; </span>');
		},
		autoSubmit: false, 
		onComplete: function(file, response){   
			 alert(file + " - " + response);
			//On completion clear the status 
			var result = response.split("|||");  
			status.html(''); 
			//Add uploaded file to list
			if(result[0]==="success"){
				//$('<li></li>').appendTo('#files').html('<img src="./uploads/'+file+'" alt="" /><br />'+file).addClass('success'); 
				updateProfilePic(result[1]);
			} else{
				status.html('<label class="error" >Only JPG, PNG or GIF files are allowed</label>');
			}
		}
	});	
}
//END CUSTOM UPLOAD 


//CUSTOM UPLOAD USER
var customUploadUser = function(btnUpload, status, url, directory, base_url){ 
	$("input[name=uploadfile]").remove();
	formdata = new AjaxUpload(btnUpload, {
		//action: base_url + 'manage_banners/uploadImage?action=upload_image&directory=' + directory + '&rand=' + Math.random(), 
		action: base_url + url +'?action=upload_image&directory=' + directory + '&rand=' + Math.random(),
		name: 'uploadfile', 
		onChange: function(file, ext){ 
			 if (! (ext && /^(jpg|png|jpeg|gif)$/.test(ext))){ 
				// extension is not allowed 
				to_upload = 0; 
				upload_error = 1; 
				status.html('<label class="error" >Only JPG, PNG or GIF files are allowed</label>'); 
				return false;
			 }
			else{
				status.html('<span >'+file+'</span>');    
			}
			
		},
		onSubmit: function(file, ext){
			 if (! (ext && /^(jpg|png|jpeg|gif)$/.test(ext))){ 
				// extension is not allowed  
				to_upload = 0; 
				upload_error = 1;
				status.html('<label class="error" >Only JPG, PNG or GIF files are allowed</label>');
				return false;
			}
		    
			status.html('<span class="upload_loading" > &nbsp; </span>');
		},
		autoSubmit: false, 
		onComplete: function(file, response){   
			 
			//On completion clear the status 
			var result = response.split("|||");  
			status.html(''); 
			//Add uploaded file to list
			if(result[0]==="success"){
				//$('<li></li>').appendTo('#files').html('<img src="./uploads/'+file+'" alt="" /><br />'+file).addClass('success'); 
				manageUser(result[1]);
			} else{
				status.html('<label class="error" >Only JPG, PNG or GIF files are allowed</label>');
			}
		}
	});	
}
//END CUSTOM UPLOAD USER 


//CUSTOM UPLOAD PROFILE
var customUploadProfile = function(btnUpload, status, url, directory, base_url){ 
	$("input[name=uploadfile]").remove();
	formdata = new AjaxUpload(btnUpload, {
		//action: base_url + 'manage_banners/uploadImage?action=upload_image&directory=' + directory + '&rand=' + Math.random(), 
		action: base_url + url +'?action=upload_image&directory=' + directory + '&rand=' + Math.random(),
		name: 'uploadfile', 
		onChange: function(file, ext){ 
			 if (! (ext && /^(jpg|png|jpeg|gif)$/.test(ext))){ 
				// extension is not allowed 
				to_upload = 0; 
				upload_error = 1; 
				status.html('<label class="error" >Only JPG, PNG or GIF files are allowedxxx</label>'); 
				return false;
			 }
			else{
				status.html('<span >'+file+'</span>');    
			}
			
		},
		onSubmit: function(file, ext){
			 if (! (ext && /^(jpg|png|jpeg|gif)$/.test(ext))){ 
				// extension is not allowed  
				to_upload = 0; 
				upload_error = 1;
				status.html('<label class="error" >Only JPG, PNG or GIF files are allowed12</label>');
				return false;
			}
		    
			status.html('<span class="upload_loading" > &nbsp; </span>');
		},
		autoSubmit: false, 
		onComplete: function(file, response){   
			 
			//On completion clear the status 
			var result = response.split("|||");  
			status.html(''); 
			//Add uploaded file to list
			if(result[0]==="success"){
				//$('<li></li>').appendTo('#files').html('<img src="./uploads/'+file+'" alt="" /><br />'+file).addClass('success'); 
				manageProfile(result[1]);
			} else{
				status.html('<label class="error" >Only JPG, PNG or GIF files are allowed</label>');
			}
		}
	});	
}
//END CUSTOM UPLOAD PROFILE 
 

//CLEAR SELECTBOX
function clearSelectbox(element) {
	//$.uniform.update();
	//$.uniform.restore();  
	element.each(function(i, obj) { 
		$(this).find("select option:first-child").attr("selected", "selected");
		$(this).find(".select2-choice span").text($(this).find("select option:first-child").text());
	});
}

//CUSTOM UPLOAD FILE
var customUploadFile = function(btnUpload, status, url, directory, base_url, validExtensions){ 
 	
	if(typeof validExtensions === 'undefined' || validExtensions==""){
	  	validExtensions = ['jpg', 'jpeg', 'gif', 'png']; 
	 };
	
	var invalid_message = validExtensions.join(", ").toUpperCase();
	
	var valid_list = validExtensions.join("|");  
	var expression = new RegExp("^("+valid_list+")$");
	
	$("input[name=uploadfile]").remove();
	formdata = new AjaxUpload(btnUpload, {
		//action: base_url + 'manage_banners/uploadImage?action=upload_image&directory=' + directory + '&rand=' + Math.random(), 
		action: base_url + url +'?action=upload_image&directory=' + directory + '&rand=' + Math.random(),
		name: 'uploadfile', 
		onChange: function(file, ext){ 
			 if (! (ext && expression.test(ext))){ 
				// extension is not allowed 
				to_upload = 0; 
				upload_error = 1; 
				status.html('<label class="error" >Only ' + invalid_message + ' files are allowed</label>'); 
				return false;
			 }
			else{
				status.html('<span >'+file+'</span>');    
			}
			
		},
		onSubmit: function(file, ext){
			 if (! (ext && expression.test(ext))){ 
				// extension is not allowed  
				to_upload = 0; 
				upload_error = 1;
				status.html('<label class="error" >Only ' + invalid_message + ' files are allowed</label>');
				return false;
			}
		    
			status.html('<span class="upload_loading" > &nbsp; </span>');
		},
		autoSubmit: false, 
		onComplete: function(file, response){   
		 
			//On completion clear the status 
			var result = response.split("|||");  
			status.html('');  
			//Add uploaded file to list 
		 
			if(result[0]==="success"){
				//$('<li></li>').appendTo('#files').html('<img src="./uploads/'+file+'" alt="" /><br />'+file).addClass('success'); 
				managePages(result[1]);
			} else{
				status.html('<label class="error" >Only ' + invalid_message + ' files are allowed</label>');
			}
		}
	});	
}
//END CUSTOM UPLOAD FILE  

 


//DOWNLOAD FILE
function file_download(link, file, base) {
	window.location = base+"ajax/attachment/download/?lnk="+link+"&file="+encodeURIComponent(file);
}
//END DOWNLOAD FILE



//NOTIFICATIONS
var checkNotification = function(count) { 
 	 
	if(count > 0)
	 {
		 $(".for_notification").show();
	 }
	else
	 {
		 $(".for_notification").hide();
	 }
}

var stickyMessage = function(data) {
	setTimeout(function() {
		$.jGrowl(data.message, {
			group: data.group, //'info', 
			header: data.header, //'<i class="icon16 i-notification"></i> Important',
			error: '', 
			life: 3000,
			position: 'bottom-left',
			sticky: false,
			closeTemplate: '<i class="icon16 i-close-2"></i>',
			animateOpen: {
				width: 'show',
				height: 'show'
			}
		});
	}, 2000); 
}

//END NOTIFCATIONS   


var loadAjaxContentForm = function(url, container, form) { 
	$.ajax({ 
		data: form.serialize(),
		type:"POST",  
		url: url,    
		cache: false,
		beforeSend:function(){        
			$(".select2-drop, .select2-drop-mask").hide();
			container.html("");
			$("html, body").animate({ scrollTop: 0 }, "slow");
			//show loading
			container.closest(".modal-content").append("<img src=\""+base_url+"media/images/loader.gif\" class=\"modal_loader\"  />");
		},
		success:function(newdata){   
			container.hide();
			container.html(newdata); 
			container.show(); 
			$(".modal_loader").remove();  
			//if(default_tab)$("div.details-tab[target='"+default_tab+"']" ).trigger("click");
		}
		 
	}); //end ajax
	   
}


String.prototype.ucwords = function() {
    str = this.toLowerCase();
    return str.replace(/(^([a-zA-Z\p{M}]))|([ -][a-zA-Z\p{M}])/g,
        function($1){
            return $1.toUpperCase();
        });
}

function warningDays(obj, start_date, warning_days) {  

    $('input:radio[name=s_dateindex]').prop("checked", false);   
	if(obj.prop("checked") === true)
	 {
		$('input:radio[name=s_dateindex][value=added]').prop("checked", true); 
		$('#reportrange span').html(moment(start_date).format('MMMM DD, YYYY h:mm A') + ' - ' + moment().subtract('days', warning_days).hours(23).minutes(59).seconds(59).format('MMMM DD, YYYY h:mm A')); 		
		$("#s_fromdate").val(moment(start_date).format('YYYY-MM-DD HH:mm:ss')); 
		$("#s_todate").val(moment().subtract('days', warning_days).hours(23).minutes(59).seconds(59).format('YYYY-MM-DD HH:mm:ss'));   
		$("#reportrange").prop('disabled', true);
		$(".daterangepicker div.ranges ul").find("li").removeClass("active"); 
		$(".daterangepicker div.ranges ul").find("li:last-child").trigger("click"); 
		$('input:radio[name=s_dateindex]').closest("label.radio-inline").addClass("hide");
		$('input:radio[name=s_dateindex][value=added]').closest("label.radio-inline").removeClass("hide");
	 } 
	else
	 { 
		$('input:radio[name=s_dateindex][value=updated]').prop("checked", true); 
		//$("body").off( "click", "#reportrange", ''); 
		$("#reportrange").prop('disabled', false);     
		$('input:radio[name=s_dateindex]').closest("label.radio-inline").removeClass("hide");
		
		//$("#reportrange").trigger("click");  
		//$(".daterangepicker div.ranges ul").find("li:last-child").trigger("click"); 
	 }  
	 $.uniform.update("input:radio[name=s_dateindex]");
}


var createRangeDatePicker = function(container, beginning_date) { 
 
	 //Start of the system 2013-09-01 00:00:00 
	container.daterangepicker(
	 {
		
		  ranges: { 
			 'Today': [ moment().hours(0).minutes(0).seconds(0),  moment().hours(23).minutes(59).seconds(59)],
			 'Yesterday': [moment().subtract(1, 'days').hours(0).minutes(0).seconds(0), moment().subtract(1, 'days').hours(23).minutes(59).seconds(59)], 
			 'Last 7 Days': [moment().subtract(6, 'days').hours(0).minutes(0).seconds(0), moment().hours(23).minutes(59).seconds(59)],  
			 'Last 30 Days': [moment().subtract(29, 'days').hours(0).minutes(0).seconds(0),  moment().hours(23).minutes(59).seconds(59)],  
			 'This Month': [moment().startOf('month').hours(0).minutes(0).seconds(0), moment().endOf('month').hours(23).minutes(59).seconds(59)],   
			 'Last Month': [moment().subtract(1, 'month').startOf('month').hours(0).minutes(0).seconds(0), moment().subtract(1, 'month').endOf('month').hours(23).minutes(59).seconds(59)],  
			 'From the beginning': [beginning_date, moment().hours(23).minutes(59).seconds(59)] 
		  }, 
		  //startDate: "<?=$s_fromdate;?>",//moment(),
		  //endDate: "<?=$s_todate;?>",//moment(), 
		  minDate: moment(beginning_date).hours(23).minutes(59).seconds(59),
		  maxDate: moment().hours(23).minutes(59).seconds(59),
		  timePicker: true, 
		  timePickerIncrement: 1, //minutes default 30
		  selected_hour: 24,  
		  format: 'YYYY/MM/DD H:mm:ss', 
		  showDropdowns: true 
	 },
		function(start, end, label)  {  
			if(label == "From the beginning")
			 {	 
				 $('#reportrange span').html(start.format('MMMM DD, YYYY h:mm A') + ' - ' + moment().format('MMMM DD, YYYY 11:59 A'));  
				 $("#s_fromdate").val(""); 
				 $("#s_todate").val("");
			 }
			else
			 {  
				$('#reportrange span').html(start.format('MMMM DD, YYYY h:mm A') + ' - ' + end.format('MMMM DD, YYYY h:mm A'));
				$("#s_fromdate").val(start.format('YYYY-MM-DD HH:mm:ss')); 
				$("#s_todate").val(end.format('YYYY-MM-DD HH:mm:ss'));
			 }
		}
	);
	  
}


