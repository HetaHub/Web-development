<nav aria-label="breadcrumb">
<ol class="breadcrumb">
    <li class="breadcrumb-item active" aria-current="page">
        <!-- <a href="index.html">Home</a>
        <a>-></a>
        <a href="fps.html">FPS</a> -->
        <?php
            require_once __DIR__.'/admin/lib/db.inc.php';
            $matches = 0;
            //$url=$_SERVER['REQUEST_URI'];
            $url=htmlspecialchars( $_SERVER['REQUEST_URI'], ENT_QUOTES, 'UTF-8' );
            $bread='';
            if(preg_match('/index.php/i', $url)){
                $bread .= '<a href="index.php">Home</a>';
            }
            elseif(preg_match('/category.php/i', $url)){
                $cat = ierg4210_cat_fetchall();
                $bread .= '<a href="index.php">Home</a>';
                $bread .= '<a>-></a>';
                if(isset($_GET['catid'])){
                    foreach ($cat as $cat){
                        if($cat['CATID']==$_GET['catid']){
                            $bread .= '<a href="category.php?catid='.$cat['CATID'].'">'.$cat['NAME'].'</a>';
                            break;
                        }
                    }

                }
                else{
                    $bread .= '<a href="category.php?catid=1">Action</a>';
                }
            }
            elseif(preg_match('/product.php/i', $url)){
                $bread .= '<a href="index.php">Home</a>';
                $bread .= '<a>-></a>';
                $cat = ierg4210_cat_fetchall();
                if(isset($_GET['name'])){
                    $name=htmlspecialchars( $_GET['name'], ENT_QUOTES, 'UTF-8' );
                    $product=ierg4210_prod_fetchOne($name);
                    foreach ($product as $product){
                        foreach ($cat as $cat){
                            if($cat['CATID']==$product['CATID']){
                                $bread .= '<a href="category.php?catid='.$product['CATID'].'">'.$cat['NAME'].'</a>';
                                $bread .= '<a>-></a>';
                                $bread .= '<a href="product.php?name='.$product['NAME'].'">'.$product['NAME'].'</a>';
                                break;
                            }
                        }
                    }
                }
            }
            echo $bread;
        ?>
    </li>
    <?php
        $email=ierg4210_validateAuthToken();
        $user='';
        if($email==false){
            $user .= '<a class="ml-auto">Welcome, guest!</a>';
        }
        else{
            $user .='<span class="ml-auto">Welcome, <a href="changepw.php" >'.$email.'</a>!</span>';
        }
        echo $user;
    ?>
    <!-- <a class="ml-auto">This text is aligned to the right.</a> -->
</ol>
</nav>