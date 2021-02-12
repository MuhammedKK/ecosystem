<?php
    ob_start();
    session_start();
    if(isset($_SESSION['Username'])) {
        $pageTitle = 'Dashboard';
        include 'init.php';

            // Function To Get The Count Of Anything
            $itemsCount = countItems('Userid', 'users');

            // Limit Of Users you Want
            $usersLimit = 3;
            // Leatest Registered Users
            $leatestUsers = getLeatest('*', 'users', 'Userid', $usersLimit);

            // Limit Of Itmes you Want
            $itmesLimit = 2;
            // Leatest Registered Itmes
            $leatestItems = getLeatest('*', 'items', 'it_id', $itmesLimit);

            // Limit Of Comments you Want
            $commentsLimit = 3;
            // Leatest Registered Comments
            $leatestCommentss = getLeatest('*', 'comments', 'c_id', $commentsLimit);

        ?>

        <!-- Start Dashboard Section -->
        <!-- Start Stats Section -->
        <div class="stats text-center">
            <div class="container">
                <h1>Dashboard</h1>
                <div class="row">
                    <div class="col-md-3">
                        <div class="stat total-members">
                            <i class="fa fa-users"></i>
                            Total Members
                            <span><a href="members.php"><?php echo $itemsCount; ?></a></span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat pending-members">
                            <i class="fa fa-clock-o"></i>
                            Pending Members
                            <span><a href="members.php?action=manage&page=pending">
                                <?php echo checkItem('Regstatus', 'users', 0); ?>
                            </a></span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat total-items">
                            <i class="fa fa-tag"></i>
                            Total Items
                            <span><a href="items.php?action=manage">
                                <?php echo countItems('it_id', 'items'); ?>
                            </a></span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat total-cats">
                            <i class="fa fa-comments"></i>
                            Total Comments
                            <span><a href="comments.php?action=manage">
                             <?php echo countItems('c_id', 'comments'); ?>
                            </a></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Stats Section -->

        <!-- Start Panel Section -->
        <div class="dash-panel">
            <div class="container">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="panel panel-default">
                            <div class="panel-heading"><i class="fa fa-users"></i> Leatest
                            Registered Users
                            <i class="fa fa-plus pull-right"></i>
                        </div>
                            <div class="panel-body">
                                <ul>
                                <?php
                                    foreach($leatestUsers as $users) {
                                        echo '<li>';
                                        echo        '<span>' .  $users['Username'] . '</span>';
                                        echo        '<a href="#" class="btn btn-success pull-right"><i class="fa fa-edit"></i> Edit</a>';
                                        if($users['Regstatus'] == 0) {
                                            echo "<a href='members.php?action=approve&Userid=". $users['Userid'] ."' class='btn btn-primary pull-right'><i class='fa fa-info'></i> Approve</a>";
                                        }       
                                        echo        '</li>' . '<br>';
                                    }
                                ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="panel panel-default">
                            <div class="panel-heading"><i class="fa fa-tag"></i>
                            Least Added Items
                            <i class="fa fa-plus pull-right"></i>
                            </div>
                            <div class="panel-body">
                            <ul class="list_unstyled">
                                <?php
                                        foreach($leatestItems as $item) {
                                            echo '<li>';
                                            echo        '<span>' .  $item['it_name'] . '</span>';
                                            echo        '<a href="items.php?action=edit&it_id='. $item['it_id'] .'" class="btn btn-success pull-right"><i class="fa fa-edit"></i> Edit</a>';
                                            if($item['approve'] == 0) {
                                                echo "<a href='items.php?action=approve&it_id=". $item['it_id'] ."' class='btn btn-primary pull-right'><i class='fa fa-info'></i> Approve</a>";
                                            }       
                                            echo        '</li>' . '<br>';
                                        }
                                    ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="panel panel-default">
                            <div class="panel-heading"><i class="fa fa-users"></i> Leatest
                            Comments
                            <i class="fa fa-plus pull-right"></i>
                        </div>
                            <div class="panel-body">
                                <?php
                                    // Select Query To Fetch all Comments
                                    $stmt = $con->prepare("SELECT
                                                                comments.*, users.Username As Client 
                                                            FROM 
                                                                comments
                                                            INNER JOIN users
                                                            ON
                                                                users.Userid = comments.user_id
                                                            ORDER BY c_id DESC LIMIT $commentsLimit");
                                    $stmt->execute();
                                    $comments = $stmt->fetchAll();
                                    foreach($comments as $comment) {
                                        echo '<div class="comment-box">';
                                            echo '<a href="members.php?action=edit&Userid='. $comment['user_id'] .'" class="member-name">'. $comment['Client'] .'</a>';
                                            echo '<p class="member-comment">'. $comment['c_content'] .'</p>';
                                            echo '<div class="comment-actions">';
                                                echo '<a class="btn btn-success" href="comments.php?action=edit&c_id='. $comment['c_id'] .'"><i class="fa fa-edit"></i> Edit</a>';
                                                echo ' <a class="btn btn-danger confirm" href="comments.php?action=delete&c_id='. $comment['c_id'] .'"><i class="fa fa-close"></i> Delete</a>';
                                                if($comment['c_status'] == 0) {
                                                    echo ' <a class="btn btn-primary" href="comments.php?action=approve&c_id='. $comment['c_id'] .'"><i class="fa fa-info"></i> Approve</a>';
                                                }
                                            echo '</div>';
                                        echo '</div>';
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Panel Section -->

        <!-- End Dashboard Section -->


    <?php 
        include $Tpl . 'footer.php';
    } else {
        header("Location: index.php");
        exit;
    }
    ob_end_flush();
?>