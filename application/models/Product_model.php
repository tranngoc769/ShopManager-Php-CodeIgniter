<?php

class Product_model extends CI_Model
{

    public function getAllProducts()
    {
        return $this->db->join(CATEGORY, "catt.category_id = pt.category_id")->get(PRODUCT);
    }

    public function getAllCodes()
    {
        return $this->db->get(CODE);
    }

    public function getSixProducts()
    {
        return $this->db->limit(6)->get(PRODUCT);
    }

    public function getProduct($product_id = "")
    {
        return $this->db
            ->join('product_images pi', 'pi.product_id = pt.product_id', 'left')
            ->get_where('product_table pt', array("pt.product_id" => $product_id));
    }

    public function getCategoryProduct($category_id = "")
    {
        return $this->db->join(CATEGORY, "catt.category_id = pt.category_id")->get_where(PRODUCT, array("pt.category_id" => $category_id));
    }

    public function getActiveCategoryProduct($category_id = "")
    {
        return $this->db
            ->join('product_images pi', 'pi.product_id = pt.product_id')
            ->get_where(PRODUCT, array("category_id" => $category_id, "active_flag" =>1));
    }
    public function getSellerProduct($seller_id = "")
    {
        return $this->db->get_where(PRODUCT, array("seller_id" => $seller_id));
    }

    public function addProductNoImage($array)
    {
        $insert = $this->db->insert("product_table", $array);
        return $insert;
    }
    public function add_code($array)
    {
        $insert = $this->db->insert("code_table", $array);
        return $insert;
    }
    public function check_code($code)
    {
        return $this->db->where(array('code' => $code, 'is_used' => 0))->get('code_table')->row();
    }
    public function used_code($code, $array)
    {
        return $this->db->where(array('code' => $code, 'is_used' => 0))->update('code_table', $array);
    }

    public function addProduct($array, $image_link, $zip_link)
    {
        $insert = $this->db->insert("product_table", $array);
        $product_id = $this->db->insert_id();
        $this->db->insert('product_images', array('product_id' => $product_id, "image_link" => $image_link));
        $this->db->insert('product_zips', array('product_id' => $product_id, "zip_link" => $zip_link));
        return $insert;
    }
    public function updateProductWithProductImage($product_id, $array, $image_link)
    {
        $update = $this->db->where("product_id", $product_id)->update(PRODUCT, $array);
        $productImageId = $this->getProductImageId($product_id);
        if (!$productImageId) {
            $this->db->insert('product_images', array('product_id' => $product_id, "image_link" => $image_link));
        } else {
            $image_path = $_SERVER['DOCUMENT_ROOT'] . '/codeigniter/' . $this->getProductImageLink($product_id);
            unlink($image_path);
            $this->db->where(array('product_id' => $product_id))->update('product_images', array("image_link" => $image_link));
        }
        return $update;
    }
    public function updateProduct($product_id, $array)
    {
        return $this->db->where("product_id", $product_id)->update(PRODUCT, $array);
    }
    public function searchProduct($search = "")
    {
        return $this->db->like('product_name', "$search")->get(PRODUCT);
    }
    public function searchActiveProduct($search = "")
    {
        return $this->db
            ->join('product_images pi', 'pi.product_id = pt.product_id')
            ->like('product_name', "$search")->get_where(PRODUCT, array("active_flag" =>1));
    }

    public function changeStatus($product_id, $active_flag)
    {
        return $this->db->where('product_id', $product_id)->update(PRODUCT, array('active_flag' => $active_flag));
    }

    public function getProductName($product_id)
    {
        return $this->db->where('product_id', $product_id)->get('product_table')->row()->product_name;
    }
    public function getActiveProduct()
    {
        return $this->db
            ->join('product_images pi', 'pi.product_id = pt.product_id')
            ->get_where('product_table pt', array('pt.active_flag' => 0))->result();
    }
    public function getProductImageLink($product_id)
    {
        $image = $this->db->get_where('product_images', array('product_id' => $product_id))->row();
        if (count($image) != 0) {
            return $image->image_link;
        } else {
            return null;
        }
    }
    public function getProductImageId($product_id)
    {
        $image = $this->db->get_where('product_images', array('product_id' => $product_id))->row();
        if (count($image) != 0) {
            return $image->product_images_id;
        } else {
            return false;
        }
    }
    public function update_downloadlink($product_id, $data){
        $update = $this->db->where("product_id", $product_id)->update("product_cart_table", $data);
        return $update;
    }
    public function getProductDev($product_id)
    {
        $image = $this->db->get_where('product_table', array('product_id' => $product_id))->row();
        if (count($image) != 0) {
            return $image->seller_id;
        } else {
            return false;
        }
    }
    public function getProductReview($product_id)
    {
        return $this->db
            ->join('user_table ut', 'ut.user_id=rt.user_id')
            ->get_where('review_table rt', array('product_id' => $product_id))->result();
    }
    public function addProductReview($data)
    {
        return $this->db->insert('review_table', $data);
    }
    public function getProductCount()
    {
        return $this->db->count_all('product_table');
    }
    public function check_if_bought_or_in_cart($pid,$uid)
    {
        $query = $this->db->join('product_cart_table pct', 'ct.cart_id = pct.cart_id')
                ->get_where('cart_table ct', array('pct.product_id' => $pid,'ct.user_id'=>$uid));
            if ($query->num_rows() > 0) {
                return $query->result();
            } else {
                return false;
            }
    }
    public function check_if_in_cart($pid,$uid)
    {
        $query = $this->db->join('product_cart_table pct', 'ct.cart_id = pct.cart_id')
                ->get_where('cart_table ct', array('pct.product_id' => $pid,'ct.user_id'=>$uid));
            if ($query->num_rows() > 0) {
                return $query->result();
            } else {
                return false;
            }
    }
    public function getLimitProducts($limit, $start)
    {
        $query = $this->db->limit($limit, $start)
            ->join('product_images pi', 'pi.product_id = pt.product_id')
            ->get_where('product_table pt', array('pt.active_flag' => 1));
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }
    public function GetMostPaidDownLoadProduct($limit, $start)
    {
        $sql = "SELECT COUNT(pct.product_id) as download,pi.image_link, pct.product_id, pt.category_id, pt.seller_id, pt.product_name, pt.price, pt.short_desc, pt.description, pt.add_time, pt.active_flag FROM  product_table pt JOIN product_cart_table pct on pt.product_id = pct.product_id JOIN product_images pi on pi.product_id = pct.product_id WHERE pt.cost_app = 1 GROUP BY pct.product_id ORDER BY download DESC LIMIT ".$limit."  OFFSET ".$start;
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }
    public function GetMostFreeDownLoadProduct($limit, $start)
    {
        $sql = "SELECT COUNT(pct.product_id) as download,pi.image_link, pct.product_id, pt.category_id, pt.seller_id, pt.product_name, pt.price, pt.short_desc, pt.description, pt.add_time, pt.active_flag FROM  product_table pt JOIN product_cart_table pct on pt.product_id = pct.product_id JOIN product_images pi on pi.product_id = pct.product_id WHERE pt.cost_app = 0 GROUP BY pct.product_id ORDER BY download DESC LIMIT ".$limit."  OFFSET ".$start;
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }
}
