<html>
    <head>
        <title>ABC Bookstore</title>
        <link href = "styles/bootstrap.css" rel = "stylesheet">
        <link href = "styles/hotbar.css" rel = "stylesheet">
        <link href = "styles/mainpage.css" rel = "stylesheet">
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
        <form method = "GET">
            <input type = "text" id = "searchBar" placeholder = "Enter keywords here...">
            <button id = "searchButton">Search</button>
            <div class = "sortList">
                <button id = "sortButton">Sort By:</button>
                <div class = "sortList-content">
                    <a link href>Title</a>
                    <a link href>Book ID</a>
                    <a link href>Date Published</a>
                    <a link href>Rating (Ascending)</a>
                    <a link href>Rating (Descending)</a>
                </div>
            </div>
        </form>
        <br>
        <div class = "bookdetails">
            <table>
                <th>
                    BOOK ID
                </th>
                <th>
                    TITLE
                </th>
                <th>
                    AUTHOR/S
                </th>
                <th>
                    DATE PUBLISHED
                </th>
                <th>
                    STOCK
                </th>
                <th>
                    RATING
                </th>
                <th>
                    LINK
                </th>
                <?php
                    $data = file_get_contents("books.json");
                    $new_data = json_decode($data, true);
                    foreach($new_data as $book_value) {
                ?>
                    <tr>
                        <td>
                            <?=$book_value["book_id"]?>
                        </td>
                        <td>
                            <?=$book_value["book_title"]?>
                        </td>
                        <td>
                            <?=implode(", ",  $book_value["authors"]);?>
                        </td>
                        <td>
                            <?=$book_value["publish_date"]?>
                        </td>
                        <td>
                            <?=$book_value["stock"]?>
                        </td>
                        <td>
                            <?=$book_value["rating"]?>
                        </td>
                        <td>
                            <a link href="bookdetails.php?book_id=<?=$book_value["book_id"]?>">View Item</a>
                        </td>
                </tr>
            <?php
                }
            ?>
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
                                    foreach($new_data as $book_value) {
                                        if($book_value["book_id"] == $cart_id)
                                            $resulting_array = $book_value;
                                    }
                                ?>
                                <td><?=$resulting_array["book_title"]?></td>
                                <td><input type="number" id = "qty" value = "<?=$cart_item["quantity"]?>", min = 1, step = 1></td>
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
</html>