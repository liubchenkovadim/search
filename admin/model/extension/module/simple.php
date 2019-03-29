<?php

class ModelExtensionModuleSimple extends Model
{

    public $install = true;

    public function getSetting()
    {
        $result = $this->db->query("SELECT * FROM " . DB_PREFIX . "pw_setting");

        return $result->row;
    }

    public function setSetting($data)
    {

        $sql =" UPDATE ". DB_PREFIX . "pw_setting SET ";
        if(isset($data['setting_input'])){
            $sql .= "setting_input='".$data['setting_input']."'" ;
        }
        if(isset($data['category_input'])) {
            $sql .= ", category_input='" . $data['category_input'] . "'" ;
        }

        if(isset($data['information_input'])){
            $sql .= ", information_input='".$data['information_input']."'" ;
        }
        if(isset($data['manufacturer_input'])){
            $sql .= ", manufacturer_input='".$data['manufacturer_input']."' ";
        }
        if(isset($data['product_input'])){
            $sql .= ", product_input='".$data['product_input']."' ";
        }
        if(isset($data['desing_input'])){
            $sql .= ", desing_input='".$data['desing_input']."' ";
        }
        if(isset($data['appearance'])){
            $sql .= ", appearance='".$data['appearance']."' ";
        }
        $sql .= " WHERE setting_id = 1 ";

        $this->db->query($sql);
    }
    public function createDatabase($data){


        $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "pw_setting` (
        `setting_id` int(11) NOT NULL AUTO_INCREMENT,
        `setting_input` text NOT NULL,
        `category_input` text NOT NULL,
        `information_input` text NOT NULL,
        `manufacturer_input` text NOT NULL,
        `product_input` text NOT NULL,
        `desing_input` text NOT NULL,
       `appearance` text NOT NULL,


        PRIMARY KEY (`setting_id`)
        )
        COLLATE='utf8_general_ci'
        ");


        $this->db->query("CREATE TABLE IF NOT EXISTS  " . DB_PREFIX . "suggestion
        (
            id int auto_increment,
	name text null,
	value text null,
	constraint oc_suggestion_pk
		primary key (id)
)  COLLATE='utf8_general_ci'");

     $this->db->query("INSERT INTO " . DB_PREFIX . "pw_setting SET
        setting_input='" . $data['setting_input'] . "',
        category_input='" . $data['category_input'] . "',
        information_input='" . $data['information_input'] . "',
        manufacturer_input='" . $data['manufacturer_input'] . "',
        product_input='" . $data['product_input'] . "',
        desing_input='" . $data['desing_input'] . "',
       appearance='" . $data['appearance'] . "'
         ");
     $this->install = false;

 }

    public function dropDatabase(){

        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "pw_setting`");
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "suggestion`");


    }

    public function getSuggetion()
    {
        $sql = ("SELECT * FROM " . DB_PREFIX . "suggestion");
        $res = $this->db->query($sql);
        return $res->rows;
    }
    public function setSuggetion($suggestion)
    {
      /*  $sql = (" UPDATE  " . DB_PREFIX . "suggestion SET ");*/
        foreach ($suggestion as $id=>$val){
            $sql = "UPDATE  " . DB_PREFIX . "suggestion SET  value='". $this->db->escape($val)."' WHERE  id='".$id."'";

            $this->db->query($sql);

        }

    /*    $sql = "INSERT INTO " . DB_PREFIX . "suggestion  (id, value) VALUES   ";
        $sql .= '(';
        foreach ($suggestion as $id=>$val){
            $sql .='';
        }
        $sql .="ON DUPLICATE KEY UPDATE";
        var_dump($sql);
        die();
            $this->db->query($sql);*/
    }
}
