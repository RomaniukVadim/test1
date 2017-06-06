</body> 
	
    <!-- CHANGE PASSWORD --> 
    <div class="modal fade" id="ChangePassword"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" tabindex="-1" >
        
        <div class="modal-dialog"  >
          
          <div class="modal-content"  >
            
            <div class="modal-header" >
              <button type="button" class="close sugbtn" data-dismiss="modal" aria-hidden="true">&times;</button>
              <h4 class="modal-title"  ><i class="icon20 i-lock-3"></i>Change Password</h4>
            </div>
            
            <!-- tab content -->
            <div style="padding: 20px 20px 20px 20px; " class="ajax_content" > 
              
            </div>
            <!-- end content -->
             
            <?php /*?><div class="modal-footer" > 
              <div id="SuggestionFormLoader" style="float: left; " ></div>
              <button type="submit" class="btn btn-primary sugbtn" id="BtnSubmitSuggestion" >Submit</button>	
              <button type="button" class="btn btn-default sugbtn" id="BtnCloseSuggestion" data-dismiss="modal" >Close</button>
            </div><?php */?>
            
          </div><!-- /.modal-content -->
        
        </div><!-- /.modal-dialog --> 
         
    </div> 
    <!-- END CHANGE PASSWORD --> 
    
    
    <!-- COMMOM MODAL -->
    <div class="modal fade" id="CommonModal"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
        
        <div class="modal-dialog"  >
          
          <div class="modal-content"  >
            
            <div class="modal-header panel-heading" >
              <!--<button type="button" class="close sugbtn" data-dismiss="modal" aria-hidden="true">&times;</button>-->
              <h4 class="modal-title"  ></h4>
            </div>
                        
            <!-- tab content -->
            <div style="padding: 20px 20px 20px 20px; " class="ajax_content center" > 
              	
            </div>
            <!-- end content -->
             
            <div class="modal-footer center" >  
              <button type="button" class="btn btn-danger" id="BtnCancelExport"  >Cancel</button>
            </div>
            
          </div><!-- /.modal-content -->
        
        </div><!-- /.modal-dialog --> 
         
    </div>  
    <!-- END COMMON --> 
    
 
    <!-- ACTIVITY TAB FORM --> 
    <style>
        .suggestions_input {
            width: 100% !important; 	
        }
    </style>
    <div class="modal fade" id="ActivityModal"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" tabindex="-1" >
    	
        <div class="modal-dialog"  >
          
          <div class="modal-content"  >
            
            <div class="modal-header" >
              <button type="button" class="close sugbtn" data-dismiss="modal" aria-hidden="true">&times;</button>
              <h4 class="modal-title"  ><i class="icon20 i-file-8"></i>Manage Activity</h4>
            </div>
            
            <!-- tab and content -->
            <div style="padding: 20px 20px 20px 20px; " id="TabContainer" >
                
                <ul id="myTab" class="nav nav-tabs">
                    <li ><a href="<?=base_url()?>banks/popupManageActivity" data-target="#bank_form" marker="bank_form" data-toggle="tabajax" ><i class="icon14 i-office"></i> Bank</a></li>
                    <li><a href="<?=base_url()?>promotions/popupManageActivity" data-target="#promotions_form" marker="promotions_form" data-toggle="tabajax"><i class="icon14 i-star-2"></i> Promotions</a></li>  
                    <li><a href="<?=base_url()?>casino/popupManageActivity" data-target="#casino_form" marker="casino_form" data-toggle="tabajax"><i class="icon14 i-dice"></i> Casino</a></li> 
                    <li><a href="<?=base_url()?>accounts/popupManageActivity" data-target="#account_form" marker="account_form" data-toggle="tabajax"><i class="icon14 i-vcard"></i> Account</a></li>
                    <li><a href="<?=base_url()?>suggestions/popupManageActivity" data-target="#suggestion_form" marker="suggestions_form" data-toggle="tabajax"><i class="icon14 i-pencil-5"></i> Suggestions</a></li> 
                    <li><a href="<?=base_url()?>access/popupManageActivity" data-target="#access_form" marker="access_form" data-toggle="tabajax"><i class="icon14 i-key-2"></i> Access</a></li>
                    <!--<li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="#dropdown" data-toggle="tab">@fat</a></li>
                            <li><a href="#dropdown" data-toggle="tab">@mdo</a></li>
                         </ul>
                    </li>-->
                </ul>
                
                <div class="tab-content" >
                	<div class="tab-pane" id="bank_form"></div>
                    <div class="tab-pane" id="promotions_form"></div>
                    <div class="tab-pane" id="casino_form"></div>
                    <div class="tab-pane" id="account_form"></div>
                    <div class="tab-pane" id="suggestions_form"></div>
                    <div class="tab-pane" id="access_form"></div>
                </div>
			
            </div>
            <!-- end tab and content -->
            
            <?php /*?><div class="modal-footer" > 
              <div id="SuggestionFormLoader" style="float: left; " ></div>
              <button type="submit" class="btn btn-primary sugbtn" id="BtnSubmitSuggestion" >Submit</button>	
              <button type="button" class="btn btn-default sugbtn" id="BtnCloseSuggestion" data-dismiss="modal" >Close</button>
            </div><?php */?>
            
          </div><!-- /.modal-content -->
        
        </div><!-- /.modal-dialog --> 
        
    </div>
    <script>   
	$(function(){    
		$.fn.modal.Constructor.prototype.enforceFocus = function () {
		 /* var that = this;
		  $(document).on('focusin.modal', function (e) {
			  if ($(e.target).hasClass('select2-input')) {
				return true;
			  }
	
			  if (that.$element[0] !== e.target && !that.$element.has(e.target).length) {
				that.$element.focus();
			  }
		   });*/  
		   
		   // Do nothing if target element is select2 input  
		};  
		 
		
		$('[data-toggle="tabajax"]').click(function(e) { 
			
			if ($(this).hasClass('disabled')) {
				return false;
			}
			 
			tabContent($(this), $(this).attr('href'), $(this).attr('marker'));
			return false;  
		});
		  
		//Boostrap modal
		/*$('#ActivityModal').modal({ 
			show: false, 
			keyboard: false
		});*/
		
		
		$('#CommonModal').on('hide.bs.modal', function (e) {     
			  $(".select2-drop, .select2-drop-mask").hide();  
			  //if(is_change == 1)$(".btn_search").trigger('click'); 
			  //is_change = 0; //global 
			  
		}); 
		
		$("#BtnCancelExport").click(function(){
			 xhr.abort(); 	  
			 $('#CommonModal').modal('hide')	
			 exportLoading("hide");
		}); 
		
		
		//form modal close
		<?php /*?>$('#ActivityModal').on('hide.bs.modal', function (e) {     
			  $(".select2-drop, .select2-drop-mask").hide();  
			  $(".btn_search").trigger('click');
			 	 //$(this).removeData('bs.modal');   
		}); <?php */?>
		
		$( window ).resize(function() {
			$(".select2-drop, .select2-drop-mask").hide();  
		}); 
	  
	});
	</script>

