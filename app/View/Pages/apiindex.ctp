<div class="porpertyListing">
<?php
//pr($listingData);
if(isset($listingData->error)){
	echo $listingData->error->message;
}elseif(isset($listingData->status) && $listingData->status == 1){
	if(!empty($listingData->result)){
		foreach($listingData->result as $b_list){
			?>
			<div>
				<div id="<?php echo $b_list->ID; ?>" class="ids"></div>
				[ <?php echo $b_list->ID; ?> ] <b><?php echo $b_list->Headline; ?></b>
				
				<br>
				<?php echo $b_list->Location; ?>
				<br>
				<?php echo json_encode($b_list->TopAmenities); ?>
				<br>
				Bedrooms : <?php echo $b_list->Bedrooms; ?> | Bathrooms : <?php echo $b_list->Bathrooms; ?> | Status : <?php echo $b_list->Status; ?>
			</div>
			<?php
		}
	}
}
?>
</div>

<div aria-hidden="true" id="popup_addbooking" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" class="modal fade" style="display: none;"></div>

<script type="text/javascript">
    $(document).ready(function () {
       //Add Product model
		$(".porpertyListing").on("click", ".openBookingForm", function () {
			//console.log(this);
			
			//var prop_id = $(".pinD").find("#storeshopid").val();
			var prop_id = $(this).data("prop_id");
			
			$.ajax({
				url: '<?php echo $this->Html->url(array("controller" => "pages", "action" => "addbooking"), true); ?>',
				type: "GET",
				data: {prop_id: prop_id},
				success: function (data) {
					try {
						var pd = $.parseJSON(data);
						if (pd.status == 0) {
							alert(pd.msg);
						}
					} catch (e) {
						$("#popup_addbooking").html(data);
						$("#popup_addbooking").modal('show');
					}
				},
				error: function (xhr) {
					ajaxErrorCallback(xhr);
				}
			});
		});
		
		//submit form product add
		$("#popup_addbooking").on("submit", "#BookingAddbookingForm", function () {
			$(this).ajaxSubmit({
				success: function (rd) {
					try {
						var resData = $.parseJSON(rd);
						if (resData.status == 0) {
							alert(resData.msg);
						} else {
							$("#popup_addbooking").html(resData);
							$("#popup_addbooking").modal('show');
							//$("#popup_addbooking").modal('hide');
							//location.reload();
						}
					} catch (e) {
						$("#popup_addbooking").html(rd);
						$("#popup_addbooking").modal('show');
					}
				},
				error: function (xhr) {
					ajaxErrorCallback(xhr);
				}
			});
			return false;
		});
    });
</script>