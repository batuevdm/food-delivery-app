<?php

class ProductModel extends Model
{
    public function get($id, $show = 1, $del = 1)
    {
        $id = (int)$id;
        if (is_array($show))
            $show = implode(',', $show);
        else
            $show = (int)$show;
        $show = '(' . $show . ')';
        $res = $this->db->query("SELECT * FROM `products` WHERE `id` = $id AND `show` IN $show AND (`del` = 0 OR `del` = $del);");
        return $res ? $res[0] : null;
    }

    public function add($name, $desc, $price, $newPrice, $category, $col, $files, $specs, $hide)
    {
        $name = $this->db->escape($name);
        $desc = $this->db->escape($desc);
        $show = !$hide;

        $photo = uploadPhoto($files['main-photo']);
        $photo = $photo[0];
        if ($photo === ERROR_FILE_EMPTY) $photo = 'NULL'; else $photo = "'" . $photo . "'";
        if ($newPrice === null) $newPrice = 'NULL';

        $result = true;
        ///////
        $this->db->getConnection()->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);

        $sql = "INSERT INTO `products` (`name`, `desc`, `price`, `new_price`, `main_photo`, `category`, `col`, `show`) VALUES ('$name', '$desc', $price, $newPrice, $photo, $category, $col, $show);";
        $res = $this->db->query($sql);
        if (!$res) $result = 'Ошибка добавления товара - ' . $this->db->error . ' - ' . $sql;

        $productID = $this->db->getConnection()->insert_id;

        $_specs = array();
        $specsCount = count($specs['name']);
        for ($i = 0; $i < $specsCount; $i++) {
            $_specs[$i]['name'] = $specs['name'][$i];
            $_specs[$i]['value'] = $specs['value'][$i];
        }

        foreach ($_specs as $spec) {
            $specName = $spec['name'];
            $value = $spec['value'];
            if (!$specName || !$value) continue;
            $res = $this->db->query("INSERT INTO `specifications` (product, name, value) VALUES ($productID, '$specName', '$value');");
            if (!$res) $result = 'Ошибка добавления характеристики ' . $spec['name'];
        }

        $photos = $files['photos'];
        $photos = uploadPhoto($photos);

        $productPhotos = array();
        foreach ($photos as $photo) {
            if ($photo != ERROR_FILE_EMPTY && $photo != ERROR_FILE_UPLOAD && $photo != ERROR_FILE_EXTENSION) {
                $productPhotos[] = $photo;
            }
        }

        foreach ($productPhotos as $photo) {
            $res = $this->db->query("INSERT INTO `photos` (product, photo) VALUES ($productID, '$photo');");
            if (!$res) $result = 'Ошибка добавления фото ' . $photo . ' - ' . $this->db->error;
        }

