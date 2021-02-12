<?php
ob_start();
session_start();
$pageTitle = 'Tags';
include 'init.php';

if(isset($_GET['tagName'])) {
    $tag = $_GET['tagName'];
    // Get Items That's share The Same Tag
    $itemTaged = getAll("*", "items", "WHERE tags LIKE  '%$tag%' ", "AND approve=1", "it_id");
    
?>


<div class="tags">
    <div class="container">
        <h1 class="text-center"><?php echo 'Items Taged' ?></h1>
        <div class="row">

        <?php
        if(!empty($itemTaged)) {
            foreach($itemTaged as $item) {
                echo '<div class="col-md-3 col-sm-6">';
                    echo '<div class="thumbnail item text-center">';
                        if($item['approve'] == 0) {
                            echo '<div class="unapproval">Unapproval</div>';
                        }
                        echo '<span class="item-price">$'. $item['it_price'] .'</span>';
                        if(!empty($item['image'])) {
                            echo '<img src="admin/data/uploads/items/'. $item['image'] .'" alt="">';
                        } else {
                            echo '<img src="admin/data/uploads/items/item-img.jpg" alt="">';
                        }
                        echo '<a href="adShow.php?itemid='. $item['it_id'] .'&userid='. $_SESSION['id'] .'" class="item-name">'. $item['it_name'] .'</a>';
                        echo '<p class="item-desc">'. $item['it_desc'] .'</p>';
                    echo '</div>';
                echo '</div>';
            }
        } else {
            echo '<div class="alert alert-danger">This Categorie Haven\'t Any Item Yet <a class="btn btn-primary" href="newAd.php">Add Item Now</a></div>';
        }
        ?>

        </div>
    </div>
</div>



<?php
    }
    include $Tpl . 'footer.php';
    ob_end_flush();
?>