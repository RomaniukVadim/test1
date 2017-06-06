<script  type="text/javascript">
var activity_type = "deposit_withdrawal"; 
</script>
       
<div class="widget-content" >      
	
    <!--<div class="row-fluid">  
        
        <div class="span12" >
            <a href="#ActivityModal" class="btn btn_addactivity"  data-toggle="modal" target-form="bank_form" >
               <i class="icon16  i-stack-plus"></i>
               Add Activity
            </a>
        </div>
    
    </div> -->	
    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered table-hover" id="dataTable">
    <thead>
        <tr>  
             
            <th class="center" width="10%" >
                Type
            </th>
            
            <th class="center" >
                Method 
            </th>
            
            <th class="center" width="12%" >
                Transaction ID
            </th>
            
            <th class="center" width="13%" >
                Amount
            </th>
            
            <th class="center" >
                Status
            </th> 
            
            <th class="center" width="13%" >
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
            <th class="left" colspan="10" > </th>  
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
        <!--<a data-toggle="modal" href="#example" class="btn btn-primary btn-large"> - See more at: http://www.w3resource.com/twitter-bootstrap/modals-tutorial.php#sthash.JrP7FH9r.dpuf-->
        <a href="#ActivityModal" class="btn btn_addactivity"  data-toggle="modal" target-form="bank_form" >
            <i class="icon16  i-stack-plus"></i>
            Add Activity
        </a>  
        
        <?php if(allow_export_data()){ ?>
        <!-- export button -->
        <div class="btn-group dropup rfloat"> 
            <a href="#CommonModal" title="export results" alt="export results" class="btn btn_export tip"   data-toggle="modal" >
                <i class="icon16 i-file-excel" ></i> Export Results
            </a>
        </div>  
        <!-- end export button -->
        <?php }?>
        
    </div><?php */?>
   
</div>
<!-- End .widget-content -->

  
<script> 
function getBankActivities(){ 
 
	$.ajax({ 
		data: $("#activities_form").serialize(),
		type:"POST",  
		dataType: "JSON",
		url: "<?=base_url();?>banks/getUserActivities",  
		beforeSend:function(){   
			//show loading 
			searchLoading("show");
			$("#ActivityList").find("tr.activity_row").remove();
		},
		success:function(newdata){     
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
				getBankActivities(); 
			});
			//end pagination 
			$(".tip").tooltip ({placement: 'top'});  
			
			//edit_activity
			$('.activity_row .edit_activity').click(function() {   
				$("html, body").animate({ scrollTop: 0 }, "slow"); 
				var activity_id = $(this).attr('activity-id');    
				tabContent($(this), "<?=base_url()?>banks/popupManageActivity/"+activity_id, "bank_form");  
				$("#TabContainer").find(".nav-tabs li, .nav-tabs li a").addClass("disabled");  
			});
		 	
			//download attachment
			$(".activity_row .download_attachment").click(function(){
				var activity_id = $(this).attr("activity-id");  
				//if(activity_id)downloadAttachment(activity_id, "deposit_withdrawal");
				if(activity_id)window.location.href = "<?=base_url()?>banks/downloadAttachment/"+activity_id+"/"+activity_type;
			});  
			
			//change status
			$('.activity_row .change_status').click(function() {
				var activity_id = $(this).attr('activity-id');  
				if(activity_id)loadAjaxContent("<?=base_url()?>banks/popupManageStatusActivity/"+activity_id, $("#ActivityStatusModal").find(".ajax_content"));   
			});
			
			//view details
			$('.activity_row .view_activity').click(function() {
				var activity_id = $(this).attr('activity-id');  
				if(activity_id)
				 {
					 loadAjaxContent("<?=base_url()?>banks/viewActivityDetails/"+activity_id, $("#ActivityDetailsModal").find(".ajax_content"));   
					 $("#ActivityDetailsModal").find(".modal-title").html("<i class=\"icon20 i-office\"></i>Deposit/Withdrawal Activity Details");  
				 }
			});
			 
		}
			
	}); //end ajax
}
 
 
$(function() { 	 
 	  
	getBankActivities();
	  
}); 
</script>

  