        $this->db->getConnection()->commit();
        ////////
        return $result;

    }

    public function update($id, $name, $desc, $price, $newPrice, $category, $col, $files, $delPhotos, $specs, $hide)
    {
        $id = (int)$id;
        $name = $this->db->escape($name);
        $desc = $this->db->escape($desc);
        $show = !$hide;
        $show = (int)$show;

        $photo = uploadPhoto($files['main-photo']);
        $photo = $photo[0];
        if ($photo === ERROR_FILE_EMPTY) $photo = ''; else $photo = ", `main_photo` = '" . $photo . "'";
        if ($newPrice === null) $newPrice = 'NULL';

        $result = true;
        $this->db->getConnection()->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);

        $fields = "`name` = '$name', `desc` = '$desc', `price` = $price, `new_price` = $newPrice, `category` = $category, `col` = $col, `show` = $show" . $photo;
        $sql = "UPDATE `products` SET $fields WHERE `id` = $id;";
        $res = $this->db->query($sql);
        if (!$res) {
            $result = 'Ошибка обновления товара';
        }

        $_specs = array();
        $specsCount = count($specs['name']);
        for ($i = 0; $i < $specsCount; $i++) {
            $_specs[$i]['name'] = $specs['name'][$i];
            $_specs[$i]['value'] = $specs['value'][$i];
        }

        foreach ($_specs as $spec) {
            $specName = $spec['name'];
            $value = $spec['value'];
            if ((!$specName && !$value) || (!$specName && $value)) continue;
            $res = $this->db->query("INSERT INTO `specifications` (product, name, value) VALUES ($id, '$specName', '$value') ON DUPLICATE KEY UPDATE `value` = '$value';");
            if (!$res) $result = 'Ошибка добавления характеристики ' . $spec['name'];

            if ($specName && !$value) {
                $res = $this->db->query("DELETE FROM `specifications` WHERE `product` = $id AND `name` = '$specName';");
                if (!$res) $result = 'Ошибка удаления характеристики ' . $spec['name'];
            }
        }

        foreach ($delPhotos as $delPhoto) {
            $delPhoto = (int)$delPhoto;

            $res = $this->db->query("DELETE FROM `photos` WHERE `id` = $delPhoto;");
            if (!$res) {
                $result = 'Ошибка удаления фото';
            }
        }

        $photos = $files['photos'];
        $photos = uploadPhoto($photos);

        $productPhotos = array();
        foreach ($photos as $photo) {
            if ($photo != ERROR_FILE_EMPTY && $photo != ERROR_FILE_UPLOAD && $photo != ERROR_FILE_EXTENSION) {
                $productPhotos[] = $photo;
            }
        }

        foreach ($productPhotos as $photo) {
            $res = $this->db->query("INSERT INTO `photos` (product, photo) VALUES ($id, '$photo');");
            if (!$res) $result = 'Ошибка добавления фото ' . $photo . ' - ' . $this->db->error;
        }

        $this->db->getConnection()->commit();
        return $result;
    }

    public function updateCol($id, $col)
    {
        $id = (int)$id;
        $col = (int)$col;

        $res = $this->db->query("UPDATE `products` SET `col` = $col WHERE `id` = $id;");
        return $res;
    }

    public function delete($id)
    {
        $id = (int)$id;
        $res = $this->db->query("UPDATE `products` SET `del` = 1, `show` = 0 WHERE id = $id;");
        return $res;
    }

    public function exists($id)
    {
        $res = $this->get($id);
        return $res === null ? false : true;
    }

    public function count($id)
    {
        $id = (int)$id;
        $res = $this->db->query("SELECT `col` FROM `products` WHERE `id` = $id;");
        return $res ? $res[0]['col'] : 0;
    }

    public function getSpecs($id)
    {
        $id = (int)$id;
        $res = $this->db->query("SELECT * FROM `specifications` WHERE `product` = $id;");
        return $res;
    }

    public function getPhotos($id)
    {
        $id = (int)$id;
        $res = $this->db->query("SELECT * FROM `photos` WHERE `product` = $id;");
        return $res;
    }

    public function getNewProducts($limit = 10, $show = 1, $del = 1)
    {
        $limit = (int)$limit;

        if (is_array($show))
            $show = implode(',', $show);
        else
            $show = (int)$show;
        $show = '(' . $show . ')';

        $res = $this->db->query("SELECT * FROM `products` WHERE `show` IN $show AND (`del` = 0 OR `del` = $del) ORDER BY `id` DESC LIMIT $limit;");
        return $res;
    }

    public function getByCategory($category, $startPos = 0, $limit = 10, $order = 1, $orderSc = 0, $show = 1, $del = 1)
    {
        $category = (int)$category;
        $startPos = (int)$startPos;
        $limit = (int)$limit;
        $order = (int)$order;
        $orderSc = (int)$orderSc;
        $orderSc = $orderSc == 0 ? "ASC" : "DESC";

        if (is_array($show))
            $show = implode(',', $show);
        else
            $show = (int)$show;
        $show = '(' . $show . ')';

        $res = $this->db->query("SELECT * FROM `products` WHERE `category` = $category AND `show` IN $show AND (`del` = 0 OR `del` = $del) ORDER BY $order $orderSc LIMIT $startPos, $limit;");
        return $res;
    }

    public function getAll($startPos = 0, $limit = 10, $order = 1, $orderSc = 0, $show = 1, $del = 1)
    {
        $startPos = (int)$startPos;
        $limit = (int)$limit;
        $order = (int)$order;
        $orderSc = (int)$orderSc;
        $orderSc = $orderSc == 0 ? "ASC" : "DESC";

        if (is_array($show))
            $show = implode(',', $show);
        else
            $show = (int)$show;
        $show = '(' . $show . ')';

        $query = "SELECT * FROM `products` WHERE `show` IN $show AND (`del` = 0 OR `del` = $del) ORDER BY $order $orderSc LIMIT $startPos, $limit;";
        $res = $this->db->query($query);
        return $res;
    }

    public function title($id)
    {
        $id = (int)$id;
        $res = $this->get($id);
        return $res['name'] ? $res['name'] : '';
    }

    public function search($q, $startPos = 0, $limit = 10, $order = 1, $orderSc = 0, $show = 1, $del = 1)
    {
        $q = $this->db->escape($q);
        $q = trim($q);
        $startPos = (int)$startPos;
        $limit = (int)$limit;
        $order = (int)$order;
        $orderSc = (int)$orderSc;
        $orderSc = $orderSc == 0 ? "ASC" : "DESC";

        if (is_array($show))
            $show = implode(',', $show);
        else
            $show = (int)$show;
        $show = '(' . $show . ')';

        $res = $this->db->query("SELECT * FROM `products` WHERE (`name` LIKE '%" . $q . "%' OR `desc` LIKE '%" . $q . "%') AND `show` IN $show AND (`del` = 0 OR `del` = $del) ORDER BY $order $orderSc LIMIT $startPos, $limit;");
        return $res;
    }

    public function searchCount($q, $show = 1, $del = 1)
    {
        $q = $this->db->escape($q);
        $q = trim($q);

        if (is_array($show))
            $show = implode(',', $show);
        else
            $show = (int)$show;
        $show = '(' . $show . ')';

        $res = $this->db->query("SELECT COUNT(*) FROM `products` WHERE (`name` LIKE '%" . $q . "%' OR `desc` LIKE '%" . $q . "%') AND `show` IN $show AND (`del` = 0 OR `del` = $del);");
        return $res[0]['COUNT(*)'];
    }

    public function allCount($show = 1, $del = 1)
    {
        if (is_array($show))
            $show = implode(',', $show);
        else
            $show = (int)$show;
        $show = '(' . $show . ')';

        $query = "SELECT COUNT(*) FROM `products` WHERE `show` IN $show AND (`del` = 0 OR `del` = $del)";
        $res = $this->db->query($query);
        return $res[0]['COUNT(*)'];
    }

    public function getAllSpecs()
    {
        $sql = "SELECT DISTINCT `name` FROM `specifications`";
        $res = $this->db->query($sql);
        return $res;
    }

}