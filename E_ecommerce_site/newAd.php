<?php

ob_start();
session_start();
$pageTitle = 'New Ad';
include 'init.php';

if(isset($_SESSION['user'])) { 
    
// Catch Errors
$errs = array();


if ($_SERVER['REQUEST_METHOD'] == 'POST') {// Check If Data Coming From POST Request
// Validate Ad Form
    $adName             = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $imageName          = $_FILES['image']['name'];
    $imageSize          = $_FILES['image']['size'];
    $imageTmp           = $_FILES['image']['tmp_name'];
    $adDesc             = filter_var($_POST['desc'], FILTER_SANITIZE_STRING);
    $adPrice            = filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_INT);
    $adCountry          = filter_var($_POST['country'], FILTER_SANITIZE_STRING);
    $adStatus           = filter_var($_POST['status'], FILTER_SANITIZE_NUMBER_INT);
    $adCategorie        = filter_var($_POST['cats'], FILTER_SANITIZE_NUMBER_INT);
    $adUser             = $_SESSION['id'];
    $tags               = filter_var($_POST['tag'], FILTER_SANITIZE_STRING);

    // Validate File Input To Certain That's Image
    $imgExts            = array("jpg", "png", "jpeg", "gif");
    $getFileData        = explode(".", $imageName);
    $myImgEx            = strtolower(end($getFileData));

    // check if extention in Exntentions array

    if(!empty($imageName) && ! in_array($myImgEx, $imgExts)) {
        echo '<div class="alert alert-danger">This Extention Is Not Allowed Plz Set One Of Them [jpg, jpeg, png, gif]</div>';
    } else {
        move_uploaded_file($imageTmp, 'admin/data/uploads/items/' . $imageName);

        // Check Item is Exsist Before Or Not
        $check = checkItem('it_name', 'items', $adName);
        if ($check === 1) {
            redirect('Sorry This Item Is Already Exsist , Plz Try Again', 2, 'newAd.php'); 
        } else {
            // Insert Data Into Array Depend On User Id

            $stmt = $con->prepare("INSERT INTO items (it_name, `image`, it_desc, it_price, add_date, country_made, `status`, cat_id, `user_id`, `tags`) VALUES (? ,? , ? ,? ,now() ,? ,? ,? , ?, ?)");
            $stmt->execute(array($adName, $imageName, $adDesc, $adPrice, $adCountry, $adStatus, $adCategorie, $adUser, $tags));
            if($stmt) {
                redirect('Item Inserted', 2, 'index.php', 'success');
            } else {
                redirect('Cannout Insert Item Try Again', 2, 'index.php'); 
            }
        }
    }

}


?>


<div class="new-ad">
    <div class="container">
        <h1 class="text-center"><?php echo $pageTitle ?></h1>
        <div class="panel panel-primary">
            <div class="panel-heading">
                <?php echo $pageTitle; ?>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-8">
                        <form action="" class="form-horizontal" method="POST" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="" class="control-label col-sm-2">Name</label>
                                <input type="text" name="name" autocomplete="off" class="live-name form-control col-sm-10"/>
                            </div>
                            <div class="form-group">
                                <label for="" class="control-label col-sm-2">Item Image</label>
                                <input type="file" name="image" class="live-desc form-control col-sm-10"/>
                            </div>
                            <div class="form-group">
                                <label for="" class="control-label col-sm-2">Description</label>
                                <input type="text" name="desc" class="live-desc form-control col-sm-10"/>
                            </div>
                            <div class="form-group">
                                <label for="" class="control-label col-sm-2">Country</label>
                                <input type="text" name="country" autocomplete="off" class="form-control col-sm-10"/>
                            </div>
                            <div class="form-group">
                                <label for="" class="live-price control-label col-sm-2">Price</label>
                                <div class="input-group">
                                    <span class="input-group-addon">$</span>
                                    <input type="text" name="price" aria-label="Amount (to the nearest dollar)" class="live-price col-sm-10 form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="" class="control-label col-sm-2">Item Status</label>
                                <select name="status" class="status col-sm-10">
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
                                <label for="tag" class="control-label col-sm-2">Tags</label>
                                <input id="tag" type="text" name="tag" autocomplete="off" class="form-control col-sm-10" placeholder="Separete Word With Comma To Be A Tag (,)"/>
                            </div>
                            <div class="form-group">
                                <input type="submit" value="Add Item"class="btn btn-lg btn-primary  col-sm-12"/>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-4">
                        <div class="thumbnail item text-center">
                            <span class="item-price">$0</span>
                            <img src="imgs/item-img.jpg" alt="Item Image" />
                            <a href="" class="item-name">Test Name</a>
                            <p class="item-desc">Test Description</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php } else {
    header('Location:login.php');
    exit;
}


include $Tpl . 'footer.php';
ob_end_flush()


?>