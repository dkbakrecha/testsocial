<?php
$reqData = $this->request->data;

$title = __("Add Booking");
$btnTitle = "Add";
?>
<div class="modal-dialog pinpopup">
    <div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
			<h4 class="modal-title pintext" id="myModalLabel"><?php echo __($title); ?></h4>
		</div>
		<div class="modal-body">
			<?php
			echo $this->Form->create("Booking", array('url' => array('controller' => 'pages', 'action' => 'addbooking'), 'onsubmit' => 'return saveBooking()'));
			echo $this->Form->hidden('prop_id', array('value' => $prop_id));
			echo $this->Form->input('FirstName');
			echo $this->Form->input('LastName');
			echo $this->Form->input('PrimaryEmail');

			echo $this->Form->input('CheckIn');
			echo $this->Form->input('CheckOut');

			echo $this->Form->submit("Add Booking");
			echo $this->Form->end();
			?>
		</div>
	</div>
</div>	
<script type="text/javascript">
    function saveBooking() {
        var _prop_id = $("#BookingPropId").val();
        var _first_name = $("#BookingFirstName").val();
        var _last_name = $("#BookingLastName").val();
        var _email = $("#BookingPrimaryEmail").val();
        
		var data = {
            "PropertyID": _prop_id,
            "CheckIn": "2015-12-20",
            "CheckOut": "2015-12-25",
            "Renter": {
                "FirstName": _first_name,
                "LastName": _last_name,
                "PrimaryEmail": _email
            }
        }

        //console.log(data)
		/*
        $.ajax({
            url: "http://connect.bookt.com/ws/?method=save&entity=booking&apikey=<?php //echo $apiKey; ?>",
            type: "GET",
            contentType: "application/x-www-form-urlencoded",
            data: {data: data},
            success: function (response) {
                console.log(response);
            }
        });
		*/
        return true;
    }

    function goBack() {
        $("#popup_addbooking").modal('hide');
    }
	
	
</script>