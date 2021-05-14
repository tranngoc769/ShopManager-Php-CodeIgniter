<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Code extends My_Controller
{

    private function gen_uuid() {
        return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
            mt_rand( 0, 0xffff ),
            mt_rand( 0, 0x0fff ) | 0x4000,
            mt_rand( 0, 0x3fff ) | 0x8000,
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
        );
    }
    public function __construct()
    {
        parent::__construct();
        $this->load->model('product_model');
        $this->session->keep_flashdata('msg');
    }
    public function add()
    {
        $this->gate_model->admin_gate();
        $this->form_validation->set_rules(
            'code_cash',
            'Code cash',
            'required',
            array(
                'required' => '<div class="alert alert-danger">You have not provided %s.</div>'
            )
        );
        $this->form_validation->set_rules(
            'code_amount',
            'Code amount',
            'required',
            array(
                'required' => '<div class="alert alert-danger">You have not provided %s.</div>'
            )
        );
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('layout/dashboard/header', array("title" => "Add code"));
            $this->loadSidebar("show_code", "add_code_active");
            $this->load->view('admin/add_code');
            $this->load->view('layout/dashboard/footer');
        } else {
            $insert = true;
            // Take code details
            $code_cash= $this->input->post('code_cash');
            $code_amount= $this->input->post('code_amount');
            $amount = intval($code_amount);
            for ($i=0; $i < $amount; $i++) { 
                $data['code'] = $this->gen_uuid();
                $data['date_created'] = date("Y-m-d h:i:sa");
                $data['cash'] = $code_cash;
                $status = $this->product_model->add_code($data);
                if (!$status){
                    $insert = $status;
                    break;
                }
            }
            if ($insert) {
                $message = "<div class='alert alert-success alert-dismissable'>";
                $message .= "<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>";
                $message .= "<strong>Success!</strong> Code is added!";
                $message .= "</div>";
            } else {
                $message = "<div class='alert alert-danger alert-dismissable'>";
                $message .= "<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>";
                $message .= "<strong>Fail!</strong> Fail to add code";
                $message .= "</div>";
            }
            $this->session->set_flashdata('msg', $message);
            redirect($this->session->userdata('usertype') . '/view_code');
        }
    }
}
