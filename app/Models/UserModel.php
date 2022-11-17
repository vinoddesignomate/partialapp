<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table      = 'ppa_store_token';
    protected $primaryKey = 'id';
    protected $db;

    public function __construct()
    {
        parent::__construct();
        $this->db = \Config\Database::connect();
        // OR $this->db = db_connect();
    }


    public function insert_data($data = array())
    {
        $this->db->table($this->table)->insert($data);
        return $this->db->insertID();
    }
    public function update_data($shop_url, $data = array())
    {
        $this->db->table($this->table)->update($data, array(
            "shop_url" => $shop_url,
        ));
        return $this->db->affectedRows();
    }
    public function get_data()
    {
        $qbuilds = $this->db->table('test_tbl');
        $qbuilds->select('*');
        $getquery = $qbuilds->get();
        return $getquery->getResult();
    }
    public function get_tokens($shopurl)
    {
        $query = $this->db->query('SELECT * FROM ppa_store_token WHERE shop_url="' . $shopurl . '" LIMIT 1');
        return $query->getRow();
        //return count($query->getResult());
    }
    public function checktokens($shopurl)
    {
        $query = $this->db->query('SELECT * FROM ppa_store_token WHERE shop_url="' . $shopurl . '" LIMIT 1');
        //return $query->getResult();
        return count($query->getResult());
    }

}
