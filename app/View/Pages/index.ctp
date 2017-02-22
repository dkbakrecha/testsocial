<style>
    #event_calendar {
        max-width: 900px;
        margin: 0 auto;
    }

    #content > input[type=button]{
        width: 120px;
    }
</style>

<?php echo $this->Form->create("calenderAdd"); ?>
<?php echo $this->Form->hidden("flag", array('value' => 'insert')); ?>
<?php
echo $this->Form->button(
    'Sync', array(
    'formaction' => Router::url(
        array('controller' => 'pages', 'action' => 'sync')
    )
    )
);
?>
<?php echo $this->Form->submit("INSERT"); ?>
<?php echo $this->Form->end(); ?>

<div id="event_calendar"></div>

<script type="text/javascript">
    $(document).ready(function () {
        
        $('#event_calendar').fullCalendar({
			header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month,basicWeek,basicDay'
			},
            editable : true,
            dayClick : function(date, jsEvent, view) {
                var dt = date.getFullYear() + '-'
                dt += (date.getMonth() + 1 < 10 ? '0' : '') + (date.getMonth() + 1) + '-'
                dt += (date.getDate() < 10 ? '0' : '') + date.getDate();
                addOpening(dt);
			},
            eventClick: function (calEvent, jsEvent, view) {
                getEventDetail(calEvent); // Open Model for event Information
            },
			events: "<?php echo $this->Html->url(array('controller' => 'pages', 'action' => 'getEvents')); ?>",
		});

    });
    
    function addOpening(cdate) {
        $.ajax({
            url: "<?php echo $this->Html->url(array('controller' => 'pages', 'action' => 'add_lesson_opening')); ?>",
            type: "GET",
            data: {cDate: cdate},
            success: function (retData, response) {
                $('#getScheduleData').html(retData);
                $('#EventModal').modal('show');
            }
        });
    }

    function getEventDetail(eData) {
        var lessionID = eData.id;
        alert(lessionID);
        $.ajax({
            url: "<?php echo $this->Html->url(array('controller' => 'lesson_openings', 'action' => 'get_opening_event'), true); ?>/" + lessionID,
            type: "GET",
            success: function (retData, response) {
                $('#getScheduleData').html(retData);
                $('#EventModal').find('#myModalLabel').text("Requests On Lesson");
                $('#EventModal').modal('show');
            }
        });
    }

    function editOpening(LessonOpeningObj) {
        var startevent = Date.parse(LessonOpeningObj.start);
        var now = Date.parse();
        console.log(Date(startevent) + ',' + Date(now));
    }

</script>



<div class="modal fade" id="EventModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	
  <div class="modal-dialog pinpopup">
      
    <div class="modal-content"  id="login_panel">
        
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" id="closeBtn">
            <img src="/tennis/img/front/popupcrossimg.png">
        </button>
        <h4 class="modal-title pintext" id="myModalLabel">Add Lesson Opening</h4>
      </div>
        
      <div class="modal-body" id="getScheduleData">
          
      </div>
    </div>
    
  </div>
</div>




