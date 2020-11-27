<?php

    $book_id = $_GET['id'];

    $cart_string = file_get_contents("cart.json");
    $cart_data = json_decode($cart_string, true);

    $data = file_get_contents("books.json");
    $book_data = json_decode($data, true);
    $resulting_array = array();

    $arr_index = array();
    foreach($cart_data as $key => $value) {
        if($value["book_id"] == $book_id)
            $arr_index[] = $key;
    }

    foreach($arr_index as $i) {
        unset($cart_data[$i]);
    }

    $cart_data = array_values($cart_data);
    $cart_count = count($cart_data);

    $json_data = json_encode($cart_data, JSON_PRETTY_PRINT);
    file_put_contents("cart.json", $json_data);
?>

<table>
    <th>Book Title</th>
    <th>Qty</th>
    <th>Price</th>
    <th></th>
    <?php
        $total_price = 0;
        if($cart_data != null) {
            foreach($cart_data as $cart_item) {
                $cart_id = $cart_item["book_id"];
                $total_price += $cart_item["price"];
    ?>
        <tr>
            <?php
                foreach($book_data as $book_value) {
                    if($book_value["book_id"] == $cart_id)
                        $resulting_array = $book_value;
                }
            ?>
            <td><?=$resulting_array["book_title"]?></td>
            <td><input type="number" id = "qty" value = "<?=$cart_item["quantity"]?>", min = "1", step = 1></td>
            <td><?=$cart_item["price"]?></td>
            <td><button onclick="deleteItemFromCart(<?=$cart_id?>)">Remove Item</button></td>
        </tr>
    <?php
            }
        }
    ?>
</table>

