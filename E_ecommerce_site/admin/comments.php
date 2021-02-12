<?php

    ob_start();
    session_start();
    $pageTitle = 'Comments';
    if(isset($_SESSION['Username'])) {
        include 'init.php';

        $action = isset($_GET['action']) ? $_GET['action'] : 'manage';

        if($action == 'manage') {
            // Select Query To Fetch all Comments
            $stmt = $con->prepare("SELECT
                                        comments.*, items.it_name AS Item, users.Username As Client 
                                    FROM 
                                        comments
                                    INNER JOIN items
                                    ON
                                        items.it_id = comments.item_id
                                    INNER JOIN users
                                    ON
                                        users.Userid = comments.user_id 
                                    ORDER BY c_id DESC");
            $stmt->execute();
            $comments = $stmt->fetchAll();
            ?>
                
            <h1 class="text-center">Manage Comments</h1>
            <div class="container">
                <table class="table-bordered main-table text-center table table-responsive">
                    <tr>
                        <td>#ID</td>
                        <td>Comment</td>
                        <td>Comment Status</td>
                        <td>Comment Date</td>
                        <td>Item</td>
                        <td>Client</td>
                        <td>Actions</td>
                    </tr>
                    <?php 
                    foreach($comments as $comment) { ?>
                    <tr>
                        <td><?php echo $comment['c_id'] ?></td>
                        <td><?php echo $comment['c_content'] ?></td>
                        <td><?php echo $comment['c_status'] ?></td>
                        <td><?php echo $comment['c_date'] ?></td>
                        <td><?php echo $comment['Item'] ?></td>
                        <td><?php echo $comment['Client'] ?></td>
                        <td>
                            <a href="comments.php?action=edit&c_id=<?php echo $comment['c_id']; ?>" class="btn btn-success"><i class="fa fa-edit"></i> Edit</a>
                            <a href="comments.php?action=delete&c_id=<?php echo $comment['c_id']; ?>" class="btn btn-danger confirm"><i class="fa fa-close"></i> Delete</a>
                        <?php
                            if($comment['c_status'] == 0) {
                                echo "<a href='?action=approve&c_id=". $comment['c_id'] ."' class='btn btn-primary'><i class='fa fa-info'></i> Approve</a>";
                            }
                        ?>
                        </td>

                    </tr>
                    <?php } ?>
                </table>
            </div>
            
            <?php
        } elseif($action == 'edit') {
            $commentId = (isset($_GET['c_id']) && is_numeric($_GET['c_id']) ? intval($_GET['c_id']) : 0);

            // Fetch Data That's Related With This Id 
            $stmt = $con->prepare("SELECT * FROM comments WHERE c_id= ?");
            $stmt->execute(array($commentId));
            $comments = $stmt->fetch();
            $commentsCount = $stmt->rowCount();
            if($commentsCount > 0) {  // Then There's Data So I 'll Make My Form To Edit The Comment ?>

                
                <h1 class="text-center">Edit Comment</h1>
                <div class="container">
                    <form action="?action=update" class="form-horizontal" method="POST">
                        <input type="hidden" value="<?php echo $comments['c_id']; ?>" name="c_id" />
                        <div class="form-group">
                            <label for="" class="control-label col-sm-2">Comment</label>
                            <input type="text" name="comment" value="<?php echo $comments['c_content'] ?>" autocomplete="off" class="form-control col-sm-10 input-lg" placeholder="Enter Your Comment Plz"/>
                        </div>
                        <div class="form-group">
                            <input type="submit" value="Save Changes" class="btn btn-lg btn-success col-sm-12"/>
                        </div>
                    </form>
                </div>                


            <?php }
        } elseif($action == 'update') {
            if($_SERVER['REQUEST_METHOD'] == 'POST') {
                echo '<div class="container">';
                $commentId  = $_POST['c_id'];
                $comment    = $_POST['comment'];

                // Update Query
                echo '<div class="container">';
                $stmt = $con->prepare("UPDATE comments SET c_content= ? WHERE c_id = ?");
                $stmt->execute(array($comment, $commentId));
                $comCount = $stmt->rowCount();
                if($comCount > 0) {
                    redirect('Comment Updated', 3, 'comments.php?action=manage', 'success');
                } else {
                    redirect('No Comment Updated', 3, 'comments.php?action=manage', 'success');
                }
                echo '<div>';
                
            } else {
                redirect('Comment Not Exsists', 3, 'comments.php?action=manage');
            }
            echo '</div>';
        } elseif($action == 'delete') {
            echo '<div class="container">';
            $commentId = (isset($_GET['c_id']) && is_numeric($_GET['c_id']) ? intval($_GET['c_id']) : 0);
            // DELETE Query 
            $stmt = $con->prepare("DELETE FROM comments WHERE c_id = ?");
            $stmt->execute(array($commentId));
            $deletedRows = $stmt->rowCount();
            if($deletedRows > 0) {
                redirect('Comment Deleted Successfuly', 3, 'comments.php?action=manage', 'success');
            } else {
                redirect('Comment Not Exsist', 3, 'comments.php?action=manage');
            }
            echo '<div>';
            
        } elseif($action == 'approve') {
            echo '<div class="container">';
            $commentId = (isset($_GET['c_id']) && is_numeric($_GET['c_id']) ? intval($_GET['c_id']) : 0);
            // Approve Query
            $stmt = $con->prepare("UPDATE comments SET c_status= 1 WHERE c_id = ?");
            $stmt->execute(array($commentId));
            $Apprvoed = $stmt->rowCount();
            if($Apprvoed > 0) {
                redirect('Comment Appreoved', 3, 'comments.php?action=manage', 'success');
            } else{
                redirect('Nothing Appreoved', 3, 'comments.php?action=manage');
            }
            echo '<div>';
            
        } 
        

        include $Tpl . 'footer.php';
    }

?>