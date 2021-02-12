<?php
    session_start();

    if(isset($_SESSION['Username'])) {
        $pageTitle = 'Items';
        include 'init.php';

        $action = (isset($_GET['action'])) ? $_GET['action'] : 'manage';

        if($action == 'manage') {// Manage Page

            // Check Approve Items
            $approve = '';
            if(isset($_GET['page']) && $_GET['page'] == 'approve') {
                $approve = 'AND approve = 0';
            } 
            // Select All Users 
            $stmt = $con->prepare("SELECT 
                                        items.*, categories.cat_name 
                                    AS 
                                        categorie, users.Username 
                                    AS 
                                        client 
                                    FROM 
                                        items 
                                    INNER JOIN 
                                        categories 
                                    ON 
                                        categories.cat_id = items.cat_id 
                                    INNER JOIN 
                                        users 
                                    ON 
                                        users.Userid = items.user_id $approve ORDER BY it_id DESC");
            $stmt->execute();
        ?>
            
            <!-- Start Table Manage Items -->
            <h1 class="text-center">Manage Items</h1>
            <div class="container items">
                <table class="table-bordered main-table text-center table table-responsive">
                    <tr>
                        <td>#ID</td>
                        <td>Image</td>
                        <td>Name</td>
                        <td>Description</td>
                        <td>Price</td>
                        <td>Country</td>
                        <td>Status</td>
                        <td>Add Date</td>
                        <td>Categorie</td>
                        <td>Client</td>
                        <td>Tags</td>
                        <td>Actions</td>
                    </tr>
                    <?php 
                    while($items = $stmt->fetch()) { ?>
                    <tr>
                        <td><?php echo $items['it_id'] ?></td>
                        <td>
                            <img src='data/uploads/items/<?php if(!empty($items['image'])) {echo $items['image'];} 
                            else {
                                echo 'item-img.jpg';
                            } ?>' alt="my Image"/>
                        </td>
                        <td><?php echo $items['it_name'] ?></td>
                        <td><?php echo $items['it_desc'] ?></td>
                        <td><?php echo $items['it_price'] ?></td>
                        <td><?php echo $items['country_made'] ?></td>
                        <td>
                            
                            <?php
                                if($items['status'] == 0) {
                                    echo '...';
                                } elseif($items['status'] == 1) {
                                    echo 'New';    
                                } elseif($items['status'] == 2) {
                                    echo 'Like New';
                                } elseif($items['status'] == 3) {
                                    echo 'Used';
                                } else {
                                    echo 'Old';
                                }
                             ?>
                    
                        </td>
                        <td><?php echo $items['add_date'] ?></td>
                        <td><?php echo $items['categorie'] ?></td>
                        <td><?php echo $items['client'] ?></td>
                        <td><?php echo $items['tags'] ?></td>
                        <td>
                            <a href="items.php?action=edit&it_id=<?php echo $items['it_id']; ?>" class="btn btn-success"><i class="fa fa-edit"></i> Edit</a>
                            <a href="items.php?action=delete&it_id=<?php echo $items['it_id']; ?>" class="btn btn-danger confirm"><i class="fa fa-close"></i> Delete</a>
                            <?php
                                if($items['approve'] == 0) {
                                    echo '<a href="?action=approve&it_id='. $items['it_id'] .'" class="btn btn-primary">Approve</a>';
                                }
                            ?>
                        </td>

                    </tr>
                    <?php } ?>
                </table>
                <a href="?action=add" class="btn btn-primary"><i class="fa fa-plus"></i> New Item</a>
            </div>
            <!-- End Table Manage Items --> 

            

       <?php } elseif ($action == 'add') { // Add Items Page ?>

            <h1 class="text-center">Add New Item</h1>
            <div class="container">
                <form action="?action=insert" class="form-horizontal" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="" class="control-label col-sm-2">Name</label>
                        <input type="text" name="name" autocomplete="off" class="form-control col-sm-10"/>
                    </div>
                    <div class="form-group">
                        <label for="" class="control-label col-sm-2">Item Image</label>
                        <input type="file" name="it-image" autocomplete="off" class="form-control col-sm-10"/>
                    </div>
                    <div class="form-group">
                        <label for="" class="control-label col-sm-2">Description</label>
                        <input type="text" name="desc" class="form-control col-sm-10"/>
                    </div>
                    <div class="form-group">
                        <label for="" class="control-label col-sm-2">Price</label>
                        <div class="input-group">
                            <span class="input-group-addon">$</span>
                            <input type="text" name="price" aria-label="Amount (to the nearest dollar)" class="col-sm-10 form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="control-label col-sm-2">Country</label>
                        <input type="text" name="country" autocomplete="off" class="form-control col-sm-10"/>
                    </div>
                    <div class="form-group">
                        <label for="" class="control-label col-sm-2">Item Status</label>
                        <select name="status" class="status">
                            <option value="0">...</option>
                            <option value="1">New</option>
                            <option value="2">As New</option>
                            <option value="3">Used</option>
                            <option value="4">Old</option>   
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="" class="control-label col-sm-2">Categories</label>
                        <select name="cats" class="status">
                            <option value="0">...</option>
                            <?php
                                $stmt = $con->prepare("SELECT * FROM categories");
                                $stmt->execute();
                                $cats = $stmt->fetchAll();
                                foreach($cats as $cat) {
                                    echo '<option value="'. $cat['cat_id'] .'">'. $cat['cat_name'] .'</option>';
                                }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="" class="control-label col-sm-2">Members</label>
                        <select name="users" class="status">
                            <option value="0">...</option>
                            <?php
                                $q = $con->prepare("SELECT * FROM users");
                                $q->execute();
                                $users = $q->fetchAll();
                                foreach($users as $user) {
                                    echo '<option value="'. $user['Userid'] .'">'. $user['Username'] .'</option>';
                                }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="" class="control-label col-sm-2">Tags</label>
                        <input type="text" name="tag" autocomplete="off" class="form-control col-sm-10" placeholder="Separete Word With Comma To Be A Tag (,)"/>
                    </div>
                    <div class="form-group">
                        <input type="submit" value="Add Item"class="btn btn-lg btn-primary  col-sm-12"/>
                    </div>
                </form>
            </div>

       <?php } elseif($action == 'edit') { // Edit Items Page 

                // Check If GET REQUEST ID IS numeric & Exsist
                $item_id = (isset($_GET['it_id']) && is_numeric($_GET['it_id']) ? intval($_GET['it_id']) : 0);

                // SELECT ALL DATA OF USER IF GET ID IS userid
                $stmt = $con->prepare('SELECT * FROM `items` WHERE `it_id` = ? LIMIT 1');

                // Execute The Query 
                $stmt->execute(array($item_id));

                // Fetch The User Data 

                $items = $stmt->fetch();

                // Get The Rows Count To Check If There's Data Coming Or Not

                $count = $stmt->rowCount();

                if ($count > 0) { ?>

                <h1 class="text-center">Edit Item</h1>
                <div class="container">
                    <form action="?action=update" class="form-horizontal" method="POST">
                        <input type="hidden" value="<?php echo $items['it_id']; ?>" name="it_id" />
                        <div class="form-group">
                            <label for="" class="control-label col-sm-2">Name</label>
                            <input type="text" name="it_name" value="<?php echo $items['it_name']; ?>" autocomplete="off" class="form-control col-sm-10" placeholder="Change Your Item Name"/>
                        </div>
                        <div class="form-group">
                            <label for="" class="control-label col-sm-2">Description</label>
                            <input type="text" name="it_desc" value="<?php echo $items['it_desc']; ?>" autocomplete="off" class="form-control col-sm-10" placeholder="Change Your Item Descrription"/>
                        </div>
                        <div class="form-group">
                            <label for="" class="control-label col-sm-2">Price</label>
                            <div class="input-group">
                                <span class="input-group-addon">$</span>
                                <input type="text" name="price" aria-label="Amount (to the nearest dollar)" value="<?php echo $items['it_price'] ?>" class="col-sm-10 form-control" placeholder="Change Your Item Price">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="control-label col-sm-2">Country</label>
                            <input type="text" name="country" value="<?php echo $items['country_made'] ?>" autocomplete="off" class="form-control col-sm-10"/>
                        </div>
                        <div class="form-group">
                            <label for="" class="control-label col-sm-2">Item Status</label>
                            <select name="status" class="status">
                                <option value="0">...</option>
                                <option value="1" <?php if($items['status'] === '1') { echo 'selected'; } ?>>New</option>
                                <option value="2" <?php if($items['status'] === '2') { echo 'selected'; } ?>>As New</option>
                                <option value="3" <?php if($items['status'] === '3') { echo 'selected'; } ?>>Used</option>
                                <option value="4" <?php if($items['status'] === '4') { echo 'selected'; } ?>>Old</option>   
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="" class="control-label col-sm-2">Categories</label>
                            <select name="cats" class="status">
                                <option value="0">...</option>
                                <?php
                                    $stmt = $con->prepare("SELECT * FROM categories");
                                    $stmt->execute();
                                    $cats = $stmt->fetchAll();
                                    foreach($cats as $cat) {
                                        echo "<option value='" . $cat['cat_id'] . "'";
                                        if($cat['cat_id'] == $items['cat_id']) {echo 'selected';};
                                        echo '>' . $cat['cat_name'] . '</option>';
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="" class="control-label col-sm-2">Members</label>
                            <select name="users" class="status">
                                <option value="0">...</option>
                                <?php
                                    $stmt = $con->prepare("SELECT * FROM users");
                                    $stmt->execute();
                                    $users = $stmt->fetchAll();
                                    foreach($users as $user) {
                                        echo '<option value="'. $user['Userid'] .'"';
                                        if($user['Userid'] === $items['user_id']) {echo 'selected';};
                                        echo '>' . $user['Username'] . '</option>';
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="" class="control-label col-sm-2">Tags</label>
                            <input type="text" name="tag" value="<?php echo $items['tags']; ?>" autocomplete="off" class="form-control col-sm-10" placeholder="Separete Word With Comma To Be A Tag (,)"/>
                        </div>          
                        <div class="form-group">
                            <input type="submit" value="Save Changes" class="btn btn-lg btn-success col-sm-12"/>
                        </div>
                    </form>
                    <?php

                    /* Manage Comments Of This Item */

                    // Select Query To Fetch all Comments
                    $stmt = $con->prepare("SELECT
                                                comments.*, users.Username As Client 
                                            FROM 
                                                comments
                                            INNER JOIN users
                                            ON
                                                users.Userid = comments.user_id WHERE item_id= ?");
                    $stmt->execute(array($items['it_id']));
                    $comments = $stmt->fetchAll();
                    if(!empty($comments)) {
                    ?>
                        
                    <h1 class="text-center">Manage { <?php echo $items['it_name']; ?> } Comments</h1>
                        <table class="table-bordered main-table text-center table table-responsive">
                            <tr>
                                <td>Comment</td>
                                <td>Comment Status</td>
                                <td>Comment Date</td>
                                <td>Client</td>
                                <td>Actions</td>
                            </tr>
                            <?php 
                            foreach($comments as $comment) { ?>
                            <tr>
                                <td><?php echo $comment['c_content'] ?></td>
                                <td><?php echo $comment['c_status'] ?></td>
                                <td><?php echo $comment['c_date'] ?></td>
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
                        <?php } else {
                            echo '<div class="alert alert-info">This Item Hasn\'t Any Comments Yet !</div>';
                        } ?>
                    </div>
                    
                <?php 
                } else {
                redirect("Sorry Item Not Exsists", 4, 'items.php?action=manage');

                }    
            }  elseif ($action == 'insert') { /* Start Insert Page */

                if($_SERVER['REQUEST_METHOD'] == 'POST') {
                    echo '<div class="container">';
                    echo '<h1 class="text-center">Insert Member</h1>';
                    $name = $_POST['name'];
                    $imageName = $_FILES['it-image']['name'];
                    $imageSize = $_FILES['it-image']['size'];
                    $imageTemDir = $_FILES['it-image']['tmp_name'];
                    $imageName = $_FILES['it-image']['name'];
                    $desc = $_POST['desc'];
                    $price = $_POST['price'];
                    $country = $_POST['country'];
                    $status = $_POST['status'];
                    $categories = $_POST['cats'];
                    $members = $_POST['users'];
                    $tag = $_POST['tag'];

                    $myExtentions = array(
                        "jpg",
                        "jpeg",
                        "png",
                        "gif"                      
                    );
                    $imgdata = explode('.', $imageName);
                    $imgExtention = strtolower(end($imgdata));
                    

                    // Errors Array To Handle Input Errors
                    $errors = array();
                    // Validate 
                    if (strlen($name) < 3) {
                        $errors[] = '<div class="alert alert-danger">Name Cant Be Less Than 3 Chars</div>';
                    }

                    if (empty($name)) {
                        $errors[] = '<div class="alert alert-danger">Name Cant Be Empty</div>';
                    }
                    if (empty($desc)) {
                        $errors[] = '<div class="alert alert-danger">Description Cant Be Empty</div>';
                    }
                    if (empty($price)) {
                        $errors[] = '<div class="alert alert-danger">Price Cant Be Empty</div>';
                    }
                    if (empty($country)) {
                        $errors[] = '<div class="alert alert-danger">Country Cant Be Empty</div>';
                    }
                    if (empty($status)) {
                        $errors[] = '<div class="alert alert-danger">Status Cant Be Empty</div>';
                    }
                    if (is_numeric($tag)) {
                        $errors[] = '<div class="alert alert-danger">tag Cannot Be Number Only Word</div>';
                    }
                    if (strlen($tag) > 15) {
                        $errors[] = '<div class="alert alert-danger">tag Cannot Be More Than 15 Chars</div>';
                    }
                    // Check If File Extention Exsist In 
                    if (!empty($imageName) && ! in_array($imgExtention, $myExtentions)) {
                        $errors[] = '<div class="alert alert-danger">Must Be Add File With This Extentions Just [jpg, jpeg, png, gif]</div>';
                    }
                    

                    foreach($errors as $err) {
                        echo $err . '<br>';
                    }
                    echo '<div>';
                    
                    if (empty($errors)) {

                         move_uploaded_file($imageTemDir, "data\uploads\items\\" . $imageName);

                        
                        // Check If User Exsist Before Insert It 
                        
                        $check = checkItem('it_name', 'items', $name);
                        if ($check == 1) {
                            redirect('Sorry This Item Is Exsist Please Try Again With Anouther Item', 6, 'items.php?action=add');
                        } else {
                            // Insert Query
                            $stmt = $con->prepare("INSERT INTO `items` (`it_name`, `it_desc`, `it_price`,`country_made`, `image`, `status`,cat_id, `user_id`, `add_date`, `tags`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, now(), ?)");
                            // Execute Update Query
                            $stmt->execute(array($name, $desc, $price, $country, $imageName, $status, $categories, $members, $tag));

                            $count = $stmt->rowCount();
                            if($count > 0) {
                                echo 'success';
                                //redirect($count .' Item Inserted Successfully !', 3, 'items.php?action=manage', 'success');
                            } else {
                                echo 'faild';
                                //redirect($count .'  Nothing Inserted !', 4, 'items.php?action=manage');
                            }
                        }
                        
                    } 

                } else {
                    redirect("You Can't Browse This Page Direct", 4, 'items.php?action=manage');
                }
                

                /* End Insert Page */

        } elseif ($action == 'update')  { /* Start Update Page */

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                echo '<h1 class="text-center"></h1>';
                $id= $_POST['it_id'];
                $name = $_POST['it_name'];
                $desc = $_POST['it_desc'];
                $price = $_POST['price'];
                $country = $_POST['country'];
                $status = $_POST['status'];
                $categoie = $_POST['cats'];
                $users = $_POST['users'];
                $tags = $_POST['tag'];

                // Statment Update Item Query
                $stmt = $con->prepare("UPDATE items SET it_name= ?, it_desc= ?, it_price= ?, country_made= ?, `status`= ?, cat_id= ?, `user_id`= ?, `tags`= ? WHERE it_id= ? ");
                $stmt->execute(array($name, $desc, $price, $country, $status, $categoie, $users, $tags, $id));
                $count = $stmt->rowCount();
                if($count > 0) {
                    redirect('Item Updated Successfully', 3, 'items.php?action=manage', 'success');
                } else {
                    redirect('No Item Updated', 3, 'items.php?action=manage');
                }
            }

        /* End Update Page */
        } elseif($action == 'delete') { /* Start Delete Page */

            // Check If GET REQUEST ID IS numeric & Exsist
            $item_id = (isset($_GET['it_id']) && is_numeric($_GET['it_id']) ? intval($_GET['it_id']) : 0);

            $check = checkItem('it_id', 'items', $item_id);

            if($check > 0) {
                // Delete Query
                $stmt = $con->prepare("DELETE FROM items WHERE it_id = ?");
                $stmt->execute(array($item_id));
                redirect('Item Deleted Successfully', 3, 'items.php?action=manage', 'success');
            } else {
                redirect('Sorry Item Is Not Exsist', 3, 'items.php?action=manage');
            }

        /* Start Delete Page */
        } elseif($action == 'approve') { // Approve Page
        
            // Check If GET REQUEST ID IS numeric & Exsist
            $item_id = (isset($_GET['it_id']) && is_numeric($_GET['it_id']) ? intval($_GET['it_id']) : 0);
            
            $check = checkItem('it_id', 'items', $item_id);
            if($check > 0) {
                // Update Query For Approve Item
                $stmt = $con->prepare("UPDATE items SET approve= 1 WHERE it_id= $item_id");
                $stmt->execute();
                redirect("Congrats! Item Approved", 3, 'items.php?action=manage', 'success');
            } else {
                redirect("Item Not Exsist", 3, 'items.php?action=manage');
            }
        }
        include $Tpl . 'footer.php';
    } else {
        header('Location: index.php');
        exit;
    }