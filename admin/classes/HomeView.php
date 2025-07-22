<?php
class HomeView extends View{
    public function render(){
        $homeDropdownImagePath = $this->SettingService->getSettingValueByName("homeDropdownImagePath");
        $homeDropdownImagePath = new Setting($homeDropdownImagePath);
        $homeErrorImagePath = $this->SettingService->getSettingValueByName("homeErrorImagePath");
        $homeErrorImagePath = new Setting($homeErrorImagePath);
        if($this->request['req'] == "search"){
            ?>
                <section id='searchResult' data-stellar-background-ratio='0.5'>
                    <div class="row col-4">
                        <form enctype="multipart/form-data" action="" method="GET">
                            <div class="form-group">
                                <input 
                                    type="text" 
                                    id="searchdynamic" 
                                    name="search" 
                                    autocomplete="off" 
                                    class="form-control rounded-0 pl-0 pb-1 pr-0" 
                                    style="box-shadow: 5px 5px 7px 5px #06635a;"
                                    placeholder="<?php echo $this->request['personName']; ?>"
                                />
                                <input type="hidden" id="pid" name="pid" value="<?php echo $this->request['pid']; ?>" />
                            </div>
                            <div id="searchForm" class="mt-2"></div>
                            <div id='options'>
                                    <div class='form-check-inline mt-0 ps-1'> 
                                        <input type="radio" id="horizontal" name="display_type" value="horizontal" class='form-check-input' <?php if($this->request['display_type'] == 'horizontal'){echo 'checked';} ?>>
                                        <label for="horizontal" class='display_type' style='color:black;'>Standard Format</label>
                                    </div>  
                                    <div class='form-check-inline mb-2'>
                                        <input type="radio" id="vertical" name="display_type" value='vertical' class='form-check-input' <?php if($this->request['display_type'] == 'vertical'){echo 'checked';} ?> />
                                        <label for="vertical" class='display_type' style='color:black;'>List Format</label>  
                                    </div>
                            </div>
                        </form>
                    </div> 
                    <div style='margin-top:2px;'>
                        <?php echo $this->object; ?>
                    </div>
                </section> 

                <script>
                $(document).ready(function(){
                    $(document).on("click",".display_type",function(event){
                        display_type = $(this).attr("value");
                        $.ajax({
                            url:"ajax.php",
                            method:"POST",
                            data:{
                                "display_type":display_type
                            },
                            success:function(response){
                                console.log(response);
                            }
                        })
                    })
                })
                $(document).ready(function(){
                    $(document).on("click","#father",function(event){
                        pid = $(this).attr("value");
                        $.ajax({
                            url:"ajax.php",
                            method:"POST",
                            data:{
                                "pid": pid
                            },
                            success: function(response){
                                console.log(response);
                            }
                        })
                    })
                })



                $(document).ready(function(){
                    console.log("document loaded");
                    $(document).on("click",'#partnerWithFather',function(event){
                        event.preventDefault();
                        value = $(this).attr("value");
                        value = value.split(",");
                        $.ajax({
                            url:"ajax.php",
                            method: "POST",
                            data:{
                                "req":"search",
                                "select":value[0],
                                "pid":value[1]
                            },
                            success: function(response){
                                console.log(response);
                                document.getElementById('familyTreeSearch').innerHTML=response;
                            }
                        })
                    })
                })
                $(document).ready(function() {
                        $(document).on("keyup", "#searchdynamic", function(event) {
                            var sValue = $(this).val();
                            $.ajax({
                                url: "ajax.php",
                                method: "POST",
                                data: {
                                    sValue: sValue
                                },
                                success: function(persons) {
                                        persons = JSON.parse(persons);
                                        var dropdown = '<ul id="optionSearchResults" class="container">';

                                        for (var i = 0; i < persons.length; i++) {
                                            var person = persons[i];
                                            var name = person['firstName'] + " " + person['lastName'];
                                            var displayType = $('input[name="display_type"]:checked').val();
                                            
                                            // Create a form for each person
                                            dropdown += '<li style="border:1px solid #06635a;" id="optionSearchResult">';
                                            dropdown += '<form  method="GET">';
                                            dropdown += "<input type='hidden' name='req' value='search'/>";
                                            dropdown += "<input type='hidden' name='select' value=''/>";

                                            dropdown += '<input type="hidden" name="pid" value="' + person['pid'] + '"/>';
                                            dropdown += '<input type="hidden" name="personName" value="' + name + '"/>';
                                            dropdown += '<button type="submit" id="searchOption">';
                                            dropdown += '<img src="<?php echo $homeDropdownImagePath->getValue();?>' + person['pid'] + '.png" onerror="this.onerror=null; this.src=\'admin/img/man.png\';" style="height: 20px; margin-right: 8px;" />   ' + name;
                                            dropdown += '</button>';
                                            dropdown += "<input type='hidden' name='display_type' value='" + displayType + "'/>";
                                            dropdown += '</form>';
                                            dropdown += '</li>';
                                        }


                                        dropdown += '</ul>';                                    
                                    document.getElementById('searchForm').innerHTML = dropdown;
                                },
                                error: function(xhr, status, error) {
                                    console.error("AJAX Error: ", status, error);
                                }
                            });
                        });
                    });

                </script>

                <?php
        }
        else if($this->req == "termsofuse" || $this->req == "privacynotice"){
            $this->renderTemplate($this->req);
        }
        else if($this->req == "page_error"){
            echo $this->object;
        }
        else if($this->req == "page_content"){
            echo $this->object;
        }
        else if($this->req == "page_profile"){
            echo "<div style='padding-top:105px;'>".$this->object."</div>";
        }

        else if($this->request['req'] == "searchForm"){
            if(array_key_exists('personName',$this->request)){$personName = $this->request['personName'];}
            else{$personName = "Search for family tree persons";}

            $statistics = new Statistics();

            $individuals = $statistics->findNumberOfIndividuals();
            $families = ceil(($statistics->findNumberOfFamilies())/2);
        ?>
        <section class="preloader">
          <div class="spinner">
               <span class="spinner-rotate"></span>
          </div>
        </section>
        <?php 
        $selected = array_key_exists("display_type", $this->request) ? $this->request['display_type'] : 'horizontal';
         ?>
        <section data-stellar-background-ratio="1" id='home' >
          <div class="container">
               
               <div class="row" >
                    <div class="col-md-offset-3 col-md-6 col-sm-12">
                         <div class="home-info">
                              <h3>Family Tree</h3>
                              <h1>We help you view your family heritage!</h1>
                              <form enctype="multipart/form-data"  action="" method="GET" style='padding-top:1px;'>
                                    <div class='form-group' id='input_search'>
                                        <input 
                                            type="text" 
                                            id="searchdynamic" 
                                            autocomplete="off" 
                                            class="form-control rounded-0" 
                                            style="box-shadow: 0 0 7px 5px #06635a;border-radius:3px;;width:80%;"
                                            placeholder = "<?php echo $personName; ?>"
                                        />
                                    </div> 
                                    <div id="searchForm" class='mt-2' style="position:relative;"></div>

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
               </div>
          </div>
        </section>
        <script src='js/optionList.js'></script>
        <?php if(array_key_exists("display_type",$this->request) || array_key_exists("pageType",$this->request)){?>
            <section data-stellar-background-ratio="0.5" id='result' >
                <div class='container'>
                    <div class='row'><a href='#home'><img src='img/scrollUp.png' alt=''/></a></div>
                </div>
                <div class='container'>
                    <div class='row'>
                        <div class='col-md-12 col-sm-12'>
                            <div class='section-title'>
                                    <h1>Search Results:</h1>
                            </div>
                        </div>
                        <div class='col-md-12 col-sm-12'>
                            <?php echo $this->object; ?>
                        </div>
                    </div>
                </div>
            </section>
        <?php } ?>
        <section data-stellar-background-ratio="1" id='AIFamilyTree' >
            <div class="container">
                <div class="row">

                        <div class="col-md-12 col-sm-12">
                            <div class="section-title">
                                <h1>Tree Search:</h1>
                            </div>
                        </div>
                    
                        <div class="col-md-6 col-sm-6" id='statistics_box' >
                                <div class='col-md-6' id='individuals'>
                                        <div id='title'>Number of People: </div>
                                        <div id='numberOfPeople'></div>
                                </div>
                                <div class='col-md-6' id='families'>
                                        <div id='title'>Number of Families: </div>
                                        <div id='numberOfFamilies'></div>
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
                                    },i* 100); // delay increases with i
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
                                    },i* 500); // delay increases with i
                                }
                            });

                        </script>
                        <div class='col-md-1 col-sm-4'></div>
                        <div class="col-md-4 col-sm-4">
                                        <div id='header'>
                                            <div id='image'><img src='img/person.png' alt=''/></div>
                                            <div id='text'>
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
                                            

                                            

                                            chatBox.innerHTML += `<div class="message user" id='user_message'>You: ${message}</div>`;
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
                        <div class='col-md-1 col-sm-1'></div>
                </div>
            </div>
        </section>

        <section id="views" data-stellar-background-ratio="1">
          <div class="container">
               <div class="row">

                    <div class=" col-md-12 col-sm-12" >
                         <div class="section-title" >
                              <h1>Standard v. List Display Types</h1>
                         </div>
                    </div>

                    <div class="col-md-12 col-sm-12">
                         <img src="img/views.png" alt=""/>
                    </div>
                    
               </div>
          </div>
        </section>

     <section id="contact" data-stellar-background-ratio="0.5">
          <div class="container">
               <div class="row">

                    <div class="col-md-offset-1 col-md-10 col-sm-12">
                         <form id="contact-form" role="form" action="" method="post">
                              <div class="section-title">
                                   <h1>Report Errors In The Family Tree
                                   </h1>
                              </div>

                              <div class="col-md-4 col-sm-4">
                                   <input type="text" class="form-control" placeholder="Full name" name="name" required="">
                              </div>
                              <div class="col-md-4 col-sm-4">
                                   <input type="email" class="form-control" placeholder="Email address" name="email" required="">
                              </div>
                              <div class="col-md-4 col-sm-4">
                                   <input type="submit" class="form-control" name="send message" value="Send Message">
                              </div>
                              <div class="col-md-12 col-sm-12">
                                   <textarea class="form-control" rows="8" placeholder="Your message" name="message" required=""></textarea>
                              </div>
                              <input type='hidden' name='req' value='contact'/>
                         </form>
                    </div>

               </div>
          </div>
     </section>       




        <script src="js/jquery.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/jquery.stellar.min.js"></script>
        <script src="js/owl.carousel.min.js"></script>
        <script src="js/smoothscroll.js"></script>
        <script src="js/custom.js"></script>

           <!-- <div id='AIFamilyTree' class='container-fluid'>

                <div class='row'>
                    <div class='col-md-8' id='AIimage'>
                        <img src='img/chatbox.png' alt=''/>
                    </div>
                    <div class='col-md-3'>
                        <div id='header'>
                            <div id='image'><img src='img/person.png' alt=''/></div>
                            <div id='text'>
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
                            

                            

                            chatBox.innerHTML += `<div class="message user" id='user_message'>You: ${message}</div>`;
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
                <div class='col-md-1'></div>
            </div>
            <div id='history'>
                <div class='row' id='title'>
                    <div class='col-md-1'>
                        History:
                    </div>
                </div>
                <div class='row' id='content'>
                    <div class='col-md-8'></div>
                    <div class='col-md-4'><img src='img/history_background.png' alt=''/></div>
                </div>
            </div>
            <div id='views'>
                <div class='row' id='title'>
                    Vertical vs. Standard Display Type:
                </div>
                <div class='row' id='description'>
                    These are the two formats you can view the family tree:
                </div>
                <div class='row' id='content'>
                    <div class='col-md-9'>
                        <img src='img/views.png' alt=''/>
                    </div>
                </div>
            </div>
            <script>
                document.addEventListener("DOMContentLoaded",function(){
                    families_content = document.getElementById('families-content');
                    let count = 0;
                    let families_target = ;
                    let duration = 100000;
                    let increment = 1;
                    let updateCounter = () => {
                        count = count+increment;
                        if(count <= families_target){
                            families_content.textContent = Math.floor(count);
                            requestAnimationFrame(updateCounter)
                        }
                        else{
                            families_content.textContent = Math.ceil(families_target/2);
                        }
                    }
                    updateCounter();

                })
                document.addEventListener("DOMContentLoaded",function(){
                    individuals_content = document.getElementById('individuals-content');
                    let count = 0;
                    let individuals_target = ;

                    console.log(individuals_target);
                    let duration = 100000;
                    let increment = 1;
                    let updateCounter = () => {
                        count = count+increment;
                        if( count <= individuals_target){
                            individuals_content.textContent =  Math.floor(count);
                            requestAnimationFrame(updateCounter);
                        }
                        
                        else{
                            individuals_content.textContent = individuals_target;
                        }
                    }
                    updateCounter();
                });
                $(document).ready(function() {
                    $(document).on("keyup", "#searchdynamic", function(event) {
                        var sValue = $(this).val();
                        console.log(sValue);
                        $.ajax({
                            url: "ajax.php",
                            method: "POST",
                            data: {
                                sValue: sValue
                            },
                            success: function(persons) {
                                console.log(persons);
                                persons = JSON.parse(persons);
                                var dropdown = '<ul id="searchDropdown" style="list-style: none; position:absolute; padding: 0; margin: 0;width:100%;">';

                                for (var i = 0; i < persons.length; i++) {
                                    var person = persons[i];
                                    var name = person['firstName'] + " " + person['lastName'];

                                    dropdown += '<li style="border:1px solid #06635a;">';
                                    dropdown += '<form method="POST" action="index.php">';
                                    dropdown += "<input type='hidden' name='req' value='search'/>";
                                    dropdown += "<input type='hidden' name='select' value=''/>";
                                    dropdown += '<input type="hidden" name="pid" value="' + person['pid'] + '"/>';



                                    
                                    dropdown += '<input type="hidden" name="personName" value="' + name + '"/>';







                                    dropdown += '<button type="submit" id="searchOption" style="text-align: left; border: none; padding: 8px;">';
                                    dropdown += '<img src="' + person['pid'] + '.png" onerror="this.onerror=null; this.src=\'admin/img/man.png\';" style="height: 20px; margin-right: 8px;" />' + name;
                                    dropdown += '</button>';

                                    dropdown += '</form>';
                                    dropdown += '</li>';
                                }

                                dropdown += '</ul>';
                                console.log(dropdown);
                                
                                document.getElementById('searchForm').innerHTML = dropdown;
                            }
                        });
                    });

                    $(document).on("click", "#searchOption", function(event) {
                        var pid = $(this).attr('value');
                        var name = $(this).attr('name');
                        console.log("PID: " + pid + " Name: " + name);
                        // Set the value of the input field to the PID
                        $("#searchdynamic").val(name);
                        // Optionally, display the name in the input field or elsewhere
                        $("#pid").val(pid)
                    });
                });

            </script>-->
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

                            <a href="index.php?req=searchForm" class="navbar-brand" style='color:black;'>Family Tree</a>
                    </div>

                    <div class="collapse navbar-collapse">
                            <ul class="nav navbar-nav">
                                <li><a href="?req=searchForm#home" class="smoothScroll" style='color:black;'>Home</a></li>
                                <li><a href="?req=searchForm#AIFamilyTree" class="smoothScroll" style='color:black;'>Tree Search</a></li>
                                <li><a href="?req=searchForm#views" class="smoothScroll" style='color:black;'> Display Types</a></li>
                                <li><a href="?req=searchForm#contact" class="smoothScroll"> Contact Us</a></li>

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

                            <a href="index.php?req=searchForm" class="navbar-brand">Family Tree</a>
                    </div>

                    <div class="collapse navbar-collapse">
                            <ul class="nav navbar-nav">
                                <li><a href="#home" class="smoothScroll">Home</a></li>
                                <li><a href="#AIFamilyTree" class="smoothScroll">Tree Search</a></li>
                                <li><a href="#views" class="smoothScroll"> Display Types</a></li>
                                <li><a href="#contact" class="smoothScroll"> Contact Us</a></li>
                            </ul>
                            <ul class="nav navbar-nav navbar-right">
                                <li><a href="http://localhost/gracian/familytree/admin/login.php" target = "_blank" class='smoothScroll'>Login</a></li>
                            </ul>
                    </div>

                </div>
            </section>


        <?php
    }
    public function setFooter(){
        ?>
        <div class='container-fluid'>
            <div class='row' id='footer'>
                <p style="text-align: center; font-size: 14px; color: black; margin-top: 20px;margin-bottom:20px;">
                    © <?php echo date("Y"); ?> Family Tree. All rights reserved. Please view our <a href="?req=termsofuse" style='text-decoration:underline;color:green;'>Terms of Use</a> & <a href="?req=privacynotice" style='color:green;text-decoration:underline;'>Privacy Notice</a>
                </p>
            </div>
        </div>
        <?php
    }
}
