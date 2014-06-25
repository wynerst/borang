<?php
/**
 * utility class
 * A Collection of static utility methods
 *
 * Copyright (C) 2007,2008  Arie Nugraha (dicarve@yahoo.com)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 */

class utility {
    /**
     * Static Method to send out javascript alert
     *
     * @param   string  $str_message
     * @return  void
     */
    public static function jsAlert($str_message)
    {
        if (!$str_message) {
            return;
        }

        // replace newline with javascripts newline
        $str_message = str_replace("\n", '\n', $str_message);
        echo '<script type="text/javascript">'."\n";
        echo 'alert("'.addslashes($str_message).'")'."\n";
        echo '</script>'."\n";
    }


    /**
     * Static Method to create random string
     *
     * @param   int     $int_num_string: number of randowm string to created
     * @return  void
     */
    public static function createRandomString($int_num_string = 32)
    {
      $_random = '';
      $_salt = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
      for ($r = 0; $r < $int_num_string; $r++) {
        $_random .= $_salt[mt_srand(strlen($_salt))];
      }

      return $_random;
    }


    /**
     * Static Method to check privileges of application module form current user
     *
     * @param   string  $str_module_name
     * @param   string  $str_privilege_type
     * @return  boolean
     */
    public static function havePrivilege($str_module_name, $str_privilege_type = 'r')
    {
        // checking checksum
        $server_addr = isset($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : (isset($_SERVER['LOCAL_ADDR']) ? $_SERVER['LOCAL_ADDR'] : gethostbyname($_SERVER['SERVER_NAME']));
        $_checksum = defined('UCS_BASE_DIR')?md5($server_addr.UCS_BASE_DIR.'admin'):md5($server_addr.SENAYAN_BASE_DIR.'admin');
        if ($_SESSION['checksum'] != $_checksum) {
            return false;
        }
        // check privilege type
        if (!in_array($str_privilege_type, array('r', 'w'))) {
            return false;
        }
        if (isset($_SESSION['priv'][$str_module_name][$str_privilege_type]) AND $_SESSION['priv'][$str_module_name][$str_privilege_type]) {
            return true;
        }
        return false;
    }


    /**
     * Static Method to write application activities logs
     *
     * @param   object  $obj_db
     * @param   string  $str_log_type
     * @param   string  $str_value_id
     * @param   string  $str_location
     * @param   string  $str_log_msg
     * @return  void
     */
    public static function writeLogs($obj_db, $str_log_type, $str_value_id, $str_location, $str_log_msg)
    {
        if (!$obj_db->error) {
            // log table
            $_log_date = date('Y-m-d H:i:s');
            $_log_table = 'system_log';
            // filter input
            $str_log_type = $obj_db->escape_string(trim($str_log_type));
            $str_value_id = $obj_db->escape_string(trim($str_value_id));
            $str_location = $obj_db->escape_string(trim($str_location));
            $str_log_msg = $obj_db->escape_string(trim($str_log_msg));
            // insert log data to database
            @$obj_db->query('INSERT INTO '.$_log_table.'
            VALUES (NULL, \''.$str_log_type.'\', \''.$str_value_id.'\', \''.$str_location.'\', \''.$str_log_msg.'\', \''.$_log_date.'\')');
        }
    }
}
