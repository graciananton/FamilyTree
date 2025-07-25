        <?php
            $navLinksMenuAdmin = explode(",",Config::getNavLinksMenuAdmin());
        ?>
        <div class='container-fluid mt-1'>
            <div class='row fw-bold shadow-lg' style='color:#7F4444;background-color:#ffffff;'>
                <nav class='navbar navbar-expand-lg navbar-light '>
                    <div class='container-fluid'>
                        <a href='../index.php?req=searchForm' target='_blank'><img src='../../familyTree/img/linksNavMenu.png'/></a>
                        
                        <button class='navbar-toggler me-4' type='button' style='background-color:#ffffff;' data-bs-toggle='collapse' data-bs-target='#hamburgerIcon' aria-controls='hamburgerIcon' aria-expanded='false' aria-label='Toggle navigation'>
                            <span class='navbar-toggler-icon'></span>
                        </button>

                        <div class='collapse navbar-collapse ms-4 mt-2' id='hamburgerIcon'>
                            <ul class='navbar-nav me-auto mb-2 mb-lg-0'>
                                <?php
                                for ($i = 0; $i < count($navLinksMenuAdmin); $i++) {
                                    $navLinkMenuAdmin = $navLinksMenuAdmin[$i];
                                    $navLinkMenuAdmin = explode("=>", $navLinkMenuAdmin);
                                    $navLinkMenuAdminName = $navLinkMenuAdmin[0];
                                    $navLinkMenuAdminReq = $navLinkMenuAdmin[1];
                                  
                                ?>
                                    <li class='nav-item'>
                                        <a class='nav-link' href='?req=<?= $navLinkMenuAdminReq; ?>' style='color:#7F4444'>
                                            <?= $navLinkMenuAdminName; ?>
                                        </a>
                                    </li>
                                <?php
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
