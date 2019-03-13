<?php

class ModelExtensionModuleSimple extends Model
{
    public function getSetting()
    {
        $result = $this->db->query("SELECT * FROM `" . DB_PREFIX . "pw_setting`");
        return $result;
    }
    public function createDatabase(){


        $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "pw_setting` (
        `setting_id` int(11) NOT NULL AUTO_INCREMENT,
        `status` boolean NOT NULL,
        `min_symbol` int NOT NULL,
        `suggestions` int NOT NULL,
        `date_modify` datetime NOT NULL,
        PRIMARY KEY (`setting_id`)
        )
        COLLATE='utf8_general_ci'
        ");


    }

    public function dropDatabase(){

        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "pw_setting`");

    }
}
