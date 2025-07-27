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
        <section data-stellar-background-ratio="1" id='home'>
                <div style='width:25%;'></div>
                <div style='width:50%;'>
                        <div>
                                    <div  id='statistics_box'>
                                        <div id='individuals'>
                                            <div id='title' style='color:white;'><span id='numberOfPeople'></span> People </div>
                                        </div>
                                        <div id='families'>
                                            <div id='title' style='color:white;padding-left:10px;'><span id='numberOfFamilies'></span> Families </div>
                                        </div>
                                    </div>
                                    <script>
                                        document.addEventListener("DOMContentLoaded", function() {
                                            const totalNumberOfPeople = parseInt(<?php echo $individuals; ?>);
                                            let numberOfPeople = 0;

                                            function incrementNums(i) {
                                                numberOfPeople = i + 1;
                                                document.getElementById('numberOfPeople').innerHTML = numberOfPeople;
                                            }

                                            for (let i = 0; i < totalNumberOfPeople; i++) {
                                                setTimeout(function() {
                                                    incrementNums(i);
                                                },i* 150); // delay increases with i
                                            }
                                        });

                                        document.addEventListener("DOMContentLoaded", function() {
                                            const totalNumberOfFamilies = parseInt(<?php echo $families; ?>);
                                            let numberOfFamilies = 0;

                                            function incrementNums(i) {
                                                numberOfFamilies = i + 1;
                                                document.getElementById('numberOfFamilies').innerHTML = numberOfFamilies;
                                            }

                                            for (let i = 0; i < totalNumberOfFamilies; i++) {
                                                setTimeout(function() {
                                                    incrementNums(i);
                                                },i* 100); // delay increases with i
                                            }
                                        });

                                    </script>
                        </div>

                         <div class='home-info'>
                              <h3>Family Tree</h3>
                              <h1>We help you view your family heritage!</h1>
                              <form enctype="multipart/form-data"  action="" method="GET" style='padding-top:1px;'>
                                    <div class='form-group' id='input_search'>
                                        <input 
                                            type="text" 
                                            id="searchdynamic" 
                                            autocomplete="off" 
                                            class="form-control rounded-0" 
                                            style="box-shadow: 0 0 7px 5px #7F4444;border-radius:3px;width:80%;"
                                            placeholder = "<?php echo $personName; ?>"
                                        />
                                    </div> 
                                    <div id="searchForm" class='mt-2' style="position:relative;"></div>
                                    <script src='js/optionList.js'></script>

                                    <div id='options'>
                                        <div class='form-check-inline'> 
                                            <input type="radio" id="horizontal" name="display_type" value="horizontal" class='form-check-input'
                                                <?= $selected === 'horizontal' ? 'checked' : '' ?>>
                                            <label for="horizontal" class='form-check-label'>Standard</label>
                                        </div>  
                                        <div class='form-check-inline'>
                                            <input type="radio" id="vertical" name="display_type" value="vertical" class='form-check-input'
                                                <?= $selected === 'vertical' ? 'checked' : '' ?>>
                                            <label for="vertical" class='form-check-label'>List (Format)</label>  
                                        </div>
                                    </div>

                                </form>
                        </div>
                </div>
                <div style='width:25%;margin-top:80px;'>
                    <div class='container-fluid'>
                        <div class="col-md-12 col-sm-12">
                                        <div id='header'>
                                            <div id='image'><img src='img/person.png' alt=''/></div>
                                            <div id='text' style='text-align:left;'>
                                                <div id='message'>Have a question?</div>
                                                <div id='quickness'>🟢 We'll be happy to help!</div>
                                            </div>
                                        </div>

                                        <div id="chat-box"></div>
                                        
                                        <div id='chatbox-bottom'>
                                            <input type="text" id="question" placeholder="Type your message here..."/>
                                            <button onclick="sendMessage()"><img src='img/submit_button2.png'/></button>
                                        </div>


                                        <div id='poweredBy'>Powered By <a href='https://python.langchain.com/docs/integrations/chat/google_generative_ai/' target='_blank'>Gemini-2.0-Flash</a></div>
                                        <script>
                                            
                                            async function sendMessage() {
                                            const input = document.getElementById("question");
                                            console.log(input)

                                            const message = input.value.trim();
                                            if (!message) return;
                                            
                                            const chatBox = document.getElementById("chat-box");
                                            

                                            

                                            chatBox.innerHTML += `<div class="message user" id='user_message' style='width:100%;text-align:left;'>You: ${message}</div>`;
                                            chatBox.scrollTop = chatBox.scrollHeight;

                                            input.value = "";
                                            
                                            chatBox.innerHTML += "<div class='dot-pulse' id='message_wait'><div>";
                                            message_wait = document.getElementById("message_wait");

                                            const response = await fetch("chat.php", {
                                                method: "POST",
                                                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                                                body: `message=${encodeURIComponent(message)}`
                                            });

                                            const reply = await response.text();
                                            
                                            message_wait.remove();

                                            chatBox.innerHTML += `<div class="message bot" id='bot_message'>Bot: ${reply}</div>`;
                                            chatBox.scrollTop = chatBox.scrollHeight;
                                            }
                                        </script>
                        </div>
                    </div>
                </div>
        </section>

        <?php if(array_key_exists("display_type",$this->request) || array_key_exists("pageType",$this->request)){
            ?>
            <section data-stellar-background-ratio="0.5" id='result' >
                <div class='container'>
                    <div class='row'><a href='#home'><img src='img/scrollUp.png' alt=''/></a></div>
                </div>
                <div class='container'>
                    <div class='row'>
                        <div class='col-md-12 col-sm-12'>
                            <div class='section-title'>
                                    <h2 style='color:#7F4444;font-weight:bold;'>Search Value: <?php echo $this->request['personName']; ?></h2>
                            </div>
                        </div>
                        <div class='col-md-12 col-sm-12'>
                            <?php echo $this->object; ?>
                        </div>
                    </div>
                </div>
            </section>

        <?php } ?>
                <section id="views" data-stellar-background-ratio="1">
                <div class="container">
                    <div class="row">

                            <div class=" col-md-12 col-sm-12" >
                                <div class="section-title" >
                                    <h1>Standard v. List Display Types</h1>
                                </div>
                            </div>

                            <div class="col-md-12 col-sm-12">
                                <img src="img/views.png" alt="" id='display_types_img' style='height:auto;width:100%;'/>
                            </div>
                            
                    </div>
                </div>
            </section>

        <div id="statusBar" style=" margin-bottom:30px; height:2rem; line-height:2rem; font-size:1.8rem; padding: 0 20px; display: flex; justify-content: space-between; align-items: center;">
            <div>Last Modified: <?php echo $statistics->getLatestDate(); ?></div>
            <div>v.1.0.0</div>
        </div>

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
                                <li><a href="?req=searchForm#AIFamilyTree" class="smoothScroll" style='color:black;'>Tree Search</a></li>
                                <li><a href="?req=searchForm#views" class="smoothScroll" style='color:black;'> Display Types</a></li>
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
                                <li><a href="#AIFamilyTree" class="smoothScroll">Tree Search</a></li>
                                <li><a href="#views" class="smoothScroll"> Display Types</a></li>
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
