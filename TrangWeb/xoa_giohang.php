<?php
    session_start();

    if (isset($_GET['action'])) 
    {
        
        if ($_GET['action'] == 'remove' && isset($_GET['id'])) 
        {
            $cart_item_id_to_remove = $_GET['id'];
            
            if (isset($_SESSION['cart'][$cart_item_id_to_remove])) 
            {
                unset($_SESSION['cart'][$cart_item_id_to_remove]);
            }
        }
    }
    header('Location: giohang.php');
    exit;
?>