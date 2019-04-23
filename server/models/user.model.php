<?php

class UserModel extends Model
{
    public function login($email, $password)
    {
        $email = trim($this->db->escape($email));
        $password = trim($this->db->escape($password));
        $password = hashPassword($password);

        $res = $this->db->query("SELECT `id` FROM users WHERE `email` = '$email' AND `password` = '$password'");
        return isset($res[0]) ? $res[0]['id'] : false;
    }

    public function register($email, $password, $fn, $ln, $mn)
    {
        $email = trim($this->db->escape($email));
        $password = trim($this->db->escape($password));
        $password = hashPassword($password);
        $fn = trim($this->db->escape($fn));
        $ln = trim($this->db->escape($ln));
        $mn = trim($this->db->escape($mn));

        $res = $this->db->query("INSERT INTO `users` (`email`, `password`, `first_name`, `last_name`, `middle_name`) VALUES ('$email', '$password', '$fn', '$ln', '$mn');");
        return $res;
    }

    public function update($id, $email, $fn, $ln, $mn)
    {
        $id = (int)$id;
        $email = trim($this->db->escape($email));
        $fn = trim($this->db->escape($fn));
        $ln = trim($this->db->escape($ln));
        $mn = trim($this->db->escape($mn));

        $res = $this->db->query("UPDATE `users` SET `email` = '$email', `first_name` = '$fn', `last_name` = '$ln', `middle_name` = '$mn' WHERE `id` = $id;");
        return $res;
    }

    public function delete($id)
    {
        $id = (int)$id;

        $res = $this->db->query("DELETE FROM `users` WHERE `id` = $id;");
        return $res;
    }

    public function getById($id)
    {
        $id = (int)$id;
        $res = $this->db->query("SELECT * FROM users WHERE `id` = '$id'");
        return isset($res[0]) ? $res[0] : false;
    }

    public function ordersCol($userid)
    {
        $userid = (int)$userid;
        $res = $this->db->query("SELECT COUNT(*) FROM `orders` WHERE `user` = $userid AND `show` = 1;");
        return $res[0]['COUNT(*)'];
    }

    public function addressesCol($userid)
    {
        $userid = (int)$userid;
        $res = $this->db->query("SELECT COUNT(*) FROM `addresses` WHERE `user` = $userid AND `show` = 1;");
        return $res[0]['COUNT(*)'];
    }

    public function addresses($userid)
    {
        $userid = (int)$userid;
        $res = $this->db->query("SELECT * FROM `addresses` WHERE `user` = $userid AND `show` = 1;");
        return $res;
    }

    public function addAddress($userid, $address)
    {
        $userid = (int)$userid;
        $address = trim($this->db->escape($address));

        $res = $this->db->query("INSERT INTO `addresses` (`user`, `address`) VALUES ($userid, '$address');");
        return $res;
    }

    public function deleteAddress($id, $userid)
    {
        $id = (int)$id;
        $userid = (int)$userid;
        $res = $this->db->query("UPDATE `addresses` SET `show` = 0 WHERE `id` = $id AND `user` = $userid");
        $rows = $this->db->getConnection()->affected_rows;
        $rows = $rows > 0 ? true : false;
        return $res ? $rows : false;
    }

    public function getAddress($id, $userid)
    {
        $id = (int)$id;
        $userid = (int)$userid;
        $res = $this->db->query("SELECT * FROM `addresses` WHERE `show` = 1 AND `id` = $id AND `user` = $userid");

        return isset($res[0]) ? $res[0] : null;
    }

    public function getAddressByName($address, $userid)
    {
        $address = $this->db->escape($address);
        $userid = (int)$userid;
        $res = $this->db->query("SELECT * FROM `addresses` WHERE `show` = 1 AND `address` = '$address' AND `user` = $userid");

        return isset($res[0]) ? $res[0] : null;
    }

    public function getOrders($userid)
    {
        $userid = (int)$userid;
        $res = $this->db->query("SELECT * FROM orders WHERE `user` = $userid AND `show` = 1 ORDER BY `date` DESC;");
        return $res;
    }

    public function getAll($startPos = 0, $limit = 10, $order = 1, $orderSc = 0)
    {
        $startPos = (int)$startPos;
        $limit = (int)$limit;
        $order = (int)$order;
        $orderSc = (int)$orderSc;
        $orderSc = $orderSc == 0 ? "ASC" : "DESC";

        $res = $this->db->query("SELECT * FROM `users` ORDER BY $order $orderSc LIMIT $startPos, $limit;");
        return $res;
    }

    public function allCount()
    {
        $res = $this->db->query("SELECT COUNT(*) FROM `users`;");
        return $res[0]['COUNT(*)'];
    }
}