<?php $navLinksMenuUser = explode(",",Config::getNavLinksMenuUser());
?>    
       <div class='container-fluid mt-1'>
            <div class='row fw-bold shadow-lg' style='color:#7F4444;background-color:#ffffff;'>
                <nav class='navbar navbar-expand-lg navbar-light '>
                    <div class='container-fluid'>
                        <a href='../index.php?req=searchForm' style='font-weight:500;text-decoration:none;font-size:25px;color:#7F4444;padding-left:10px;' target='_blank'>
                            Family Tree
                        </a>
                        
                        <button class='navbar-toggler me-4' type='button' style='background-color:#ffffff;' data-bs-toggle='collapse' data-bs-target='#hamburgerIcon' aria-controls='hamburgerIcon' aria-expanded='false' aria-label='Toggle navigation'>
                            <span class='navbar-toggler-icon'></span>
                        </button>

                        <div class='collapse navbar-collapse ms-4 mt-2' id='hamburgerIcon'>
                            <ul class='navbar-nav me-auto mb-2 mb-lg-0'>
                                <?php
                                for($i=0;$i<count($navLinksMenuUser);$i++){
                                    $navLinkMenuItem = $navLinksMenuUser[$i];
                                    

                                    $navLinksMenuItems = explode("=>",$navLinkMenuItem);

                                    if(strpos($navLinksMenuItems[1],"(") !== false && stripos($navLinksMenuItems[1],")") !== false){
                                        ?>
                                        <li class='nav-items dropdown'>
                                            <a class="nav-link dropdown-toggle" style='color:#7F4444;text-decoration:none;' href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="true">
                                                <?php echo $navLinksMenuItems[0]; ?>
                                            </a>
                                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                            <?php
                                                $navLink = str_replace("(","",$navLinksMenuItems[1]);
                                                $navLink = str_replace(")","",$navLinksMenuItems[1]);

                                                $navLinks = explode(";",$navLink);
                                                for($j=0;$j<count($navLinks);$j++){
                                                    $navLink = $navLinks[$j];
                                                    $navLink = explode("->",$navLink);
                                                    $navLink[0] = str_replace("(","",$navLink[0]);
                                                    ?>
                                                    <li><a class='dropdown-item'  href='?req=<?php echo $navLink[1]; ?>'><?php echo $navLink[0]; ?></a>
                                                    <?php
                                                }
                                            ?>
                                            </ul>
                                        </li>
                                        <?php
                                    }
                                    else{
                                        ?>
                                        <li class='nav-item'>
                                            <a class='nav-link' href='?req=<?php echo $navLinksMenuItems[1]; ?>' style='color:#7F4444;'><?php echo $navLinksMenuItems[0];?></a>
                                        </li>
                                        <?php
                                    }
                                }
                                ?>
                                <li class='nav-item'>
                                    <a class='nav-link' href='logout.php' style='color:#7F4444'>Logout</a>
                                </li>
                            </ul>

                        </div>
                    </div>
                </nav>
            </div>
        </div>
