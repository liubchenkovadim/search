<?php

class ModelExtensionModuleSimple extends Model
{

    private $route = 'extension/module/simple';

    public function getSetting()
    {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "pw_setting ");

        return $query->row;

    }

    public function search($text, $searches = array(), $research = 0)
    {
        $this->load->language($this->route);
        $this->load->model('catalog/product');
        $this->load->model('catalog/category');
        $this->load->model('catalog/manufacturer');
        $this->load->model('catalog/information');
        $this->load->model('tool/image');



    }
    public function  getCategory($search,$array)
    {


        $sql = "SELECT category_id, name, meta_title FROM " . DB_PREFIX . "category_description  WHERE  ";
        foreach ($array as $key=>$val){
            if($key == 0){
                $sql .= " $val LIKE '%$search%' ";
            } else {
                $sql .= " OR  $val LIKE '%$search%' ";
            }

        }

            $res = $this->db->query($sql);

            return $res->rows;


    }

    public function getProduct($search,$array)
    {
        $sql = "SELECT pd.product_id, pd.name ,pc.category_id
                FROM " . DB_PREFIX . "product_description AS pd 
                 LEFT JOIN " . DB_PREFIX . "product AS p
                 ON pd.product_id = p.product_id
                 LEFT JOIN " . DB_PREFIX . "product_to_category AS pc
                  ON pc.product_id = p.product_id
                 WHERE  ";
        foreach ($array as $key=>$val){
            if($key == 0){
                $sql .= " $val LIKE '%$search%' ";
            } else {
                $sql .= " OR  $val LIKE '%$search%' ";
            }

        }

        $res = $this->db->query($sql);

       return $res->rows;

    }
}