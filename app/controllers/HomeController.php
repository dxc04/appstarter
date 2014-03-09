<?php

class HomeController extends BaseController {


	public function remunerations() 
	{
		$form_data = array();		
		$this->layout->content = View::make("data_entry", $form_data);
	}

}
