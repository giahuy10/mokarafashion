<?php 
$session = JFactory::getSession();
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

 <script type="text/javascript">
SubmitForm();			
 function SubmitForm(){
 $(document).ready(function() {
		 var favorite = [];
		$.each($("input:checked"), function(){            
                favorite.push($(this).val());
            });
		var url = "index.php?option=com_inventory&task=ajax&format=raw";		
		 $.post(url, {suggest: JSON.stringify(favorite)}, function(result){
			$("#products_list").html(result);
		});
	});
}

</script>



<?php function get_list_tags ($parent_id) {
	
	// Get a db connection.
			$db = JFactory::getDbo();
			 
			// Create a new query object.
			$query = $db->getQuery(true);
			 
			// Select all records from the user profile table where key begins with "custom.".
			// Order it by the ordering field.
			$query->select($db->quoteName(array('id', 'title')));
			$query->from($db->quoteName('#__tags'));

			$query->where($db->quoteName('published') . ' = '. $db->quote('1'));
			$query->where($db->quoteName('parent_id') . ' = '. $parent_id);
			$query->order('lft ASC');
			 
			// Reset the query using our newly populated query object.
			$db->setQuery($query);
			 
			// Load the results as a list of stdClass objects (see later for more options on retrieving data).
			$results = $db->loadObjectList();
			return ($results);
	
}?>
<div class="row">
	<div class="col-xs-12 col-sm-9">
		<div id="products_list">
		</div>
	</div>
	<div class="col-xs-12 col-sm-3">
		<?php 
			$fields = get_list_tags(33);
			foreach ($fields as $field) {
				echo "<h3>$field->title</h3>";
				$filter = get_list_tags($field->id);
				foreach ($filter as $option) {
				?>
				
				
				<input class="hidden" onchange="SubmitForm()" type="radio" name="field_<?php echo $field->id?>" value="<?php echo $option->id?>" id="field_<?php echo $field->id."_".$option->id?>" <?php if ($option->id == $value) echo "checked";?>/>
				<label for="field_<?php echo $field->id."_".$option->id?>" class="<?php //if (!$existed) echo "hidden"?>"  >
				
				<span class="<?php echo 'btn btn_field btn_field_'.$field->id.' btn_field_value_'.$option->id?> <?php if ($option->id == $value) echo "active";?>"><?php echo $option->title?></span>
				
				</label>
			<?php } }
		?>
		<input type="reset" id="save_value" name="save_value" value="reset" />

	</div>
</div>
<style>
input[type=radio]:checked + label{
 background:red;
}
</style>