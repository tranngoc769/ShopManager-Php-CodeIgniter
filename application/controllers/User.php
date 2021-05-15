<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class User extends My_Controller
{
    public function __construct() {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('cart_model');
        $this->load->model('gate_model');
        $this->load->model('product_model');
    }
    public function charge(){
        $this->gate_model->dev_user_data();
        $data['userData']= $this->user_model->get_userdetail()->row();
        $this->load->view('layout/user/header', array('title' => 'Change Details'));
        $this->loadUserSidebar('show_profile', 'change_detail_active');
        $this->load->view('user/charge', $data);
        $this->load->view('layout/dashboard/logout');
        $this->load->view('layout/user/footer');
    }
    public function upgrade(){
        $data['status'] = false;
        $data['text'] = 'Not request for upgrade';
        if ($this->user_model->get_upgrade_status($this->session->userdata('userid'))){
            $data['status'] = true;
            $data['text'] = 'Requested for upgrade';
        }
        $this->gate_model->dev_user_data();
        $this->load->view('layout/user/header', array('title' => 'Change Details'));
        $this->loadUserSidebar('show_profile', 'change_detail_active');
        $this->load->view('user/upgrade',$data);
        $this->load->view('layout/dashboard/logout');
        $this->load->view('layout/user/footer');
    }
    public function upgradedev(){
        $this->gate_model->user_gate();
        $user_id = $this->session->userdata('userid');
        if ($this->user_model->get_upgrade_status( $user_id )){
            $this->session->set_flashdata('msg', "Already request"); 
            redirect(site_url('user/upgrade'));
            return;
        }
        $data['user_id'] = $user_id;
        if ($this->user_model->upgrade_request($data)){
            $this->session->set_flashdata('msg', "Request success"); 
            redirect(site_url('user/upgrade'));
            return;
        }
        $this->session->set_flashdata('msg', "Something error"); 
        redirect(site_url('user/upgrade'));
        return;

    }
    public function index() {
        $this->gate_model->dev_user_data();
        $this->load->view('layout/user/header');
        $this->load->view('user/login');
        $this->load->view('layout/account/footer');
    }
    
    public function charge_cash() {
        $this->gate_model->dev_user_data();
        $user = $this->user_model->get_userdetail()->row();
        $cashcode = $this->input->post('cash_code');
        $id =$user->user_id;
        $check = $this->product_model->check_code($cashcode);
        if ($check==null){
            $this->session->set_flashdata('msg', "Code is used or not available"); 
            redirect(site_url('user/charge'));
        }
        $cash = $check->cash;
        $data['used_by'] = $id;
        $data['date_used'] =  date("Y-m-d h:i:sa");
        $data['is_used'] = 1;
        $update = $this->product_model->used_code($cashcode, $data);
        if (!$update){
            $this->session->set_flashdata('msg', "Cannot use code"); 
            redirect(site_url('user/charge'));
        }
        // 
        $up_cash['cash'] =  intval($user->cash) + intval($cash); 
        $update = $this->user_model->update_cash($id, $up_cash);
        if (!$update){
            $this->session->set_flashdata('msg', "Cannot use code"); 
            redirect(site_url('user/charge'));
        }
        $this->session->set_flashdata('msg', "Success"); 
        redirect(site_url('user/charge'));
    }
    
    public function dashboard() {
        $this->gate_model->dev_user_data();
        $this->load->view('layout/user/header', array('title' => 'User Dashboard'));
        $this->loadUserSidebar(null, null);
        $this->gate_model->dev_user_data();
        $this->load->view('user/dashboard');
        $this->load->view('layout/dashboard/logout');
        $this->load->view('layout/user/footer');
    }
    
    public function change_details() {		
        $this->gate_model->dev_user_data();
        $data['userData']= $this->user_model->get_userdetail()->row();
        $this->load->view('layout/user/header', array('title' => 'Change Details'));
        $this->loadUserSidebar('show_profile', 'change_detail_active');
        $this->load->view('user/change_details', $data);
        $this->load->view('layout/dashboard/logout');
        $this->load->view('layout/user/footer');
    }
    
    public function change_userdetail() {
        $this->gate_model->dev_user_data();
        $user = $this->user_model->get_userdetail()->row();
        $this->form_validation->set_rules(
            'fname', 'First Name',
            'trim|required|min_length[5]|max_length[20]|alpha',
            array(
                'required' => 'You have not provided %s.',
                'min_length' => 'Your {field} needs to be at least {param} characters long',
                'max_length' => 'Your {field} needs to be at most {param} characters long',
                'alpha-numeric' => 'You may only use alphabet in your {field}'
            )
        );

        $this->form_validation->set_rules(
            'lname', 'Last Name',
            'trim|required|min_length[5]|max_length[20]|alpha',
            array(
                'required' => 'You have not provided %s.',
                'min_length' => 'Your {field} needs to be at least {param} characters long',
                'max_length' => 'Your {field} needs to be at most {param} characters long',
                'alpha-numeric' => 'You may only use alphabet in your {field}'
            )
        );

        if ($user->username == $this->input->post('username')) {
            $uniqueUsername = '';
        } else {
            $uniqueUsername = '|is_unique[user_table.username]';
        }
        
        $this->form_validation->set_rules(
            'username', 'Username',
            'required|min_length[5]|max_length[12]'.$uniqueUsername,
            array(
                'required' => 'You have not provided %s.',
                'is_unique' => 'This %s already exists.'
            )
        );
            
        if ($user->email == $this->input->post('email')) {
            $unique = '';
        } else {
            $unique = '|is_unique[user_table.email]';
        }
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email'.$unique, 
            array(
                'required' => 'You have not provided %s.',
                'is_unique' => 'This %s already exists.',
                'valid_email' => 'You did not provide a valid E-Mail Address'
            )
        );
        
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('msg', validation_errors()); 
            redirect(site_url('user/change_details'));
        } else {
            $data['first_name'] = $this->input->post('fname');
            $data['last_name'] 	= $this->input->post('lname');
            $data['username'] 	= $this->input->post('username');
            $data['email'] 		= $this->input->post('email');
            $message = 'Your account detail is successfully updated.';
            $this->session->set_flashdata('msg', $message); 
            $this->user_model->update_userdetail($this->session->userdata('userid'), $data);
            redirect(site_url('user/change_details'));
        }
    }
    
    public function change_password() {
        $this->gate_model->dev_user_data();
        $data['userData']	=	$this->user_model->get_userdetail()->row();
        $this->load->view('layout/user/header', array('title' => 'Change Password'));
        $this->loadUserSidebar('show_profile', 'change_password_active');
        $this->load->view('user/change_password', $data);
        $this->load->view('layout/dashboard/logout');
        $this->load->view('layout/user/footer');
    }
    
    public function change_userpassword() {
        $this->gate_model->dev_user_data();
        $data['oldpassword']	=	$this->input->post('oldpassword');
        $data['newpassword']	=	$this->input->post('newpassword');
        $data['renewpassword']	=	$this->input->post('renewpassword');
        $this->user_model->change_userpassword($data);
    }
    
    public function your_cart() {
        $this->gate_model->dev_user_data();
        $cartExist = $this->cart_model->hasActiveCart();
        if ($cartExist) {
            $cartid = $this->cart_model->getUserActiveCartID();
            $cartData = $data['cartData'] = $this->cart_model->getProductsInCart($cartid);
            $data['totalPrice'] = 0;
            foreach($cartData as $cart) {
                $data['totalPrice'] += $cart->price * $cart->quantity;
            }
        }
        $data['exist'] = $cartExist;
        $this->load->view('layout/user/header', array('title' => 'Your Cart'));
        $this->loadUserSidebar('show_cart_order', 'your_cart_active');
        $this->load->view('user/manage_cart', $data);
        $this->load->view('layout/dashboard/logout');
        $this->load->view('layout/user/cart_modal');
        $this->load->view('layout/user/footer');
    }
    
    public function checkout() {
        $this->gate_model->dev_user_data();
        $cartid = $this->cart_model->getUserActiveCartID();
        $cartData = $data['cartData'] = $this->cart_model->getProductsInCart($cartid);
        if (count($cartData) == 0) {
            $message = '<div class="alert alert-danger" style="margin-top:10px" role="alert"> You have no products in your cart. Cannot proceed to checkout </div>'; 
            $this->session->set_flashdata('msg', $message);
            redirect('user/your_cart');
        } else {
            $user_data = $this->user_model->get_userdetail($this->session->userdata('userid'))->row();
            $totalPrice = 0;
            foreach($cartData as $cart) {
                $totalPrice += $cart->price * $cart->quantity;
            }
            if ($user_data->cash < $totalPrice ){
                $message = '<div class="alert alert-danger" style="margin-top:10px" role="alert"> Your dont have enough cash</div>'; 
                $this->session->set_flashdata('msg', $message);
                redirect('user/your_cart');
                return;
            }
            foreach($cartData as $cart) {
                $ud_data['link'] = str_replace("uploads/","downloads/".$user_data->user_id."_",$cart->zip_link);
                $this->product_model->update_downloadlink($cart->product_id,$ud_data);
                copy($cart->zip_link, $ud_data['link']);
            }
            $cash_new['cash'] = $user_data->cash - $totalPrice;
            $this->user_model->update_cash($user_data->user_id,$cash_new);
            $this->cart_model->buyCart($cartid);
            $this->load->view('layout/user/header', array('title' => 'Cart'));
            $this->loadUserSidebar('show_cart_order', 'your_cart_active');
            redirect('user/your_cart');
            $this->load->view('layout/dashboard/logout');
            $this->load->view('layout/user/footer');
        }
        
    }
    
    public function your_order() {
        $orders = $this->cart_model->getAllUserOrders()->result();
        foreach($orders as $order) {
            $order->totalPrice = $this->cart_model->getTotalCartPrice($order->cart_id);
        }
        $data["orders"] = $orders;
        $this->load->view('layout/user/header', array("title" => "Manage Orders"));
        $this->loadUserSidebar('show_cart_order', 'your_order_active');
        $this->load->view("user/manage_order", $data);
        $this->load->view('layout/dashboard/logout');
        $this->load->view('layout/user/footer');
    }
    
    public function view_order($cartid) {
        $this->gate_model->dev_user_data();
        $status = $this->cart_model->userCartChecking($cartid);
        if (!$status) {
            $message = '<div class="alert alert-danger" style="margin-top:10px" role="alert">You are not allowed to access this page. </div>';
            $this->session->set_flashdata('message', $message);
            redirect('user/your_order');
        } else {
            $cartData = $data['cartData'] = $this->cart_model->getProductsInCart($cartid);
            $data['totalPrice'] = 0;
            foreach($cartData as $cart) {
                $data['totalPrice'] += $cart->price * $cart->quantity;
            }
            $this->load->view('layout/user/header', array('title' => 'Your Cart'));
            $this->loadUserSidebar('show_cart_order', 'your_order_active');
            $this->load->view('user/view_order', $data);
            $this->load->view('layout/dashboard/logout');
            $this->load->view('layout/user/cart_modal');
            $this->load->view('layout/user/footer');
        }
        
    }
    
}
