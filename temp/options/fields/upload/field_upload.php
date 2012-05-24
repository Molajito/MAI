<?php
class NHP_Options_upload extends NHP_Options{	
	
	/**
	 * Field Constructor.
	 *
	 * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
	 *
	 * @since NHP_Options 1.0
	*/
	function __construct($field = array(), $value ='', $parent = ''){
		
		parent::__construct($parent->sections, $parent->args, $parent->extra_tabs);
		$this->field = $field;
		$this->value = $value;
		
	}//function
	
	
	
	/**
	 * Field Render Function.
	 *
	 * Takes the vars and outputs the HTML for the field in the settings
	 *
	 * @since NHP_Options 1.0
	*/
	function render(){
		
		$class = (isset($this->row['class']))?$this->row['class']:'regular-text';
		
		
		echo '<input type="hidden" id="'.$this->row['id'].'" name="'.$this->args['opt_name'].'['.$this->row['id'].']" value="'.$this->value.'" class="'.$class.'" />';
		//if($this->value != ''){
			echo '<img class="nhp-opts-screenshot" id="nhp-opts-screenshot-'.$this->row['id'].'" src="'.$this->value.'" />';
		//}
		
		if($this->value == ''){$remove = ' style="display:none;"';$upload = '';}else{$remove = '';$upload = ' style="display:none;"';}
		echo ' <a href="javascript:void(0);" class="nhp-opts-upload button-secondary"'.$upload.' rel-id="'.$this->row['id'].'">'.__('Browse', 'nhp-opts').'</a>';
		echo ' <a href="javascript:void(0);" class="nhp-opts-upload-remove"'.$remove.' rel-id="'.$this->row['id'].'">'.__('Remove Upload', 'nhp-opts').'</a>';
		
		echo (isset($this->row['desc']) && !empty($this->row['desc']))?'<br/><br/><span class="description">'.$this->row['desc'].'</span>':'';
		
	}//function
	
	
	
	/**
	 * Enqueue Function.
	 *
	 * If this field requires any scripts, or css define this function and register/enqueue the scripts/css
	 *
	 * @since NHP_Options 1.0
	*/
	function enqueue(){
		
		wp_enqueue_script(
			'nhp-opts-field-upload-js', 
			NHP_OPTIONS_URL.'fields/upload/field_upload.js', 
			array('jquery', 'thickbox', 'media-upload'),
			time(),
			true
		);
		
		wp_enqueue_style('thickbox');// thanks to https://github.com/rzepak
		
		wp_localize_script('nhp-opts-field-upload-js', 'nhp_upload', array('url' => $this->url.'fields/upload/blank.png'));
		
	}//function
	
}//class
?>