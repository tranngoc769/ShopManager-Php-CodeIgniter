<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Dev extends My_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('account_model');
        $this->load->model('admin_model');
        $this->load->model('product_model');
        $this->load->model('category_model');
        $this->load->model('cart_model');
        $this->load->model('contact_model');
        $this->load->model('user_model');
    }
    
    public function index()
    {
        $this->gate_model->dev_gate();
        $data['newMessagesCount'] = $this->contact_model->getNewMessagesCount();
        $data['messages'] = $this->contact_model->getMessages();
        $this->load->view('layout/dashboard/header', array('title' => 'Developer Dashboard'));
        $this->loadDevSidebar(null, null);
        $this->load->view('admin/dashboard', $data);
        $this->load->view('layout/dashboard/footer');
    }
    
    public function read_message($message_id)
    {
        $this->gate_model->admin_gate();
        $this->contact_model->readMessage($message_id);
        $data['message'] = $this->contact_model->getMessage($message_id)->row();
        $this->load->view('layout/dashboard/header', array('title' => 'Admin Dashboard'));
        $this->loadDevSidebar(null, null);
        $this->load->view('admin/read_message', $data);
        $this->load->view('layout/dashboard/footer');
    }
    
    
    public function view_product()
    {
        $this->gate_model->dev_gate();
        $user_id = $this->session->userdata('userid');
        $data["productlist"] = $this->product_model->getSellerProduct($user_id);
        $data["categories"] = $this->category_model->getAllCategoriesWithSubCategories();
        $this->load->view('layout/dashboard/header', array("title" => "View Products"));
        $this->loadDevSidebar("show_product", "manage_product_active");
        $this->load->view('admin/view_product',$data);
        $this->load->view('layout/dashboard/footer');
    }
    
    public function add_product()
    {
        $this->gate_model->dev_gate();
        $data["categories"] = $this->category_model->getAllCategoriesWithSubCategories();
        $this->load->view('layout/dashboard/header', array("title" => "Add Product"));
        $this->loadDevSidebar("show_product", "add_product_active");
        $this->load->view('admin/add_product',$data);
        $this->load->view('layout/dashboard/footer');
    }
    
    public function edit_product($product_id)
    {
        $this->gate_model->admin_gate();
        $data['product'] = $this->product_model->getProduct($product_id)->row();
        $data["categories"] = $this->category_model->getAllCategoriesWithSubCategories();
        $data["product_id"] = $product_id;
        $image_link = $this->product_model->getProductImageLink($product_id);
        if (count($image_link) == 0){
            $data["image_link"] = 'style/assets/images/no_image.png';
        } else {
            $data["image_link"] = $image_link;
        }
        $this->load->view('layout/dashboard/header', array("title" => "Edit Product"));
        $this->loadDevSidebar("show_product", "manage_product_active");
        $this->load->view('admin/edit_product',$data);
        $this->load->view('layout/dashboard/footer');
    }
    
}
