<?php
trait taxOverrideTrait
{
	public function edit_taxes()
	{
		$data = array();
		$data['controller_name']=strtolower(get_class());
		$data['tax_info'] = $this->cart->get_override_tax_info();
		$this->load->view("tax_override",$data);
		
	}
	
	function save_tax_overrides()
	{
		$data = array();
		$this->cart->override_tax_names = $this->input->post('tax_names');
		$this->cart->override_tax_percents = $this->input->post('tax_percents');
		$this->cart->override_tax_cumulatives = $this->input->post('tax_cumulatives');
		$this->cart->save();
  	$this->_reload($data);
		
	}
}