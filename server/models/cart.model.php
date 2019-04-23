<?php

class CartModel extends Model
{
    public function add($productID, $userID, $col = 1)
    {
        $productID = (int)$productID;
        $userID = (int)$userID;
        $col = (int)$col;
        if ($col < 1) $col = 1;

        $old = $this->db->query("SELECT * FROM `cart` WHERE `product` = $productID AND `user` = $userID");

        $newCol = (isset($old[0]['col']) ? $old[0]['col'] : 0) + $col;

        $productModel = new ProductModel();
        $max = $productModel->count($productID);
        if ($newCol > $max) {
            return 'max';
        } else {
            if (count($old) > 0) {
                $id = $old[0]['id'];
                $res = $this->db->query("UPDATE `cart` SET `col` = $newCol WHERE `id` = $id");

            } else {
                $res = $this->db->query("INSERT INTO `cart` (`user`, `product`, `col`) VALUES ($userID, $productID, $col)");
            }
        }

        return $res;
    }

    public function get()
    {
        $cart = isset($_COOKIE['cart']) ? $_COOKIE['cart'] : '{}';
        if (!isJson($cart)) $cart = '{}';
        $cart = json_decode($cart, true);

        $productModel = new ProductModel();

        foreach ($cart as $id => $value) {
            $col = $value['col'];
            $product = $productModel->get($id);
            if ($col > $product['col']) $col = $product['col'];
            if ($col < 1) $col = 1;
            $this->update($id, $col);
        }

        $cart = isset($_COOKIE['cart']) ? $_COOKIE['cart'] : '{}';
        if (!isJson($cart)) $cart = '{}';
        $cart = json_decode($cart, true);

        $products = array();
        foreach ($cart as $id => $value) {
            $products[$id]['product'] = $id;
            $products[$id]['col'] = $value['col'];
        }

        return $products;
    }

    public function update($productID, $col)
    {
        $productID = (int)$productID;
        $col = (int)$col;

        $cart = isset($_COOKIE['cart']) ? $_COOKIE['cart'] : '{}';
        if (!isJson($cart)) $cart = '{}';
        $cart = json_decode($cart, true);

        if (isset($cart[$productID])) {
            $cart[$productID]['col'] = $col;
        }

        $_COOKIE['cart'] = json_encode($cart);

        $time = time() + 365 * 24 * 60 * 60;
        return setcookie('cart', json_encode($cart), $time, '/');
    }

    public function delete($productID)
    {
        $productID = (int)$productID;
        $cart = isset($_COOKIE['cart']) ? $_COOKIE['cart'] : '{}';
        if (!isJson($cart)) $cart = '{}';
        $cart = json_decode($cart, true);

        if (isset($cart[$productID])) {
            unset($cart[$productID]);
        }

        $_COOKIE['cart'] = json_encode($cart);

        $time = time() + 365 * 24 * 60 * 60;
        return setcookie('cart', json_encode($cart), $time, '/');

    }
}