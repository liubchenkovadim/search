<?php
class ModelCatalogCastom extends Model
{
    public function getCastomCategory($type) {
        $query = $this->db->query("SELECT * FROM ".DB_PREFIX."castom  WHERE binding='$type' ");

        return $query->row;
    }
}