<?php if(is_login() && $this->router->class != 'error'): ?>
<?php
	//&& !super_admin() && $this->session->userdata('mb_no') != 316 check if
	
	$currs = array();
	$currs2 = array();
	
	$res = $this->_currency->getAbbreviation(explode(',', $this->session->userdata('mb_currencies')));
	$res2 = $this->_currency->getAbbreviation($this->config->item('chat_eng'));
	$res3 = $this->_currency->getAbbreviation($this->config->item('chat_kr'));
	
	foreach($res as $c)
		$currs[] = $c->Abbreviation;
	
	foreach($res2 as $c2)
		$currs2[] = $c2->Abbreviation;
	
	foreach($res3 as $c3)
		$currs3[] = $c3->Abbreviation;
	
	$currency_groups = array(
			array(
					'title' => 'ENG',
					'currencies' => $currs2
				),
			array(
					'title' => 'KR',
					'currencies' => $currs3
				)
		);
	
	$not_eng = $currs2;
	$not_kr = $currs3;
	
	array_push($not_eng, 'N/A');
	array_push($not_kr, 'N/A');
	
	$titles = array();
	
	foreach($currency_groups as $v)
		foreach($currs as $k => $v2)
			if(in_array($currs[$k], $v['currencies'])) {
			
				$titles[] = $v['title'];
				
				break;
			}
	
	$currencies = array();
	
	foreach($currs as $user_currency)
		if(!in_array($user_currency, $not_eng) && !in_array($user_currency, $not_kr))
			$currencies[] = $user_currency;
	
	$conditions_array = array('a.Status ='=>'1' ); 
	$groups = $this->manage->createCustomChatGroup_($conditions_array, $this->common->chat_default); 
	 
	$chat = array(); 
	if(count($groups) > 0)$chat = custom_group_chat($groups); 
	 
