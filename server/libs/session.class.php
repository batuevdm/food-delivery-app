<?php

class Session
{

    public static function setMessage($message, $type = 'info')
    {
        self::set('__msg', $message);
        self::set('__type', $type);
    }

    public static function hasMessage()
    {
        return !is_null(self::get('__msg'));
    }

    public static function message()
    {
        echo self::get('__msg');
        self::set('__msg', null);
    }

    public static function messageType()
    {
        echo self::get('__type');
    }

    public static function setField($key, $value)
    {
        self::set('__' . $key, $value);
    }

    public static function field($key, $return = false)
    {
        $val = self::get('__' . $key);
        self::set('__' . $key, '');
        if( $return ) return $val;
        echo $val;
    }

    public static function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public static function get($key)
    {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
    }

    public static function delete($key)
    {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }

    public static function destroy()
    {
        session_destroy();
    }

}