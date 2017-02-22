<div class="row">
	<div class="col-lg-8 col-lg-offset-2">
		<?php
		//pr($question); 
		//$sessionUser = $this->Session->read('Auth.User'); 
		//pr($sessionUser);
		?>

		<?php
		if (!empty($question))
		{
			echo $this->Form->create('Quiz');
			echo $this->Form->hidden('question_id', array('value' => $question['Question']['id']));
			?>

			<h3 class="title"><?php echo $question['Question']['question']; ?></h3>

			<?php
			foreach ($question['Answers'] as $answer)
			{
				//pr($answer);
				$options[$answer['Answers']['id']] = $answer['Answers']['answer'];
			}

			$attributes = array(
				'legend' => false,
				'div' => 'ans_wrap',
				'separator' => '<br/>'
			);
			echo $this->Form->radio('answers', $options, $attributes);
			echo $this->Form->submit("NEXT");
			echo $this->Form->end();
		}
		?>
	</div>
</div>