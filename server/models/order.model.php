<?php

class OrderModel extends Model
{
    public function get($id)
    {
        $id = (int)$id;
        $res = $this->db->query("SELECT * FROM orders WHERE id = $id");
        return isset($res[0]) ? $res[0] : false;
    }

    public function getAll($startPos = 0, $limit = 10, $order = 1, $orderSc = 0)
    {
        $startPos = (int)$startPos;
        $limit = (int)$limit;
        $order = (int)$order;
        $orderSc = (int)$orderSc;
        $orderSc = $orderSc == 0 ? "ASC" : "DESC";

        $res = $this->db->query("SELECT * FROM orders ORDER BY $order $orderSc LIMIT $startPos, $limit;");
        return $res;
    }

    public function allCount()
    {
        $res = $this->db->query("SELECT COUNT(*) FROM orders;");
        return $res[0]['COUNT(*)'];
    }

    public function newFromApp($name, $phone, $products)
    {
        $name = $this->db->escape($name);
        $phone = $this->db->escape($phone);

        $result = true;

        $this->db->getConnection()->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
        $res = $this->db->query("INSERT INTO `orders` (`name`, `phone`, `date`) VALUES ('$name', '$phone', UNIX_TIMESTAMP(NOW()));");
        if (!$res) $result = false;
        $orderID = $this->db->getConnection()->insert_id;

        $sum = 0;
        $productModel = new ProductModel();
        foreach ($products as $product) {
            $info = $productModel->get($product['id']);
            if (!$info) continue;

            $price = $info['new_price'] ? $info['new_price'] : $info['price'];
            $productID = $product['id'];
            $col = $product['col'];
            $sum += $price * $col;
            $res = $this->db->query("INSERT INTO `order_products` (`order`, `product`, `price`, `col`) VALUES ($orderID, $productID, $price, $col);");

            if (!$res) $result = false;

            $newCol = $info['col'] - $col;
            $res = $productModel->updateCol($productID, max(0, $newCol));
            if (!$res) $result = false;
        }

        $this->db->getConnection()->commit();
        if ($result) {
            $productsString = "";
            $productModel = new ProductModel();
            foreach ($products as $product) {
                $productID = $product['id'];
                $info = $productModel->get($productID);
                if (!$info) continue;

                $productsString .= $product['name'] . " (" . $product['col'] . ' шт.)' . '<br/>';

                $col = $product['col'];
                $newCol = $info['col'] - $col;
                $productModel->updateCol($productID, max(0, $newCol));
            }

            $data = array(
                '-DATE-'    => dateFormat(time()),
                '-NAME-'    => $name,
                '-PHONE-'   => $phone,
                '-PRODUCTS-' => $productsString
            );
            Mail::send(Config::get('email.orders'), 'New order', 'orderLite', $data);
        }

        return $result;
    }

    public function products($id)
    {
        $id = (int)$id;
        $res = $this->db->query("SELECT * FROM order_products WHERE `order` = $id");
        return $res;
    }

    public function editStatus($id, $status) {
        $id = (int)$id;
        $status = (int)$status;

        $res = $this->db->query("UPDATE `orders` SET `status` = $status WHERE `id` = $id;");
        return $res;
    }
}