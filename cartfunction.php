<?php
$user = JFactory::getUser();
$field_id = $_POST['source1'];
$field_value = $_POST['source1'];
$profile = new stdClass();
			$profile->user_id = $user->id;
			$profile->ip = $_SERVER['REMOTE_ADDR'];
			
			$profile->field_id = $field_id;
			$profile->value = $field_value;

		
			 
			// Insert the object into the user profile table.
			$result = JFactory::getDbo()->insertObject('#__user_filter', $profile);