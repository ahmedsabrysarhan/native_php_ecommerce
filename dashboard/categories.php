<?php
ob_start();
    session_start();
    $title = 'Categories';
    if(isset($_SESSION['Admin']) ){
    include('init.php');
        // Manage Category
        if(isset($_GET['do']) && $_GET['do'] == 'manage'){
            $sort = "ASC";
            $sorting = array("ASC" , "DESC");
            if( isset($_GET['sort']) && in_array($_GET['sort'] , $sorting)){
                $sort = $_GET['sort'];
            }
            $stmt = $connect->prepare("SELECT * FROM categories ORDER BY Ordering $sort ");
            $stmt->execute();
            $rows = $stmt->fetchAll();
            ?>
            <h1 class='text-center'>Manage Categories</h1>
            <div class="container cat-add">
                <div class="ordering pull-left">
                    <i class="fa fa-sort"></i><span> Order By : </span>
                    [ <a href="categories.php?do=manage&sort=ASC" class='<?php if($sort == 'ASC'){echo 'active';}?>'>  Asc </a> |
                      <a href="categories.php?do=manage&sort=DESC" class='<?php if($sort == 'DESC'){echo 'active';}?>'> Desc </a> ]
                </div>
                <div class="table-responsive">
                    <table class="table main-table table-bordered text-center">
                        <tr>
                            <td>Category Name</td>
                            <td>Parent</td>
                            <td>Description</td>
                            <td>Visibility</td>
                            <td>Comments</td>
                            <td>Ads</td>
                            <td>Control</td>
                        </tr>
                        <!-- $subs= getAllFrom("*", "categories","WHERE Parent = {$row['CatID']}", "","CatID","DESC");
                                        if(!empty($subs)){
                                            foreach($subs as $sub){
                                                echo $row['Name'];
                                            }
                                        } -->
                            <?php
                                foreach($rows as $row){
                                echo "<tr>";
                                        echo "<td>" . $row['Name'] . "</td>";

                                        echo "<td>";
                                        if($row['Parent'] == 0 ){
                                            echo "No Parent";
                                        }else{
                                            $subs= getAllFrom("*", "categories","WHERE CatID = {$row['Parent']}", "","CatID","DESC");
                                            if(!empty($subs)){
                                                foreach($subs as $sub){
                                                    echo $sub['Name'];
                                                } 
                                            }
                                        }
                                        echo "</td>";

                                        echo "<td>";
                                        if(empty($row['Description'])){ echo "There is no description here"; }
                                        else{ echo "<p style='max-width:250px;'>" . $row['Description'] . "</p>";} 
                                        echo "</td>";

                                        echo "<td>";
                                            if($row['Visibility'] == 0 ){ echo "Visible"; }else{ echo "Hidden"; } 
                                        echo "</td>";

                                        echo "<td>";
                                            if($row['AllowComments'] == 0 ){ echo "Allowed"; }else{ echo "Not Allowed";} 
                                        echo "</td>";

                                        echo "<td>";
                                        if($row['AllowAds'] == 0 ){ echo "Allowed"; }else{ echo "Not Allowed";}
                                    echo "</td>"; 
                                    echo "<td>";
                                            echo "<a href='?do=edit&id=" . $row['CatID'] . "' class='btn btn-success '><i class='fa fa-edit'></i> Edit </a>";
                                            echo "<a href='?do=delete&id=" . $row['CatID'] . "' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete </a>";
                                        echo"</td>";

                                echo "</tr>";
                                }
                            ?>
                    </table>
                </div>
                <a class="btn btn-info" href="?do=add"><i class="fa fa-plus"></i> New Category </a>
            </div>
        <?php    
        // Add New Category
        }elseif(isset($_GET['do']) && $_GET['do'] == 'add'){?>
            <h1 class="text-center">Add New Category</h1>
            <div class="container add m-auto">
                <form class="form-horizontal" action="?do=insert" method="POST">
                    <div class="row form-group">
                        <label class="col-md-3 control-label">Category Name</label>
                        <input class="col-md-6 form-control" type="text" name="catName" placeholder="Category name must be unique" required="required" autocomplete="off"  >
                    </div>

                    <div class="row form-group">
                        <label class="col-md-3 control-label" >Desription</label>
                        <input class= "col-md-6 form-control" type="text" name="description" placeholder="Describe your category" autocomplete="off" >
                    </div>

                    <div class="row form-group">
                        <label class="col-md-3 control-label" >Ordering</label>
                        <input class= "col-md-6 form-control" type="text" name="ordering" placeholder="Number to arrange the category" autocomplete="off" >
                    </div>

                    <div class="row form-group">
                        <label class="col-md-3 control-label" >Parent</label>
                        <select name="parent"><?php
                            $cats = getAllFrom('*','categories', 'WHERE Parent = 0 ', '', 'CatID');
                            echo '<option value="0">None</option>';
                            foreach($cats as $cat){
                                echo '<option value="'. $cat['CatID'] .'"> '. $cat['Name'] .' </option>';
                            }
                        ?></select>
                    </div>

                    <div class="row form-group">
                        <label class="col-md-3 control-label">Visibility</label>
                        <div class="col-md-6">
                            <div>
                                <input name="vis" id="vis-yes" type="radio" value= 0 checked>
                                <label for="vis-yes"> Yes </label>
                            </div>
                            <div>
                                <input name="vis" id="vis-no" type="radio" value= 1>
                                <label for="vis-no"> No </label>
                            </div>
                        </div>
                    </div>

                    <div class="row form-group">
                        <label class="col-md-3 control-label">Allow Comments</label>
                        <div class="col-md-6">
                            <div>
                                <input id="com-yes" type="radio" name="comment" value=0 checked>
                                <label for="com-yes">Yes</label>
                            </div>
                            <div>
                                <input id="com-no" type="radio" name="comment" value=1>
                                <label for="com-no">No</label>
                            </div>
                        </div>
                    </div>

                    <div class="row form-group">
                        <label class="col-md-3 control-label">Allow Ads</label>
                        <div class="col-md-6">
                            <div>
                                <input id="com-yes" type="radio" name="ads" value=0 checked>
                                <label for="com-yes">Yes</label>
                            </div>
                            <div>
                                <input id="com-no" type="radio" name="ads" value=1>
                                <label for="com-no">No</label>
                            </div>
                        </div>
                    </div>

                    <div class="row form-group">
                        <label class="col-md-3 control-label" ></label>
                        <input class= "btn btn-primary col-md-2 form-control" type="submit"  value="Add Category" >
                    </div>
                </form>
            </div>
            
        <?php    
        // Insert New Category
        }elseif(isset($_GET['do']) && $_GET['do'] == 'insert'){
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                $name       = $_POST['catName'];
                $parent     = $_POST['parent'];
                $descripe   = $_POST['description'];
                $ordering   = $_POST['ordering'];
                $visible    = $_POST['vis'];
                $comment    = $_POST['comment'];
                $ads        = $_POST['ads'];
                // check Empty name or not 
                if(empty($name)){
                    $msg = "<div class='container alert alert-danger'>Category Name Can't be Empty</div>";
                    Redirect($msg, 'back');
                }else{
                    // check if there's item with same name 
                    $check = checkItem("Name", "categories", $name);
                    if($check > 0 ){
                        $msg = "<div class='container alert alert-danger mt-3'> SORRY! category name must be unique </div>";
                        Redirect($msg , 'back');
                    }else{
                        // Execute and Add new item  
                        $stmt = $connect->prepare("INSERT INTO
                                                 categories(Name, Parent,Description, Ordering, Visibility, AllowComments, AllowAds)
                                                 VALUES (:name, :parent ,:descripe, :order, :visible, :comment, :ads)");
                        $stmt->execute(array(
                            "name"      => $name,
                            "parent"    => $parent,
                            "descripe"  => $descripe,
                            "order"     => $ordering,
                            "visible"   => $visible,
                            "comment"   => $comment,
                            "ads"       => $ads
                        ));
    
                        $msg = "<div class='container alert alert-success mt-3'>A new category has Added successfully</div>";
                        Redirect($msg, "back");
                    }
                }
            }else{
                $msg = '<div class="container alert alert-danger mt-4">You can\'t browse this page directly</div>';
                Redirect($msg, 'back');
            }

        // Edit Category
        }elseif(isset($_GET['do']) && $_GET['do'] == 'edit'){
            if(isset($_GET['id']) && is_numeric($_GET['id'])){
                $catID = $_GET['id'];
                $stmt = $connect->prepare("SELECT * FROM categories WHERE CatID = ?");
                $stmt->execute(array($catID));
                $row = $stmt->fetch();
                ?>
                <h1 class="text-center"> Edit Category Page</h1>
                <div class="container edit mt-3">
                    <form class="form-horizontal" action="?do=update" method="POST">
                        <input type="hidden" name = "Id" value = "<?php echo $row['CatID'];?>">
                        <div class="row form-group">
                            <label class="col-md-3 control-label">Category Name</label>
                            <input type="hidden" name="oldName" value="<?php echo $row['Name'];?>" >
                            <input class="col-md-6 form-control" type="text" name="newName"  value="<?php echo $row['Name']; ?>" required="required" autocomplete="off"  >
                        </div>

                        <div class="row form-group">
                            <label class="col-md-3 control-label" >Desription</label>
                            <input type="hidden" name="oldDescripe" value ="<?php echo $row['Description']; ?>">
                            <input class= "col-md-6 form-control" type="text" name="description"  value ="<?php if(empty($row['Description'])){ echo "Type your description here";}else{echo $row['Description'];} ?>" autocomplete ="off">
                        </div>

                        <div class="row form-group">
                            <label class="col-md-3 control-label" >Ordering</label>
                            <input class= "col-md-6 form-control" type="text" name="ordering"  value= "<?php echo $row['Ordering'];?>" placeholder="Number to arrange the category" autocomplete="off" >
                        </div>

                        <div class="row form-group">
                        <label class="col-md-3 control-label" >Parent</label>
                        <select name="parent"><?php
                            $cats = getAllFrom('*','categories', 'WHERE Parent = 0 ', '', 'CatID');
                            echo '<option value="0"';
                              if ($row['Parent'] == 0 ){ echo "selected";}
                            echo '>None</option>';
                            foreach($cats as $cat){
                                echo '<option value="'. $cat['CatID'] . '" ';
                                    if ($cat['CatID'] == $row['Parent']){ echo "selected";}
                                echo '>'. $cat['Name'] .' </option>';
                            }
                        ?></select>
                        </div>

                        <div class="row form-group">
                            <label class="col-md-3 control-label">Visibility</label>
                                <div class="col-md-6">
                                    <div>
                                        <input name="vis" id="vis-yes" type="radio" value= 0 <?php if($row['Visibility'] == 0){echo "checked";}?>>
                                        <label for="vis-yes"> Yes </label>
                                    </div>
                                    <div>
                                        <input name="vis" id="vis-no" type="radio" value= 1 <?php if($row['Visibility'] == 1){echo "checked";}?>>
                                        <label for="vis-no"> No </label>
                                    </div>
                            </div>
                        </div>

                        <div class="row form-group">
                            <label class="col-md-3 control-label">Allow Comments</label>
                            <div class="col-md-6">
                                <div>
                                    <input id="com-yes" type="radio" name="comment" value= 0 <?php if($row['AllowComments'] == 0){echo "checked";}?>>
                                    <label for="com-yes">Yes</label>
                                </div>
                                <div>
                                    <input id="com-no" type="radio" name="comment" value= 1 <?php if($row['AllowComments'] == 1){echo "checked";}?>>
                                    <label for="com-no">No</label>
                                </div>
                            </div>
                        </div>

                        <div class="row form-group">
                            <label class="col-md-3 control-label">Allow Ads</label>
                            <div class="col-md-6">
                                <div>
                                    <input id="com-yes" type="radio" name="ads" value=0 <?php if($row['AllowAds'] == 0){echo "checked";}?>>
                                    <label for="com-yes">Yes</label>
                                </div>
                                <div>
                                    <input id="com-no" type="radio" name="ads" value=1 <?php if($row['AllowAds'] == 1){echo "checked";}?>>
                                    <label for="com-no">No</label>
                                </div>
                            </div>
                        </div>

                            <div class="row form-group">
                                <input class= "btn btn-primary offset-md-3 col-md-2 form-control" type="submit"  value="Update" >
                            </div>
                    </form>
                </div>
                <?php    
            }else{
                $msg = '<div class="container alert alert-danger mt-4">You can\'t browse this page directly</div>';
                Redirect($msg, 'back');
            }

        // Update Category
        }elseif(isset($_GET['do']) && $_GET['do'] == 'update'){
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                $catId       = $_POST['Id'];
                $oldName     = $_POST['oldName'];
                $newName     = $_POST['newName'];
                $parent      = $_POST['parent'];           
                $description = $_POST['description'];
                $ordering    = $_POST['ordering'];
                $visible     = $_POST['vis'];
                $comment     = $_POST['comment'];
                $ads         = $_POST['ads'];
                if(empty($newName)){
                    $msg = '<div class="container alert alert-danger mt-4">Category Name can\'t be an empty </div>';
                    Redirect($msg, 'back');
                }else{
                    $check = checkItem("Name", "categories", $newName);
                    if( ($oldName !== $newName) && $check > 0 ){
                        $msg = '<div class="container alert alert-danger mt-4">The Category Name must be Unique </div>';
                        Redirect($msg, 'back');
                    }else{
                        $stmt = $connect->prepare("UPDATE categories SET Name =?, Parent = ?, Description =?, Ordering =?, Visibility =?, AllowComments =?, AllowAds =? WHERE CatID =?");
                        $stmt->execute(array($newName, $parent, $description, $ordering, $visible, $comment, $ads, $catId));
    
                        $msg = '<div class="container alert alert-success mt-4">The Category is Updated </div>';
                        Redirect($msg, 'back');
                    }
                }
               
            }else{
                $msg = '<div class="container alert alert-danger mt-4">You can\'t browse this page directly</div>';
                Redirect($msg, 'back');
            }
            
        // Delete Category
        }elseif(isset($_GET['do']) && $_GET['do'] == 'delete'){
            if(isset($_GET['id']) && is_numeric($_GET['id'])){  
                $catId = $_GET['id'];
                $stmt = $connect->prepare("DELETE FROM categories WHERE CatID = ? ");
                $stmt->execute(array($catId));
                $count = $stmt->rowCount();
                $msg = '<div class="container alert alert-success mt-4"> ' . $count . ' row deleted </div>';
                Redirect($msg, 'back');

            }else{
                $msg = '<div class="container alert alert-danger mt-4">You can\'t browse this page directly</div>';
                Redirect($msg, 'back');
            }
            
        }else{
            $msg=  '<div class="container alert alert-danger mt-4"> SORRY! There is no page such that </div>';
            Redirect($msg);
        }
        

    include($tmpls . 'footer.inc.php');
    }else{
        header("location:index.php");
        exit();
    }
ob_end_flush();
