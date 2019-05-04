<?php
class StatsModel extends Model
{

    public function getOrders()
    {
        $res = $this->db->query("SELECT COUNT(*) FROM `orders`");
        return $res[0]['COUNT(*)'];
    }

    public function getProducts()
    {
        $res = $this->db->query("SELECT COUNT(*) FROM `products` WHERE `del` = 0;");
        return $res[0]['COUNT(*)'];
    }
}