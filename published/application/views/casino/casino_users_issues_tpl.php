<script  type="text/javascript">
var activity_type = "casino_issues"; 
</script>
 

<style>
#search_form .select2-choice {
	max-width: 180px; 
}
</style>
                        
<div class="widget-content" >  
     <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered table-hover" id="dataTable">
    <thead>
        <tr>    
            <th class="center"  >
               Casino Product	
            </th>
             
            <th class="center" >
               Category Name
            </th>
            
            <th class="center" width="16%" >
               Date Updated
            </th>
             
            <th class="center" width="12%" >
                Status
            </th> 
            
            <th class="center" width="12%" >
                Assignee
            </th>
            
            <th width="10%" class="center" >
                Action 
            </th>
             
        </tr> 

    </thead>
    
    <tbody id="ActivityList" class="dynamic-list" >  
        
    </tbody> 
    
    <tfoot>
        <tr> 
            <th class="center" colspan="9" >
            
            </th> 
        </tr>
    </tfoot>
    </table> 
     
     <!-- pagination -->
    <div class="row-fluid">
        <div class="span4">  
            <div id="dataTable_info" class="dataTables_info"  ><!--Showing 1 to 10 of 58 entries--></div>   
        </div>
        
        <div class="span8" > 
            <div class="dataTables_paginate paging_bootstrap pagination" id="ActivitiyPagination" >
                <?=$pagination?>
            </div> 
        </div>
    </div>
    <!-- end pagination -->
    
    <?php /*?><div class="form-actions">  
        <a href="#ActivityModal" class="btn btn_addactivity"  data-toggle="modal" target-form="casino_form" >
            <i class="icon16  i-stack-plus"></i>
            Add Activity
        </a>  
        
        <?php if(allow_export_data()){ ?>
        <!-- export button -->
        <div class="btn-group dropup rfloat"> 
            <a href="#CommonModal" title="export results" alt="export results" class="btn btn_export tip"  id="ExportBtn"  data-toggle="modal" >
                <i class="icon16 i-file-excel" ></i> Export Results
            </a>
        </div>  
        <!-- end export button -->
        <?php }?>
        
    </div><?php */?>
   
</div>
<!-- End .widget-content -->    

<script>  

function getCasinoActivities(){ 
	 
	$.ajax({ 
		data: $("#activities_form").serialize(),
		type:"POST",  
		dataType: "JSON",
		url: "<?=base_url();?>casino/getUserActivities",  
		beforeSend:function(){   
			//show loading 
			searchLoading("show");   
			$("#ActivityList").find("tr.activity_row").remove();
		},
		success:function(newdata){   
			//alert(JSON.stringify(newdata));
			searchLoading("hide");
			$("#ActivityLoader").remove();  
			$("#ActivityList").find("tr.activity_row").remove();
			$("#ActivityList").append(newdata.activities);
			  
			//for pagination 
			$("#dataTable_info").html(newdata.pagination_string);
			$("#ActivitiyPagination").html(newdata.pagination); 
			
			if(newdata.records > 0)$(".btn_export").show();
			
			$("#ActivitiyPagination li").each(function(index) { 
				if(!$(this).hasClass("active") && $(this).find("a").length > 0)
				 { 
					 $(this).find("a").addClass("pagination_link"); 
					 $(this).find("a").attr("page-num", $(this).find("a").attr("href").replace('/','')); 
				 } 
				$(this).find("a").removeAttr("href");
			});
 			
			$(".pagination_link").click(function(){ 
				$("input[name=s_page]").val($(this).attr("page-num")); 
				getCasinoActivities(); 
			});
			//end pagination 
			$(".tip").tooltip ({placement: 'top'});  
			
			//edit_activity
			$('.activity_row .edit_activity').click(function() {   
				$("html, body").animate({ scrollTop: 0 }, "slow"); 
				var activity_id = $(this).attr('activity-id');  
				tabContent($(this), "<?=base_url()?>casino/popupManageActivity/"+activity_id, "casino_form");  
				$("#TabContainer").find(".nav-tabs li, .nav-tabs li a").addClass("disabled");  
			});
		 	
			//download attachment
			$(".activity_row .download_attachment").click(function(){
				var activity_id = $(this).attr("activity-id");  
				//if(activity_id)downloadAttachment(activity_id, "deposit_withdrawal");
				if(activity_id)window.location.href = "<?=base_url()?>access/downloadAttachment/"+activity_id+"/"+activity_type;
			});  
			
			//change status
			$('.activity_row .change_status').click(function() {
				var activity_id = $(this).attr('activity-id');  
				if(activity_id)loadAjaxContent("<?=base_url()?>casino/popupManageStatusActivity/"+activity_id, $("#ActivityStatusModal").find(".ajax_content"));   
			});
			
			//view details
			$('.activity_row .view_activity').click(function() {
				var activity_id = $(this).attr('activity-id');  
				var default_tab = $(this).attr('target'); 
				if(activity_id)
				 {
					loadAjaxContent("<?=base_url()?>casino/viewActivityDetails/"+activity_id, $("#ActivityDetailsModal").find(".ajax_content"), default_tab); 
				 	$("#ActivityDetailsModal").find(".modal-title").html("<i class=\"icon20 i-dice\"></i>Casino Issue Update Status");  
				 }
				 
			});
			 
		}
			
	}); //end ajax
}

 
$(function() { 	  
	 
	getCasinoActivities();
	 
});  

</script>
 
