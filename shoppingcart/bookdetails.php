<?php
    $data = file_get_contents("books.json");
    $book_data = json_decode($data, true);

    $book_id = $_GET['book_id'];

    $resulting_array = array();

    foreach($book_data as $book) {
        if($book["book_id"] == $book_id)
            $resulting_array = $book;
    }
?>

<html>
   <head>
       <title><?=$resulting_array["book_title"]?></title>
       <link href = "styles/bootstrap.css" rel = "stylesheet">
       <link href = "styles/hotbar.css" rel = "stylesheet">
       <link href = "styles/bookdetails.css" rel = "stylesheet">
   </head>
   <body>
        <div class = "hotbar">
            <a link href = "mainpage.php"  id = "homeText">ABC Bookstore</a>
            <a link href = "#" id = "cartLink">
                <?php
                    $cart_string = file_get_contents("cart.json");
                    $cart_data = json_decode($cart_string, true);
                    $cart_count = count($cart_data);
                ?>Cart(<?=$cart_count?>)
            </a>
        </div>
        <br><br><br>
        <div class = "bookDetails">
            <table>
                <tr>
                    <td>
                    <div class = "bookDetails1">
                        <div class = "container"><img src = "<?=$resulting_array["book_img"]?>"></div><br>
                        <h4>Rating:</h4><div class = "rating"><?=$resulting_array["rating"]?>/5</div><br>
                        <a link href = "addToCart.php?book_id=<?=$book_id?>">Add to Cart</a><br>
                        <a link href>Buy Now!</a>
                    </div>
                    </td>
                    <td>
                    <div class = "bookDetails2">
                        <h2><?=$resulting_array["book_title"]?></h2>
                        <dl>
                            <dt>Authors:</dt>
                            <dd><?=implode(", ",  $resulting_array["authors"]);?></dd>
                            <dt>Publisher:</dt>
                            <dd><?=$resulting_array["publisher"]?></dd>
                            <dt>Publication Date:</dt>
                            <dd><?=$resulting_array["publish_date"]?></dd>
                            <dt>Brief Description</dt>
                            <dd><p><?=$resulting_array["description"]?></p></dd>
                            <dt>Retail Price:</dt>
                            <dd><?=$resulting_array["price"]?></dd>
                        </dl>
                    </div>
                    </td>
                </tr>
            </table>
            
            
        </div>
        <div id = "modal" class = "cart_modal">
            <div class = "cart_content">
                <div class = "cart_header">
                    <span class = "close">&times;</span>
                    <h3>CHECKOUT</h3>
                    <h4>Your Cart</h4>
                </div>
                <div class = "cart_body">
                    <table id = "record">
                        <!--cart data goes here-->
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
                </div>
                <div class = "cart_footer">
                    <div>
                        <label>TOTAL PRICE</label>
                        <label><?=$total_price?></label>
                    </div>
                    <div class = "buttonset">
                        <button id = "closeBtn">Close</button>
                        <button id = "checkBtn">Proceed to Checkout</button>
                    </div>
                </div>
            </div>
        </div>

    <script>
        var modal = document.getElementById("modal");

        var cartLink = document.getElementById("cartLink");

        var span = document.getElementsByClassName("close")[0];
        var closeBtn = document.getElementById("closeBtn");

        cartLink.onclick = function() {
            modal.style.display = "block";
        }

        span.onclick = function() {
            modal.style.display = "none";
        }

        closeBtn.onclick = function() {
            modal.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>

   </body>

   <style>
       table {
            margin: auto;
            width: 50%;
            border: 2px solid black;    
        }

        tr:nth-child(even) {
            background-color: blanchedalmond;
        }

        tr:nth-child(odd) :not(th) {
            background-color: cornsilk;
        }

        th:nth-child(odd) {
            background-color: khaki;
        }

        th:nth-child(even) {
            background-color: mintcream;
        }

        th, td:not(#qty){
            text-align: center;
            border: 10px;
            max-width: 100px;
            overflow: hidden;
        }

        th {
            padding-top: 3px;
            padding-bottom: 3px;
        }

        #qty {
            max-height: 30px;
            overflow: auto;
            width:50px;
        }
   </style>

    <script src = "js/jquery.js"></script>

    <script>
        function deleteItemFromCart(id) {
            $.ajax(
                {
                    method: "GET",
                    url: "deleteFromCart.php",
                    data: {
                        "id" : id
                    },
                    success: function(result) {
                        $("#record").html(result);
                    }
                }
            );
        }
    </script>
</html>