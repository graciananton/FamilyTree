<?php
class HomeView extends View{
    public function render(){

        $homeDropdownImagePath = $this->SettingService->getSettingValueByName("homeDropdownImagePath");
        $homeDropdownImagePath = new Setting($homeDropdownImagePath);
        $homeErrorImagePath = $this->SettingService->getSettingValueByName("homeErrorImagePath");
        $homeErrorImagePath = new Setting($homeErrorImagePath);
        

         if($this->req == "termsofuse" || $this->req == "privacynotice"){
            $this->renderTemplate($this->req);
        }
        else if($this->req == "contact"){
            ?>
            <div class='container-fluid' style='padding-top:110px;padding-bottom:470px;'>
                <div class='row' style='font-weight:bold;display:inline-block;color:#7F4444;font-size:30px;padding-left:20px;'>
                    <?php if($this->request['successMessage'] == true){ ?>
                        Success:
                    <?php }
                    else{?>
                        Error:
                    <?php } ?>
                </div>
                <div class='row'><br/>
                    <div class='col-sm-8 col-md-8' style='font-size:15px;padding-left:20px;'>
                        <?php if($this->request['successMessage'] == true) { ?>
                            Successfully Transfered the mail. Click <a href='index.php?req=searchForm' style='text-decoration:underline;'>here</a> to be redirected to the main page
                        <?php }
                        else{?>
                            Mail Was Not Transferred. Click <a href='index.php?req=searchForm' style='text-decoration:underline;'>here</a> to be redirected to the main page.
                        <?php } ?>
                    </div>
                </div>
            </div>
            <?php
        }
        else if($this->req == "page_profile"){
            echo "<div style='padding-top:105px;'>".$this->object."</div>";
        }
        else if($this->request['pageType'] == "page_error"){
            echo $this->object;
        }

        else if($this->request['req'] == "searchForm"){
            if(array_key_exists('personName',$this->request)){$personName = $this->request['personName'];}
            else{$personName = "Search for family tree persons";}

            $statistics = new Statistics();

            $individuals = $statistics->findNumberOfIndividuals();
            $families = ceil(($statistics->findNumberOfFamilies()));

            
            ?>
            <section class="preloader">
                <div class="spinner">
                    <span class="spinner-rotate"></span>
                </div>
            </section>

            <?php 
                $selected = array_key_exists("display_type", $this->request) ? $this->request['display_type'] : 'horizontal';
            ?>

            <!-- Main Home Section -->
        <div style='position:relative;'>
            <section data-stellar-background-ratio="1" id="home">
                <div style="width:25%; float:left;"></div>

                <div style="width:50%; float:left;">
                    <!-- Stats Box -->
                    <div id="statistics_box">
                        <div id="individuals">
                            <div id="title" style="color:white;">
                                <span id="numberOfPeople"></span> People
                            </div>
                        </div>
                        <div id="families">
                            <div id="title" style="color:white; padding-left:10px;">
                                <span id="numberOfFamilies"></span> Families!
                            </div>
                        </div>
                    </div>

                    <!-- Animated Counter Scripts -->
                    <script>
                        document.addEventListener("DOMContentLoaded", function () {
                            const totalNumberOfPeople = parseInt(<?php echo $individuals; ?>);
                            for (let i = 0; i < totalNumberOfPeople; i++) {
                                setTimeout(() => {
                                    document.getElementById('numberOfPeople').innerHTML = i + 1;
                                }, i * 150);
                            }
                        });

                        document.addEventListener("DOMContentLoaded", function () {
                            const totalNumberOfFamilies = parseInt(<?php echo $families; ?>);
                            for (let i = 0; i < totalNumberOfFamilies; i++) {
                                setTimeout(() => {
                                    document.getElementById('numberOfFamilies').innerHTML = i + 1;
                                }, i * 100);
                            }
                        });
                    </script>

                    <!-- Info and Search Form -->
                    <div class="home-info">
                        <h3>Family Tree</h3>
                        <h1>We help you view your family heritage!</h1>
                        <form enctype="multipart/form-data" method="GET" style="padding-top:1px;">
                            <div class="form-group" id="input_search">
                                <input type="text" 
                                    id="searchdynamic" 
                                    autocomplete="off" 
                                    class="form-control rounded-0" 
                                    style="box-shadow: 0 0 7px 5px #7F4444; border-radius:3px; width:80%;" 
                                    placeholder="<?php echo $personName; ?>" />
                            </div>
                            <div id="searchForm" class="mt-2" style="position:relative;"></div>
                            <script src="js/optionList.js"></script>

                            <!-- Display Type Options -->
                            <div id="options">
                                <div class="form-check-inline">
                                    <input type="radio" id="horizontal" name="display_type" value="horizontal" class="form-check-input"
                                        <?= $selected === 'horizontal' ? 'checked' : '' ?>>
                                    <label for="horizontal" class="form-check-label">Standard</label>
                                </div>
                                <div class="form-check-inline">
                                    <input type="radio" id="vertical" name="display_type" value="vertical" class="form-check-input"
                                        <?= $selected === 'vertical' ? 'checked' : '' ?>>
                                    <label for="vertical" class="form-check-label">List</label>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>


                <!-- Chat Container -->
                <div style="width:21%;display:none;z-index:1001;" id='chatbox'>
                    <div class="container-fluid">
                        <div id="chat-container">
                            <div class="col-md-12 col-sm-12">
                                <div id="header">
                                    <div id="image"><img src="img/person.png" alt=""/></div>
                                    <div id="text" style="text-align:left;">
                                        <div id="message">Have a question?</div>
                                        <div id="quickness">🟢 We'll be happy to help!</div>
                                    </div>
                                </div>

                                <div id="chat-box"></div>

                                <div id="chatbox-bottom">
                                    <input type="text" id="question" placeholder="Type your message here..."/>
                                    <button onclick="sendMessage()"><img src="img/submit_button2.png"/></button>
                                </div>


                                <script>
                                    async function sendMessage() {
                                        const input = document.getElementById("question");
                                        const message = input.value.trim();
                                        if (!message) return;

                                        const chatBox = document.getElementById("chat-box");
                                        chatBox.innerHTML += `<div class="message user" style="width:100%; text-align:left;">You: ${message}</div>`;
                                        chatBox.scrollTop = chatBox.scrollHeight;

                                        input.value = "";
                                        chatBox.innerHTML += "<div class='dot-pulse' id='message_wait'></div>";

                                        const response = await fetch("chat.php", {
                                            method: "POST",
                                            headers: { "Content-Type": "application/x-www-form-urlencoded" },
                                            body: `message=${encodeURIComponent(message)}`
                                        });

                                        const reply = await response.text();
                                        document.getElementById("message_wait").remove();
                                        chatBox.innerHTML += `<div class="message bot">Bot: ${reply}</div>`;
                                        chatBox.scrollTop = chatBox.scrollHeight;
                                    }
                                </script>
                            </div>
                        </div>
                    </div>
                </div>
                <div id='chatbox-button' style='margin-right:0px;' onclick='displayChatbox()'>
                    
                        <img src='img/chatbox-icon.png' alt=''/>
                </div>
                <script>
                    function displayChatbox(){
                        chatbox = document.getElementById('chatbox');
                        if(chatbox.style.display == "block"){
                            document.getElementById("chatbox").style.display = "none";
                        }
                        else if(chatbox.style.display == 'none'){
                            document.getElementById("chatbox").style.display = "block";
                        }
                    }
                </script>
            </section>
        </div>
            <!-- Optional Results Section -->
            <?php if (array_key_exists("display_type", $this->request) || array_key_exists("pageType", $this->request)) { ?>
                <section data-stellar-background-ratio="0.5" id="result">
                    <div class="container">
                        <div class="row"><a href="#home"><img src="img/scrollUp.png" alt=""/></a></div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="section-title">
                                    <h2 style="color:#7F4444;font-weight:bold;">Search Value: <?php echo $this->request['personName']; ?></h2>
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12">
                                <?php echo $this->object; ?>
                            </div>
                        </div>
                    </div>
                </section>
            <?php } ?>


            <section id="contact" data-stellar-background-ratio="0.5">
                <div class="container">
                    <div class="row">

                            <div class="col-md-offset-1 col-md-10 col-sm-12">
                                <form id="contact-form" role="form" action="" method="post">
                                    <div class="section-title">
                                        <h1>Report Errors On The Family Tree</h1>
                                        <div style='font-size:18px;'> Use the following form to report any errors in the family tree(e.g. incorrect relationshps,
                                        bio information, etc)</div>
                                    </div>

                                    <div class="col-md-4 col-sm-4">
                                        <input type="text" class="form-control" placeholder="Full name" name="name" required="">
                                    </div>
                                    <div class="col-md-4 col-sm-4">
                                        <input type="email" class="form-control" placeholder="Email address" name="email" required="">
                                    </div>
                                    <div class="col-md-4 col-sm-4">
                                        <input type="submit" id='submitMessage' class="form-control" name="send-message" value="Send Message">
                                    </div>
                                    <div class="col-md-12 col-sm-12">
                                        <textarea class="form-control" rows="8" placeholder="Error Message" name="message" required=""></textarea>
                                    </div>
                                    <input type='hidden' name='req' value='contact'/>
                                </form>
                            </div>

                    </div>
                </div>
            </section>       

            <!-- Status Footer -->
            <section id="statusBar">
                <div>Last Modified: <?php echo $statistics->getLatestDate(); ?></div>
                <div>v.1.0.0</div>
            </section>

            
            <!-- Scripts -->
            <script src="js/jquery.js"></script>
            <script src="js/bootstrap.min.js"></script>
            <script src="js/jquery.stellar.min.js"></script>
            <script src="js/owl.carousel.min.js"></script>
            <script src="js/smoothscroll.js"></script>
            <script src="js/custom.js"></script>
        <?php
        }
    }
    public function setTermsLinksNavMenu(){
    ?>  
            
               <section class="navbar custom-navbar navbar-fixed-top" role="navigation" >
                <div class="container">

                    <div class="navbar-header">
                            <button class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                                <span class="icon icon-bar"></span>
                                <span class="icon icon-bar"></span>
                                <span class="icon icon-bar"></span>
                            </button>

                            <a href="index.php?req=searchForm" class="navbar-brand" style='color:black;' id='brand'>Family Tree</a>
                    </div>

                    <div class="collapse navbar-collapse" id='headerLinks'>
                            <ul class="nav navbar-nav">
                                <li><a href="?req=searchForm#home" class="smoothScroll" style='color:black;'>Home</a></li>
                            </ul>
                            <ul class="nav navbar-nav" >
                                <li><a href="?req=searchForm#contact" class="smoothScroll" style='color:black;'>Contact</a></li>
                            </ul>

                            <ul class="nav navbar-nav navbar-right">
                                <li><a href="admin/login.php" style='color:black;' target = "_blank" class='smoothScroll'>Login</a></li>
                            </ul>
                    </div>
                </div>
            </section>
    <?php
    }
    public function setMainLinksNavMenu(){ 
        ?>

            <section class="navbar custom-navbar navbar-fixed-top" role="navigation">
                <div class="container">

                    <div class="navbar-header">
                            <button class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                                <span class="icon icon-bar"></span>
                                <span class="icon icon-bar"></span>
                                <span class="icon icon-bar"></span>
                            </button>

                            <a href="index.php?req=searchForm" class="navbar-brand" id='brand'>Family Tree</a>
                    </div>

                    <div class="collapse navbar-collapse" id='headerLinks'>
                            <ul class="nav navbar-nav" >
                                <li><a href="#home" class="smoothScroll" >Home</a></li>
                            </ul>
                            <ul class="nav navbar-nav" >
                                <li><a href="#contact" class="smoothScroll" >Contact</a></li>
                            </ul>

                            <ul class="nav navbar-nav navbar-right">
                                <li><a href="admin/login.php" target = "_blank" class='smoothScroll'>Login</a></li>
                            </ul>
                    </div>

                </div>
            </section>


        <?php
    }
    public function setFooter(){
        ?>
        <div class='container-fluid' style='box-shadow: 0 -5px 10px -5px #7F4444;'>
            <div class='row' id='footer'>
                <p style="text-align: center; font-size: 14px; color: black; margin-top: 20px;margin-bottom:20px;">
                    © <?php echo date("Y"); ?> Family Tree. All rights reserved. Please view our <a href="?req=termsofuse" style='text-decoration:underline;color:#7F4444;'>Terms of Use</a> & <a href="?req=privacynotice" style='color:#7F4444;text-decoration:underline;'>Privacy Notice</a>
                </p>
            </div>
        </div>
        <?php
    }
}
