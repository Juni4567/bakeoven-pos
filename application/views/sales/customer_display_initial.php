<?php $this->load->view("partial/header"); 
$company = ($company = $this->Location->get_info_for_key('company', isset($override_location_id) ? $override_location_id : FALSE)) ? $company : $this->config->item('company');
$website = ($website = $this->Location->get_info_for_key('website', isset($override_location_id) ? $override_location_id : FALSE)) ? $website : $this->config->item('website');
$company_logo = ($company_logo = $this->Location->get_info_for_key('company_logo', isset($override_location_id) ? $override_location_id : FALSE)) ? $company_logo : $this->config->item('company_logo');
?> 
<div id="sales_page_holder">
	<?php
	if (!$this->agent->is_mobile() || $this->agent->is_tablet()) { 
		
	?>
	<div id="announcement" class="col-md-6 col-sm-6 col-xs-6 text-left">
		<h4><?php echo nl2br($this->config->item('announcement_special')) ?></h4>
	</div>
	
	
    <div class="col-md-6 col-sm-6 col-xs-6 text-right">
        <ul class="list-unstyled" style="margin-bottom:2px;">
            <?php if($company_logo) {?>
            	<li class="company-title">
									<?php echo img(array('src' => $this->Appfile->get_url_for_file($company_logo))); ?>
            	</li>
            <?php } ?>
            <li class="company-title"><h4><?php echo H($company); ?></h4></li>
						<?php if ($website) { ?>
           	 <li class="company-title"><?php echo H($website); ?></li>
						<?php } ?>
        </ul>
    </div>
	<?php } ?>
		<div id="digital_sig_holder" style="display: none;">
			<h2><?php echo lang('sales_signature');?></h2>
			<canvas id="sig_cnv" name="sig_cnv" class="signature" width="500" height="100"></canvas>
			<div id="sig_actions_container" class="pull-right">
					<button class="btn btn-default btn-radius btn-lg hidden-print" style="font-size:18px" id="capture_digital_sig_clear_button"> <?php echo lang('sales_clear_signature'); ?> </button>
					<button class="btn btn-primary btn-radius btn-lg hidden-print" style="font-size:18px" id="capture_digital_sig_done_button"> <?php echo lang('sales_done_capturing_sig'); ?> </button>
			</div>
			<div id="digital_sig_holder_signature">
			</div>
		</div>
		
		
	<div id="customer_display_container" class="sales clearfix">
	  <?php $this->load->view("sales/customer_display"); ?>
	</div>
</div>

<script>
var sale_id = false;
customer_display_update();

function customer_display_update()
{
	$("#customer_display_container").load('<?php echo site_url('sales/customer_display_update/'.$register_id); ?>', function()
	{
		$.get('<?php echo site_url('sales/customer_display_info/'.$register_id); ?>', function(json)
		{
			if (json.sale_id)
			{
				sale_id = json.sale_id;
				if (json.signature_needed)
				{
					$("#digital_sig_holder").show();
				}
			}
			else
			{
				$("#digital_sig_holder_signature").empty();
				$("#digital_sig_holder").hide();
			}
			
			setTimeout(customer_display_update, 1000);	
		
		},'json').fail(function() 
		{
			setTimeout(customer_display_update, 1000);	
		});
	});
}
$(document).on('click', "#email_receipt",function()
{
	$.get($(this).attr('href'), function()
	{
		show_feedback('success', <?php echo json_encode(lang('common_receipt_sent')); ?>, <?php echo json_encode(lang('common_success')); ?>);
		
	});
	
	return false;
});

$(document).ready(function(){

	$(window).load(function()
	{
		setTimeout(function()
		{
			salesRecvFullScreen();
		}, 0);
	});
});


var sig_canvas = document.getElementById('sig_cnv');
var signaturePad = new SignaturePad(sig_canvas);

$("#capture_digital_sig_button").click(function()
{	
	signaturePad.clear();	
	$("#capture_digital_sig_button").hide();
});

$("#capture_digital_sig_clear_button").click(function()
{
		signaturePad.clear();
});

$("#capture_digital_sig_done_button").click(function()
{
		SigImageCallback(signaturePad.toDataURL().split(",")[1]);
		$("#capture_digital_sig_button").show();
});

function SigImageCallback( str )
{
	if (sale_id)
	{
		$.post('<?php echo site_url('sales/sig_save/'.$register_id); ?>', {sale_id: sale_id, image: str}, function(response)
		{
	 	 $("#digital_sig_holder_signature").html('<img src="'+SITE_URL+'/app_files/view/'+response.file_id+'?timestamp='+response.file_timestamp+'" width="250" />');
 			signaturePad.clear();
		}, 'json');
	}
	else
	{
		bootbox.alert(<?php echo json_encode(lang('sales_cannot_sign')); ?>);
		signaturePad.clear();
	}

}

</script>

<?php $this->load->view("partial/footer"); ?>
