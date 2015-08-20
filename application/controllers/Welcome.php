<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		
		$contents['recaptcha_html'] = $this->recaptcha->recaptcha_get_html();
		$this->load->view('welcome_message',$contents);
		
	}
	
	public function getResponse($str){

		$this->recaptcha->recaptcha_check_answer();
		if($this->recaptcha->getIsValid())
		{     
			return true;
        }     
        else
        {
			$this->form_validation->set_message('getResponse', '%s code wrong');
			return false;
        }
    }
	
	public function send(){
		
		if($this->input->post()){
			
			$this->form_validation->set_rules('name', '<b>Name</b>', 'trim|required');
			$this->form_validation->set_rules('message', '<b>Message</b>', 'trim|required');
			$this->form_validation->set_rules('recaptcha_response_field', '<b>Captcha</b>', 'required|callback_getResponse');
			
			if ($this->form_validation->run() == FALSE) {
			
				$this->session->set_flashdata('name',$this->input->post('name'));
				$this->session->set_flashdata('message',$this->input->post('message'));
			
				$this->session->set_flashdata('alert', '<div class="alert alert-danger">' . validation_errors() . '</div>');
			
				redirect('welcome');
			
			} else {
				
				$this->session->set_flashdata('alert', '<div class="alert alert-success">Message sent</div>');
			
				redirect('welcome');
				
			}
			
		} else {
			
			redirect('welcome');
			
		}
	}
}
