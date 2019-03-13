<?php
class ModelCatalogCastom extends Model {

    public function getEnables()
    {
        $query = $this->db->query("SELECT * FROM  " . DB_PREFIX ."castom ");

        return $query->rows;
    }

    public function getCastomId($id)
    {
        $sql = ("SELECT * FROM  " . DB_PREFIX ."castom WHERE id=$id");
        $query = $this->db->query($sql);

        return $query->row;
    }

    public function editCastom($id,$data)
    {
        $sql = ("UPDATE ". DB_PREFIX ."castom  SET `before`='". $this->db->escape($data['before']) ."',
               `after`='".$this->db->escape($data['after']). "',
               `enable`=". $data['enable'] ." WHERE id=$id"
        );
        $this->db->query($sql);

        $this->cache->delete('castom');

    }
    public function getProductsId()
    {
        $sql = ("SELECT product_id, image FROM " . DB_PREFIX ."product");
        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function createTable()
    {
        $sql = ('CREATE TABLE  ' . DB_PREFIX .'castom 
(
id int auto_increment,
`before` varchar(50) null,
`after` varchar(50) null,
binding varchar(30) null,
enable boolean  null,
constraint oc_castom_pk
    primary key (id)
) CHARACTER SET utf8 COLLATE utf8_general_ci');

        $this->db->query($sql);
        $this->db->query("INSERT INTO " . DB_PREFIX . "castom  SET `before`='Купить',`after`='в SHOP.TEST', `binding`='product',`enable`=1");
        $this->db->query("INSERT INTO " . DB_PREFIX . "castom  SET `before`='Смотреть',`after`='в SHOP.TEST', `binding`='category',`enable`=1");

    }
}
