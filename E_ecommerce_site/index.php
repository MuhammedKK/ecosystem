<?php
    session_start();
    $pageTitle = 'Homepage';
    include 'init.php';


    $all = getAll('*', 'items', 'WHERE approve= 1', "", 'it_id');

    if(!empty($all)) {?>
        <div class="container items">
            <div class="row">
                <?php
                foreach($all as $item) {
                ?>
                    
                        <div class="col-md-3 col-sm-6">';
                            <div class="thumbnail item text-center">
                                <?php
                                if($item['approve'] == 0) {
                                    echo '<div class="unapproval">Unapproval</div>';
                                }
                                ?>
                                <span class="item-price">$<?php echo $item['it_price'];?></span>
                                <img src='admin/data/uploads/items/<?php if(!empty($item['image'])) {echo $item['image'];} 
                                else {
                                echo 'item-img.jpg';
                                } ?>' alt="my Image"/>
                                <a href="adShow.php?itemid=<?php echo $item['it_id'];?>&userid=<?php echo $_SESSION['id'];?>" class="item-name"><?php echo $item['it_name'];?></a>
                                <p class="item-desc"><?php echo $item['it_desc'];?></p>
                            </div>
                        </div>
                <?php } ?>
            </div>
        </div>
   <?php } else {
        echo '<div class="alert alert-danger">This Categorie Haven\'t Any Item Yet <a class="btn btn-primary" href="newAd.php">Add Item Now</a></div>';
    }
    
    include $Tpl . 'footer.php';

?>