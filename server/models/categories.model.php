<?php

class CategoriesModel extends Model
{
    public function get($sub = false)
    {
        if ($sub) {
            $categories = $this->db->query("SELECT * FROM `categories`;");
            $categoriesList = array();
            foreach ($categories as $category) {
                if (!$category['parent']) {
                    $categoriesList[$category['id']] = $category;
                    $categoriesList[$category['id']]['subs'] = array();
                    $categoriesList[$category['id']]['col'] = $this->productsCount($category['id']);
                } else {
                    if ($categoriesList[$category['parent']]) {
                        $categoriesList[$category['parent']]['subs'][$category['id']] = $category;
                        $categoriesList[$category['parent']]['subs'][$category['id']]['col'] = $this->productsCount($category['id']);
                    }
                }
            }

            return $categoriesList;
        } else {
            return $this->db->query("SELECT `id`, `name` FROM `categories` WHERE `parent` IS NULL;");
        }
    }

    public function getAll()
    {
        return $this->db->query("SELECT * FROM `categories`;");
    }

    public function add($name, $parent, $image)
    {
        $parent = (int)$parent;
        if ($parent < 0) $parent = 'NULL';
        $name = $this->db->escape($name);

        $photo = uploadPhoto($image);
        $photo = $photo[0];
        if ($photo === ERROR_FILE_EMPTY) $photo = 'NULL'; else $photo = "'" . $photo . "'";

        $res = $this->db->query("INSERT INTO `categories` (`name`, `parent`, `image`) VALUES ('$name', $parent, $photo);");
        return $res;
    }

    public function getOne($id)
    {
        $id = (int)$id;
        $res = $this->db->query("SELECT * FROM `categories` WHERE `id` = $id;");
        return isset($res) ? $res[0] : null;
    }

    public function edit($id, $name, $parent, $image)
    {
        $id = (int)$id;
        $parent = (int)$parent;
        if ($parent < 0) $parent = 'NULL';
        $name = $this->db->escape($name);

        $photo = uploadPhoto($image);
        $photo = $photo[0];
        if ($photo === ERROR_FILE_EMPTY) $photo = ''; else $photo = ", `image` = '" . $photo . "'";

        $res = $this->db->query("UPDATE `categories` SET `name` = '$name', `parent` = $parent $photo WHERE `id` = $id;");
        return $res;
    }

    public function delete($id)
    {
        $id = (int)$id;
        $res = $this->db->query("DELETE FROM `categories` WHERE `id` = $id");
        return $res;
    }

    public function exists($id)
    {
        $id = (int)$id;
        $res = $this->db->query("SELECT `id` FROM `categories` WHERE `id` = $id;");
        return $res ? true : false;
    }

    public function parent($id)
    {
        $id = (int)$id;
        $res = $this->db->query("SELECT `parent` FROM `categories` WHERE `id` = $id;");
        return $res ? $res[0]['parent'] : null;
    }

    public function subcategories($id)
    {
        $id = (int)$id;
        $res = $this->db->query("SELECT * FROM `categories` WHERE `parent` = $id;");
        return $res;
    }

    public function name($id)
    {
        $id = (int)$id;
        $res = $this->db->query("SELECT `name` FROM `categories` WHERE `id` = $id;");
        return $res ? $res[0]['name'] : null;
    }

    public function title($id)
    {
        $id = (int)$id;
        $res = $this->name($id);
        return $res ? $res : '';
    }

    public function productsCount($id)
    {
        $id = (int)$id;
        $res = $this->db->query("SELECT COUNT(*) FROM products WHERE category = $id AND `show` = 1 AND `del` = 0;");
        return $res[0]['COUNT(*)'];
    }
}