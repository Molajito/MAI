<?php
class NHP_Options_post_type_select extends NHP_Options{	
	
	/**
	 * Field Constructor.
	 *
	 * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
	 *
	 * @since NHP_Options 1.0.1
	*/
	function __construct($field = array(), $value ='', $parent){
		
		parent::__construct($parent->sections, $parent->args, $parent->extra_tabs);
		$this->field = $field;
		$this->value = $value;
		//$this->render();
		
	}//function
	
	
	
	/**
	 * Field Render Function.
	 *
	 * Takes the vars and outputs the HTML for the field in the settings
	 *
	 * @since NHP_Options 1.0.1
	*/
	function render(){
		
		$class = (isset($this->row['class']))?'class="'.$this->row['class'].'" ':'';
		
		echo '<select id="'.$this->row['id'].'" name="'.$this->args['opt_name'].'['.$this->row['id'].']" '.$class.' >';
		
		if(!isset($this->row['args'])){$this->row['args'] = array();}
		$args = wp_parse_args($this->row['args'], array('public' => true));
			
		$post_types = get_post_types($args, 'object'); 
		foreach ( $post_types as $k => $post_type ) {
			echo '<option value="'.$k.'"'.selected($this->value, $k, false).'>'.$post_type->labels->name.'</option>';
		}
		echo '</select>';

		echo (isset($this->row['desc']) && !empty($this->row['desc']))?' <span class="description">'.$this->row['desc'].'</span>':'';
		
	}//function
	
}//class
?>