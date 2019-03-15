<?php

class ModelExtensionModuleSimple extends Model
{
    public function getSetting()
    {
        $result = $this->db->query("SELECT * FROM " . DB_PREFIX . "pw_setting");

        return $result->row;
    }

    public function setSetting($data)
    {

        $sql ="UPDATE ". DB_PREFIX . "pw_setting SET ";
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

        PRIMARY KEY (`setting_id`)
        )
        COLLATE='utf8_general_ci'
        ");
/*
    $this->db->query("INSERT INTO " . DB_PREFIX . "pw_setting SET
        setting_input='" . $data['setting_input'] . "',
        category_input='" . $data['category_input'] . "',
        information_input='" . $data['information_input'] . "',
        manufacturer_input='" . $data['manufacturer_input'] . "',
        product_input='" . $data['product_input'] . "',
        desing_input='" . $data['desing_input'] . "'
         "); */
 }

    public function dropDatabase(){

        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "pw_setting`");

    }
}