?>
<script type="text/javascript">
	var SOCKET_LOCAL = '<?=base_url("media/js/chat/socket.io-1.3.6.js")?>';
</script>
<script id="ws-loader" type="text/javascript" src="http://122.53.154.211/ws/loader.js?module=notifs"></script>
<script type="text/javascript">  
var base_url = '<?=base_url()?>';
var user_type = '<?=$this->session->userdata('mb_usertype')?>';
var user_nick = '<?=$this->session->userdata('mb_nick')?>';
var mb_currencies = [<?=$this->session->userdata('mb_currencies')?>];
var user_id = <?=($this->session->userdata('mb_no') ? $this->session->userdata('mb_no') : 0)?>; 
var chat_groups = [];

var titles = <?=json_encode($titles)?>;
var currencies = <?=json_encode($currencies)?>;
var custom_groups = <?=json_encode($chat)?>;

var NOTICE_USER = '<?=strtolower($this->session->userdata('mb_username'))?>',
	NOTICE_DEPT = '<?=$this->session->userdata('mb_deptno')?>',
	NOTICE_KEY = '<?=$this->encrypt->encode($this->session->userdata('mb_username'))?>';
 
 
function createCustomChatGroup(){ 
	$.ajax({ 
		data: $("#search_form").serialize(),
		type:"POST",  
		dataType: "JSON",
		url: "<?=base_url();?>chat_groups/createCustomChatGroup",  
		beforeSend:function(){   
			//show loading 
		},
		success:function(newdata){    
			 $.each(newdata, function (i, val) {  
				//if($.inArray(user_type, val.users) != -1)  
				val.icon = (val.icon)?val.icon:"group"; 
				initChat_UserGroup(val.title, val.icon); 
			 });
		}
			
	}); //end ajax
}
	
	 
	/*var chat_groups = [
			{
				title: 'CRM GROUP',
				icon: 'comments',
				users: [
						'admin',
						'supervisor',
						'crm'
					]
			}
		];*/
	
<?php

	$currs = array();
	$currs2 = array();
	
	$res = $this->_currency->getAbbreviation(explode(',', $this->session->userdata('mb_currencies')));
	$res2 = $this->_currency->getAbbreviation($this->config->item('chat_eng'));
	$res3 = $this->_currency->getAbbreviation($this->config->item('chat_kr'));
	
	foreach($res as $c)
		$currs[] = $c->Abbreviation;
	
	foreach($res2 as $c2)
		$currs2[] = $c2->Abbreviation;
	
	foreach($res3 as $c3)
		$currs3[] = $c3->Abbreviation;
	
	$_currs2 = implode(',', array_map(function ($c) { return "'$c'"; }, $currs2));
	$_currs3 = implode(',', array_map(function ($c) { return "'$c'"; }, $currs3));

?>
		
	var currency_groups = [
			{
				title: 'ENG',
				icon: 'money',
				currencies: [
						// 'AUD',
						// 'GBP',
						// 'EURO',
						// 'MYR',
						// 'USD'
						<?=$_currs2?>
					]
			},
			{
				title: 'KR',
				icon: 'money',
				currencies: [
						<?=$_currs3?>
					]
			}
		];
	
	var not_eng = [<?=$_currs2?>, 'N/A'];
	var not_kr = [<?=$_currs3?>, 'N/A'];

	var user_currencies = new Array(<?=implode(',', array_map(function ($c) { return "'$c'"; }, $currs))?>);  
</script>

<div id="dynamic-files">
	<?php if(isset($css)){foreach($css as $style){ ?>
	<link type="text/css" rel="stylesheet" href="<?=base_url()?>media/css/<?=$style?>" />
        <?php }} ?>

	<?php if(isset($js)){foreach($js as $script){ ?>
	<script type="text/javascript" src="<?=base_url()?>media/js/<?=$script?>"></script>
        <?php }} ?>
</div>

<script type="text/javascript" src="<?=base_url()?>media/js/chat/chat.js?v=4"></script>   
    
<?php endif; ?>
    
</html>