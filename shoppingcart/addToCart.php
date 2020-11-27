<?php

    $book_id = $_GET['book_id'];

    $cart_string = file_get_contents("cart.json");
    $cart_data = json_decode($cart_string, true);
    
    $data = file_get_contents("books.json");
    $book_data = json_decode($data, true);
    $resulting_array = array();

    foreach($book_data as $book) {
        if($book["book_id"] == $book_id)
            $resulting_array = $book;
    }

    try {

        $json_data = file_get_contents("cart.json");

        if($json_data != null)
            $arr_data = json_decode($json_data, true);

        $arr_index = array();
        foreach($cart_data as $key => $value) {
            if($value["book_id"] == $book_id) {
                echo "<script>window.location='bookdetails.php?book_id=".$book_id."';</script>";
                return;
            }
        }

        $input_data = array(
            'book_id' => $book_id,
            'quantity' => 1,
            'price' => $resulting_array["price"]
        );

        array_push($arr_data, $input_data);

        $json_data = json_encode($arr_data, JSON_PRETTY_PRINT);

        if(file_put_contents("cart.json", $json_data)) {
	        echo "<script>alert('Added to Cart!');window.location='bookdetails.php?book_id=".$book_id."';</script>";
	    }
	    else 
	        echo "<script>alert('Cannot be added to cart.');window.location='bookdetails.php?book_id=".$book_id."';</script>";

    }

    catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
    }
?>