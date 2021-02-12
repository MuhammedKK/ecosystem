<?php
    session_start();
    if(isset($_SESSION['Username'])) {
        $pageTitle = 'Categories';
        include 'init.php';

        $action = (isset($_GET['action'])) ? $_GET['action'] : 'manage';

        // Select Query To Get All Categories

        $sorting = 'DESC';

        $sorting_list = array('ASC', 'DESC');

        if (isset($_GET['sorting']) && in_array($_GET['sorting'], $sorting_list)) {
            $sorting = $_GET['sorting'];
        }
        $catRows = getAll("*", "categories", "WHERE parent=0", "", "cat_id", $sorting);

        if($action == 'manage') {// Manage Categories Page ?>
            <h1 class="text-center">Manage Categories</h1>
            <div class="cats">
                <div class="container">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3>Categories</h3>
                            <div class="order pull-right">
                                <span>Ordering : </span>
                                <a class="<?php if($sorting == 'ASC') {echo 'active';} ?>" href="?sorting=ASC">ASC</a> |
                                <a class="<?php if($sorting == 'DESC') {echo 'active';}  ?>" href="?sorting=DESC">DESC</a>
                            </div>  
                        </div>
                        <div class="panel-body">
                            
                            <?php
                                foreach($catRows as $catRow) {
                                    echo '<div class="categories">';
                                        echo '<div class="cats-btns">';
                                            echo '<a class="btn btn-primary" href="?action=edit&cat_id='.$catRow['cat_id'].'"><i class="fa fa-edit"></i> Edit</a>';
                                            echo '<a class="btn btn-danger confirm" href="?action=delete&cat_id='.$catRow['cat_id'].'"><i class="fa fa-close"></i> Delete</a>';
                                        echo '</div>';
                                        echo '<h3>Categorie Name : ' . $catRow['cat_name'] . '</h3>' . '<br>';
                                        echo ($catRow['cat_desc'] == '') ? "<div class='cat-desc'>Categorie Description : No Description</div>" . '<br>' : "<div class='cat-desc'>Categorie Description : ". $catRow['cat_desc'] ."</div>" . '<br>';
                                        echo '<div class="order">Ordering : ' . $catRow['cat_order'] . '</div>' . '<br>';
                                        echo ($catRow['cat_visibility'] == 1) ? '<div class="cat-visibile">Visibility Status : <span class="btn btn-danger">Hidden</span></div> <br>' : '<div class="cat-visibile">Visibility Status : <span class="btn btn-success">Visibile</span></div> <br>';
                                        echo ($catRow['cat_comment'] == 1) ? '<div class="cat-comment">Comment Status : <span class="btn btn-danger">Disabled</span></div> <br>' : '<div class="cat-visibile">Comment Status : <span class="btn btn-success">Enabled</span></div> <br>';
                                        echo ($catRow['cat_ads'] == 1) ? '<div class="cat-ads">Ads Status : <span class="btn btn-danger">Disabled</span></div> <br>' : '<div class="cat-visibile">Ads Status : <span class="btn btn-success">Enabled</span></div> <br>';

                                        // Fetch All Child Categories To Get All Child To Their Parent
                                        $stmt = $con->prepare("SELECT * FROM categories WHERE parent = ? ORDER BY cat_id $sorting");
                                        $stmt->execute(array($catRow['cat_id']));
                                        $catChild = $stmt->fetchAll();
                                        
                                        foreach($catChild as $child) {
                                            echo '<div class="cat-child">';
                                                echo '<h4 class="child-head">'. $child['cat_name'] .'</h4>';
                                                echo '<p class="child-para">'. $child['cat_desc'] .'</p>';
                                                echo '<div class="child-btns">';
                                                echo '<a class="btn btn-primary" href="?action=edit&cat_id='.$child['cat_id'].'"><i class="fa fa-edit"></i> Edit</a>';
                                                echo '<a class="btn btn-danger confirm" href="?action=delete&cat_id='.$child['cat_id'].'"><i class="fa fa-close"></i> Delete</a>';
                                                 echo '</div>';
                                            echo '</div>';
                                        }


                                    echo '<hr>';
                                    
                                    echo '</div>';
                                }
                            ?>
                            
                        </div>
                    </div>
                    <a href="?action=add" class="btn btn-primary btn-lg"><i class="fa fa-plus"></i> Add Categorie</a>
                </div>
            </div>
            

        <?php } elseif ($action == 'add') {   // Add Member Page ?>

            <h1 class="text-center">Add New Categorie</h1>
            <div class="container">
                <form action="?action=insert" class="form-horizontal" method="POST">
                    <div class="form-group">
                        <label for="" class="control-label col-sm-2">Name</label>
                        <input type="text" name="cat_name" autocomplete="off" class="form-control col-sm-10" required/>
                    </div>
                    <div class="form-group">
                        <label for="" class="control-label col-sm-2">Description</label>
                        <input type="text" name="cat_desc" autocomplete="off" class="form-control col-sm-10"/>
                    </div>
                    <div class="form-group">
                        <label for="" class="control-label col-sm-2">Ordering</label>
                        <input type="text" name="cat_order"  autocomplete="off" class="form-control col-sm-10"/>
                    </div>
                    <div class="form-group">
                        <label for="" class="control-label col-sm-2">Parent?</label>
                        <select name="parent" class="status">
                            <option value="0">None</option>
                            <?php
                                $cats = getAll("*", "categories", "WHERE parent=0", "", "cat_id");
                                foreach($cats as $cat) {
                                    echo "<option value='" . $cat['cat_id'] . "'";
                                    echo '>' . $cat['cat_name'] . '</option>';
                                }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="" class="control-label col-sm-2">Visiblility</label>
                        <div class="vis-btns">
                            <input id="vis-yes" type="radio" name="visible" value="0" checked>
                            <label for="vis-yes">Yes</label><br>
                            <input id="vis-no" type="radio" name="visible" value="1">
                            <label for="vis-no">No</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="control-label col-sm-2">Comments Allowing</label>
                        <div class="com-btns">
                            <input id="com-yes" type="radio" name="comment" value="0" checked>
                            <label for="com-yes">Yes</label><br>
                            <input id="com-no" type="radio" name="comment" value="1">
                            <label for="com-no">No</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="control-label col-sm-2">Ads Allowing</label>
                        <div class="ads-btns">
                            <input id="ads-yes" type="radio" name="ads" value="0" checked>
                            <label for="ads-yes">Yes</label><br>
                            <input id="ads-no" type="radio" name="ads" value="1">
                            <label for="ads-no">No</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="submit" value="Add Categorie"class="btn btn-lg btn-primary  col-sm-12"/>
                    </div>
                </form>
            </div>
  
        <?php }  elseif ($action == 'insert') { /* Start Insert Page */

                if($_SERVER['REQUEST_METHOD'] == 'POST') {

                    $name = $_POST['cat_name'];
                    $desc = $_POST['cat_desc'];
                    $order = (isset($_POST['cat_order'])) ? intval($_POST['cat_order']) : 0 ;
                    $parent = $_POST['parent'];
                    $visible = $_POST['visible'];
                    $comment = $_POST['comment'];
                    $ads = $_POST['ads'];
                    // Check If Category Exsist Before
                    $check = checkitem('cat_name', 'categories', $name);
                    if($check == 1) {
                        redirect('Sorry This Categorie Is Exsist , Plz Try Again', 3, '?action=add');
                    } else {

                        // Insert Query Statment
                        $stmt = $con->prepare("INSERT INTO `categories` (`cat_name`, `cat_desc`, `cat_order`, `parent`, `cat_visibility`, `cat_comment`, `cat_ads`) VALUES (?, ?, ?, ?, ?, ?, ?)");
                        $stmt->execute(array($name, $desc, $order, $parent, $visible, $comment, $ads));
                        $count = $stmt->rowCount();

                        if ($count > 0) {
                            redirect("Categorie Inserted Successfully!", 3, 'categories.php?action=manage', 'success');
                        }

                    }
                    
                } else {
                    redirect("You Can't Browse This Page Directly", 3, 'categories.php?action=manage');
                }

            /* End Insert Page */

        } elseif ($action == 'edit') {// Edit Categorie Page 

            // Check If GET REQUEST ID IS numeric & Exsist
            $catID = (isset($_GET['cat_id']) && is_numeric($_GET['cat_id']) ? intval($_GET['cat_id']) : 0);

            // SELECT ALL DATA OF USER IF GET ID IS userid
            $stmt = $con->prepare('SELECT * FROM `categories` WHERE `cat_id` = ? LIMIT 1');

            // Execute The Query 
            $stmt->execute(array($catID));
            
            // Fetch The User Data 

            $rows = $stmt->fetch();

            // Get The Rows Count To Check If There's Data Coming Or Not

            $count = $stmt->rowCount();

            if ($count > 0) { ?>
            
            <h1 class="text-center">Edit Categorie</h1>                     
            <div class="container">
                <form action="?action=update" class="form-horizontal" method="POST">
                    <input type="hidden" name="cat_id" value="<?php echo $rows['cat_id'] ?>">
                    <div class="form-group">
                        <label for="" class="control-label col-sm-2">Name</label>
                        <input type="text" name="cat_name" autocomplete="off" class="form-control col-sm-10" value="<?php echo $rows['cat_name'] ?>" required/>
                    </div>
                    <div class="form-group">
                        <label for="" class="control-label col-sm-2">Description</label>
                        <input type="text" name="cat_desc" autocomplete="off" placeholder="Set Some Description Of Your Categorie" class="form-control col-sm-10" value="<?php echo $rows['cat_desc'] ?>" required />
                    </div>
                    <div class="form-group">
                        <label for="" class="control-label col-sm-2">Ordering</label>
                        <input type="text" name="cat_order"  autocomplete="off" class="form-control col-sm-10" value="<?php echo $rows['cat_order'] ?>"/>
                    </div>
                    <div class="form-group">
                        <label for="" class="control-label col-sm-2">Parent?</label>
                        <select name="parent" class="status">
                            <option value="0">None</option>
                            
                            <?php
                                $cats = getAll("*", "categories", "WHERE parent=0", "", "cat_id");
                                foreach($cats as $cat) {?>
                                    <option value=' <?php echo $cat['cat_id'] ?>' <?php if ($cat['cat_id'] == $rows['parent']) {echo 'selected';} ?>>
                                        <?php echo $cat['cat_name']; ?>
                                    </option>
                                <?php }
                            ?>
                        </select> 
                    </div>
                    <div class="form-group">
                        <label for="" class="control-label col-sm-2">Visiblility</label>
                        <div class="vis-btns">
                            <input id="vis-yes" type="radio" name="visible" value="0" <?php echo ($rows['cat_visibility'] == 0) ? 'checked' : '' ?>>
                            <label for="vis-yes">Yes</label><br>
                            <input id="vis-no" type="radio" name="visible" value="1" <?php echo ($rows['cat_visibility'] == 1) ? 'checked' : '' ?>>
                            <label for="vis-no">No</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="control-label col-sm-2">Comments Allowing</label>
                        <div class="com-btns">
                            <input id="com-yes" type="radio" name="comment" value="0" <?php echo ($rows['cat_comment'] == 0) ? 'checked' : '' ?>>
                            <label for="com-yes">Yes</label><br>
                            <input id="com-no" type="radio" name="comment" value="1" <?php echo ($rows['cat_comment'] == 1) ? 'checked' : '' ?>>
                            <label for="com-no">No</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="control-label col-sm-2">Ads Allowing</label>
                        <div class="ads-btns">
                            <input id="ads-yes" type="radio" name="ads" value="0" <?php echo ($rows['cat_ads'] == 0) ? 'checked' : '' ?>>
                            <label for="ads-yes">Yes</label><br>
                            <input id="ads-no" type="radio" name="ads" value="1" <?php echo ($rows['cat_ads'] == 1) ? 'checked' : '' ?>>
                            <label for="ads-no">No</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="submit" value="Save Changes" class="btn btn-lg btn-primary  col-sm-12"/>
                    </div>
                </form>
            </div>
            

        <?php }
        } elseif ($action == 'update')  { /* Start Update Page */

            

            if($_SERVER['REQUEST_METHOD'] == 'POST') {
                echo '<h1 class="text-center">Update Categorie</h1>';
                $id = $_POST['cat_id'];
                $name = $_POST['cat_name'];
                $desc = $_POST['cat_desc'];
                $order = $_POST['cat_order'];
                $visibile = $_POST['visible'];
                $comment = $_POST['comment'];
                $ads = $_POST['ads'];
                
    
                // Update Query
                $stmt = $con->prepare('UPDATE `categories` SET cat_name= ?, cat_desc= ?, cat_order = ? , cat_visibility= ?, cat_comment= ?, cat_ads= ? WHERE cat_id= ? ');
                // Execute Update Query
                $stmt->execute(array($name, $desc, $order, $visibile, $comment, $ads, $id));
    
                redirect(' Categorie Updated Successfully !', 4, 'categories.php?action=manage', 'success');
            } else {
                redirect('Sorry You Can Browse This Page Direct', 4, 'categories.php?action=manage');
            }

        /* End Update Page */
        } elseif($action == 'delete') { /* Start Delete Page */

            $catID = (isset($_GET['cat_id']) && is_numeric($_GET['cat_id']) ? intval($_GET['cat_id']) : 0);

            $checkCat = checkItem('cat_id', 'categories', $catID);

            if($checkCat > 0) {
                $stmt = $con->prepare("DELETE FROM categories WHERE cat_id = ?");
                $stmt->execute(array($catID));
                redirect('Categorie Deleted Successfully !', 2, 'categories.php?action=manage', 'success');
            } else {
                redirect('Categorie Not Exsist !', 3, 'categories.php?action=manage');
            }
        /* Start Delete Page */
        } elseif($action == 'approve') { // Approve Page
        

        }

        include $Tpl . 'footer.php';
    } else {
            header("Location: index.php");
            exit();
        }
?>