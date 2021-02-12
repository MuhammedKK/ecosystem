<?php
    session_start();
    $pageTitle = 'Categories';
    include 'init.php';
    $pageName = isset($_GET['pageName']) ? $_GET['pageName'] : '';
    $cat_id = isset($_GET['pageID']) ? $_GET['pageID'] : '';
    $allItems = allItems('cat_id', $cat_id);
?>

<div class="categories">
    <div class="container">
        <h1 class="text-center"><?php echo $pageTitle; ?></h1>
        <div class="row">

        <?php
        if(!empty($allItems)) {
            foreach($allItems as $item) {
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
                        echo '<a href="adShow.php?itemid='. $item['it_id'] .'" class="item-name">'. $item['it_name'] .'</a>';
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
    include $Tpl . 'footer.php';
?>