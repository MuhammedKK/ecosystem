<?php
session_start();
$pageTitle = 'Profile';

include 'init.php';

if(isset($_SESSION['user'])) {

    $userInfo = $con->prepare("SELECT * FROM users WHERE Username= ?");
    $userInfo->execute(array($userSession));
    $info = $userInfo->fetch();
    
}
?>

<div class="user-info">
    <div class="container">
        <div class="panel panel-primary info">
            <div class="panel-heading">
                <i class="fa fa-info-circle"></i> Information
            </div>
            <div class="panel-body">
                <ul class="list-unstyled">
                    <li><i class="fa fa-unlock-alt"></i> My Name is: <a href="#"><?php echo $info['Username'] ?></a></li>
                    <li><i class="fa fa-envelope"></i> My Email is: <a href="#"><?php echo $info['Email'] ?></a></li>
                    <li><i class="fa fa-user"></i> My FullName is: <a href="#"><?php echo $info['FullName'] ?></a></li>
                    <li><i class="fa fa-clock-o"></i> My Register Date is: <a href="#"><?php echo $info['date'] ?></a></li>
                    <li><i class="fa fa-tags"></i> My Registration Status: <a href="#"><?php if($info['Regstatus'] == 1) {echo 'Approved';} else {echo 'Not Approve Yet';} ?></a></li>
                </ul>
            </div>
        </div>
        <div class="panel panel-primary ads">
            <div class="panel-heading">
            <i class="fa fa-plus"></i> <span>Letest Ads</span>
            </div>
            <div class="panel-body">
                <div class="row">
                    <?php
                    $allItems =allItems('user_id', $info['Userid']);
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
                                        echo '<a href="adShow.php?itemid= '. $item['it_id'] .'" class="item-name">'. $item['it_name'] .'</a>';
                                        echo '<p class="item-desc">'. $item['it_desc'] .'</p>';
                                    echo '</div>';
                                echo '</div>';
                            }
                        } else {
                            echo '<p>There\'s No Items To Show</p>';
                        }
                    ?>
                </div>
            </div>
        </div>
        <div class="panel panel-primary comments">
            <div class="panel-heading">
                <i class="fa fa-comments-o"></i> Letest Comments
            </div>
            <div class="panel-body">
                <div class="row">
                    <?php
                        // Get All User's Comments
                        $stmt = $con->prepare("SELECT comments.*, users.Username FROM comments INNER JOIN users ON comments.user_id = users.Userid WHERE user_id=? Order By c_id DESC ");
                        $stmt->execute(array($info['Userid']));
                        $comments = $stmt->fetchAll();

                        if(!empty($comments)) {
                            foreach($comments as $comment) {
                                
                    ?>
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
                    <div class="clearfix"></div>
                    <?php 
                        }
                    } ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
                        
include $Tpl . 'footer.php';

?>