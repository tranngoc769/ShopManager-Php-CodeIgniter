<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class CART extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('cart_model');
        $this->load->model('gate_model');
        $this->load->model('user_model');
        $this->load->model('product_model');
    }


    // Testing
    public function getTotalCartPrice($cart_id) {
        echo "$ ".$this->cart_model->getTotalCartPrice($cart_id);
    } 

    public function addToCart() {
        // $this->gate_model->ajax_gate();
        $product_id = $_POST['product_id'];
        $quantity = $_POST['quantity'];
        $uid = $this->session->userdata('userid');
        if ($quantity != 1){
            $quantity = 1;
        }
        $bought_exist = $this->product_model->check_if_bought_or_in_cart($product_id,  $uid);
        if ($bought_exist ){
            $message = "Already bought or in cart";
            $array = array (
                "success" => false,
                "message" => $message,
                "title" => "Warning!"
            );
            header('Access-Control-Allow-Origin: *');
            header('Content-Type: application/json');
            echo json_encode($array);
            return;
        }
        if ($this->session->userdata('usertype') != 'user' && $this->session->userdata('usertype') != 'admin') {
            $message = "Need to login first!";
            $array = array("success" => false, "message" => $message, "title" => "Warning!");
        } 
        elseif ($this->session->userdata('usertype') == 'admin') {
            $message = "You are the admin!";
            $array = array("success" => false, "message" => $message, "title" => "Warning!");
         }else {
            if ($this->cart_model->hasActiveCart()) {
                $cart_id = $this->cart_model->getUserActiveCartID();
            } else {
                $cart_id = $this->cart_model->addNewCart();
            }
            $dev_id = $this->product_model->getProductDev($product_id);
            $insert = $this->cart_model->addProductToCart($cart_id, $product_id, $quantity,$dev_id);
            if ($insert) {
                $message = "Add product successfully";
                $array = array (
                    "success" => true,
                    "message" => $message,
                    "title" => "Success!"
                );
            } else {
                $message = "Fail to add product";
                $array = array (
                    "success" => false,
                    "message" => $message,
                    "title" => "Warning!"
                );
            }
        }
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json');
        echo json_encode($array);
    }

    public function removeFromCart() {
        $pcid = $this->input->post('productCartId');
        $succ = $this->cart_model->removeFromCart($pcid);
        if ($succ) $message = '<div class="alert alert-success" style="margin-top:10px" role="alert"> Item removed from your cart. </div>';
        else $message = '<div class="alert alert-danger" style="margin-top:10px" role="alert"> Failed to remove item from your cart. </div>';       

        $this->session->set_flashdata('msg', $message);
        redirect('user/your_cart');
    }

}
