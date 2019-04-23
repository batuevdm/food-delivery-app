<?php

class PhotoModel extends Model
{
    public function get($id)
    {
        $id = (int)$id;
        $res = $this->db->query("SELECT * FROM photos WHERE id = $id");
        return $res ? $res[0] : null;
    }

}