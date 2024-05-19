<?php
    function is_admin_login()
    {
        if(isset($_SESSION['admin_id']))
        {
            return true;
        }
        return false;
    }
?>