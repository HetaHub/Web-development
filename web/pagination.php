<nav class="d-flex justify-content-center" aria-label="Page navigation example">
                                <ul class="pagination">
                                    <!-- <li class="page-item"><a class="page-link" href="#">Previous</a></li>
                                    <li class="page-item"><a class="page-link" href="#?page=1">1</a></li>
                                    <li class="page-item"><a class="page-link" href="#?page=1">2</a></li>
                                    <li class="page-item"><a class="page-link" href="#">Next</a></li> -->
                                    <?php
                                    require_once __DIR__.'/admin/lib/db.inc.php';
                                    $product = ierg4210_prod_fetchAll();
                                    $page = '';
                                    $url=htmlspecialchars( $_SERVER['REQUEST_URI'], ENT_QUOTES, 'UTF-8' );
                                    //$url=$_SERVER['REQUEST_URI'];
                                    $nextPage = '';
                                    if(isset($_GET['page'])){  //handle previous and next page button
                                        $temp=$_GET['page'];
                                        if($temp==1){ //on page=1
                                            $url=preg_replace('/page=\d+/', 'page=1', $url);
                                            $url=htmlspecialchars( $url, ENT_QUOTES, 'UTF-8' ); //prevent xss
                                            $page .= '<li class="page-item"><a class="page-link" href="'.$url.'">Previous</a></li>';
                                        }
                                        else{  //page >1
                                            $temp--;
                                            $url=preg_replace('/page=\d+/', 'page='.$temp.'', $url);
                                            $url=htmlspecialchars( $url, ENT_QUOTES, 'UTF-8' );
                                            $page .= '<li class="page-item"><a class="page-link" href="'.$url.'">Previous</a></li>';
                                            $temp++;
                                        }
                                        $temp++;
                                        $url=preg_replace('/page=\d+/', 'page='.$temp.'', $url);
                                        $url=htmlspecialchars( $url, ENT_QUOTES, 'UTF-8' );
                                        $nextPage .= '<li class="page-item"><a class="page-link" href="'.$url.'">Next</a></li>';
                                        $temp--;
                                    }
                                    else{  //URL has 0 parameters, then append ?
                                        $page .= '<li class="page-item"><a class="page-link" href="'.$url.'?page=1">Previous</a></li>';
                                        $nextPage .= '<li class="page-item"><a class="page-link" href="'.$url.'?page=2">Next</a></li>';
                                    }
                                    for($i=1;$i<=ceil($totalitem/$totalItemEachPage);$i++){
                                        if(isset($_GET['page'])){
                                            $url=preg_replace('/page=\d+/', 'page='.$i.'', $url);
                                            $url=htmlspecialchars( $url, ENT_QUOTES, 'UTF-8' );
                                            $page .= '<li class="page-item"><a class="page-link" href="'.$url.'">'.$i.'</a></li>';
                                        }
                                        else{ //append ? or &
                                            
                                            if(count($_GET)==0){ //URL has 0 parameters, then append ?
                                                $page .= '<li class="page-item"><a class="page-link" href="'.$url.'?page='.$i.'">'.$i.'</a></li>';
                                            }
                                            else{ //append &
                                                $page .= '<li class="page-item"><a class="page-link" href="'.$url.'&page='.$i.'">'.$i.'</a></li>';
                                            }
                                            //$matches = preg_match_all('/.*=.*/i', $url, $matches);
                                            
                                        }
                                        
                                    }
                                    $page .= $nextPage;
                                    echo $page;
                                    ?>
                                </ul>
                            </nav>