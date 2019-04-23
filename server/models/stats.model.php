<?php
class StatsModel extends Model
{
    public function getNewOrders()
    {
        $res = $this->db->query("SELECT COUNT(*) FROM `orders` WHERE `status` = 0 AND `show` = 1;");
        return $res[0]['COUNT(*)'];
    }

    public function getOrders()
    {
        $res = $this->db->query("SELECT COUNT(*) FROM `orders` WHERE `show` = 1;");
        return $res[0]['COUNT(*)'];
    }

    public function getUsers()
    {
        $res = $this->db->query("SELECT COUNT(*) FROM `users`;");
        return $res[0]['COUNT(*)'];
    }

    public function getProducts()
    {
        $res = $this->db->query("SELECT COUNT(*) FROM `products` WHERE `del` = 0;");
        return $res[0]['COUNT(*)'];
    }
}