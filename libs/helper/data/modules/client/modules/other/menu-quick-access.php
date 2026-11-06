<?php
$menu_quick_access = $db->get_list("SELECT * FROM `menu_quick_acces` WHERE `status` = 1 ORDER BY `id` ASC");
$show_menu_quick_access = !empty($menu_quick_access);
