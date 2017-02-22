<?php echo $this->Form->create('pages', array("class" => "m-t", 'onsubmit' => 'return false')); ?>

<div class="calender_event_input">
    <?php
    echo $this->Form->input('what', array(
        'type' => 'text',
        'required' => true,
        'placeholder' => 'e.g., 7pm Dinner at Pancho',
        'class' => 'coachplayertopinput_righttextbox',
        'label' => false,
        'div' => false,
        'required' => true,
        'error' => array('attributes' => array('wrap' => 'span', 'class' => 'span_error'))
    ));
    ?>
</div>

<?php
echo $this->Form->hidden('cDate', array(
    'value' => @$clickDate,
));
?>

<div class="calender_event_input">
    <?php
    echo $this->Form->input('type', array(
        'type' => 'select',
        'empty' => '-- Type --',
        'options' => array('1' => 'Private Lesson', '2' => 'Group Lesson', '3' => 'Match Event'),
        'class' => 'calender_event_select_box',
        'before' => '<div class="coach_selectdiv"><label class = "select_arrow">',
        'after' => '</label></div>',
        'label' => false,
        'div' => false,
        'required' => true,
        'error' => array('attributes' => array('wrap' => 'span', 'class' => 'span_error'))
    ));
    ?>
</div>

<button  class="calender_event_Schedule_button" type="submit" onclick= "scheduleTime()" >Schedule</button>

<?php echo $this->Form->end(); ?>    

<script type="text/javascript">

	function checkDate() {
		var minutesBet = getDifference();
		if (minutesBet == 0) {
			alert('Please Choose Dates From & To time with atleast 30 minutes gap.');
		} else {
			return true;
		}
	}

	
	function getDifference() {
		var start = $('#LessonOpeningFromTime').datetimepicker('getDate');
		var end = $('#LessonOpeningToTime').datetimepicker('getDate');
		if (end == null) {
			$('#LessonOpeningToTime').click();
		} else {
			// Check for the time
			var diffMin = ((end.getTime() - start.getTime()) / 1000) / 60;
			return diffMin;
		}
		return 0;
	}


	function scheduleTime() {

//		if (!$('#LessonOpeningAddLessonOpeningForm')[0].checkValidity()) {
//			return false;
//		}
		var a = $('#pagesAddLessonOpeningForm').serialize();

		$.ajax({
			url: "<?php echo $this->Html->url(array('controller' => 'pages', 'action' => 'add_lesson_opening')); ?>",
			type: "POST",
			data: a,
			success: function (ret) {
                if (ret == '1') {
					alert('Schedule Created Successfully.');
					window.location.reload();
				} else {
					if (ret == '0') {
						alert('Schedule Cannot be created. Please try again later');
						$('#EventModal').modal('hide');
					} else {
						$('#getScheduleData').html(ret);
					}
				}
			}
		});

	}
</script>