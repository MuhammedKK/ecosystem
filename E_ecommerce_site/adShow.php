<?php

session_start();
$pageTitle = 'Ad Details';
include 'init.php';
    
if(isset($_GET['itemid']) && is_numeric($_GET['itemid'])) {

    $itemid = $_GET['itemid'];
    
    // Query To Get Item Data

    $stmt = $con->prepare("SELECT
                                    items.*, categories.cat_name, users.Username
                            FROM 
                                    items
                            INNER JOIN 
                                categories 
                            ON 
                                categories.cat_id = items.cat_id
                            INNER JOIN
                                users
                            ON
                                users.Userid = items.user_id
        WHERE it_id = ?");
    $stmt->execute(array($itemid));
    $itemData = $stmt->fetch();
    
    if($itemData) { ?>

    <div class="item-show">
        <div class="container">
            <h1 class="text-center"><?php echo $itemData['it_name']; ?></h1>
            <div class="row">
                <div class="col-md-3">
                    <div class="item-img">
                        <img src='admin/data/uploads/items/<?php if(!empty($itemData['image'])) {echo $itemData['image'];} 
                        else {
                        echo 'item-img.jpg';
                        } ?>' alt="my Image"/>
                    </div>
                </div>
                <div class="col-md-9">
                    <ul class="list-unstyled list-group">
                        <li class="list-group-item active"><span>Item Name</span> : <a href="#"><?php echo $itemData['it_name']; ?></a></li>
                        <li class="list-group-item"><span>Description</span> : <a href="#"><?php echo $itemData['it_desc']; ?></a></li>
                        <li class="list-group-item"><span>Price</span> : <a href="#">$<?php echo $itemData['it_price']; ?></a></li>
                        <li class="list-group-item"><span>Date</span>: <a href="#"><?php echo $itemData['add_date']; ?></a></li> 
                        <li class="list-group-item"><span>Country</span>: <a href="#"><?php echo $itemData['country_made']; ?></a></li>                            
                        <li class="list-group-item"><span>Categorie</span>: <a href="#"><?php echo $itemData['cat_name']; ?></a></li>                            
                        <li class="list-group-item"><span>User</span>: <a href="#"><?php echo $itemData['Username']; ?></a></li>                            
                        <li class="list-group-item tags"><span>Tags</span>: 
                            <?php
                                $tags = explode(',', $itemData['tags']);
                                foreach($tags as $tag) {
                                    echo '<a href="tags.php?tagName='. $tag .'" class="tag">'. $tag .'</a> ';
                                }
                            ?>
                        </li>                            

                    </ul>
                </div>
            </div>
            <hr>
            <?php
            
            if(isset($_SESSION['user'])) {

            ?>
            <div class="row">
                <div class="col-md-offset-3">
                    <div class="adding-comment">
                        <h4>Add Your Comment</h4>
                        <form action="<?php $_SERVER['PHP_SELF']; ?>" method="POST">
                            <textarea name="ad-comment" cols="50" rows="5" class="ad-comment" placeholder="Your Comment"></textarea>
                            </i><input class="btn btn-primary" type="submit" name="submit" value="+ Add Comment"/>
                        </form>
                    </div>
                </div>
            </div>
            <?php

            if($_SERVER['REQUEST_METHOD'] == 'POST') {
                // Validate Inputs To Insert In DB
                $comment = filter_var($_POST['ad-comment'], FILTER_SANITIZE_STRING);
                $userid = $_SESSION['id'];

                // Query To Insert Comment
                $stmt = $con->prepare("INSERT INTO comments (c_content, c_status, c_date, item_id, user_id) VALUES (?, 0, now(), ?, ?)");
                $stmt->execute(array($comment, $itemid, $userid));
                if($stmt) {
                    echo '<div class="alert alert-success">Comment Added Successfully</div>';
                }
            }

            } else {
                echo '<div class="col-md-offset-3">';
                    echo 'You Must Be <a href="login.php">Login</a> To Comment Or <a href="login.php">Signup</a> Now! ';
                echo '</div>';
            }
            ?>
            <hr>
            <?php

                // Fetch All Comments Data Do Show Here
                $stmt = $con->prepare("SELECT 
                                            comments.*, items.it_name, users.Username
                                        FROM
                                            comments
                                        INNER JOIN
                                            items
                                        ON
                                            comments.item_id = items.it_id
                                        INNER JOIN
                                            users
                                        ON
                                            comments.user_id = users.Userid
                                        WHERE c_status = 1 AND item_id = ?
                                        ORDER BY c_id");
                $stmt->execute(array($itemid));
                $comments = $stmt->fetchAll();

            ?>
            <h3>Comments</h3>
            <?php
            if($comments) {
                foreach($comments as $comment) { ?>
                                
                  
            <div class="row">
                <div class="col-md-4">
                    <div class="item-img">
                        <img class="img-responsive img-thumbnail img-circle center-block" src="imgs/item-img.jpg" alt="">
                        <h5 class="text-center"><?php echo $comment['Username']; ?></h5>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="comment-box">  
                        <p class="member-comment"> <?php echo $comment['c_content'] ?> </p>
                    </div>
                </div>
            </div>
            <?php 
            }   
        } else {
            echo '<div class="alert alert-danger">This Item Haven\'t Any Comments Or Comments Not Approved Yet <a class="btn btn-" href="index.php">Explore Items Now</a></div>';
        }
            ?>
        </div>
    </div>

    <?php }

} else {
    echo '<div class="alert alert-danger">Theres No Such Id LIke This or Item Waiting To Approval</div>';
}

include $Tpl . 'footer.php';

?>