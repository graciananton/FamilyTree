<?php
class Home extends View{
    public function renderTemplate(string $template):void{

    }
    public function render(){
        $timeOfDay = $this->HomeService->getHour();
        $introduction = $timeOfDay.$this->object->getName();

        if($this->object->getRole() == "admin"){
            $persons = $this->HomeService->getPersons();
        }   
        else{
            $persons = $this->HomeService->getUserPersons();
            
            
            $UAPIDperson = $this->DatabaseManager->getPerson("pid",$this->object->getUAPID());
            $UAPIDperson = new Person($UAPIDperson);
        }
            $user = $this->DatabaseManager->getUser("emailAddress",$this->object->getUsername());
            $user = new User($user);
            $history = explode("|",$user->getHistory());
            foreach($history as $element){
                if (preg_match('/(?:ep|i|dp):\s*(\d+)/', $element, $match)) {
                    $ids[] = $match[1]; // Extract just the number
                }
                if(preg_match("/ep|i|dp/i",$element,$match)){
                    $actions[] = $match[0];
                }
            }
            for($i=0;$i<count($ids);$i++){
                if($actions[$i] != "dp"){
                    $person = $this->DatabaseManager->getPerson("pid",$ids[$i]);
                    if(is_object($person)){
                        $person = new Person($person);
                        $ids[$i] = $person->getFirstName()." ".$person->getLastName();

                    }
                }
            }

        ?>
        <div class='container-fluid pt-2'>
            <div class='row'>
                <div class='col-12 mb-5'>
                <?php echo "<div class='fw-bold' style='color:#7F4444;font-size:30px;'>".$introduction."</div>"; ?>
                </div>
            </div>
            <div class='row justify-content-center '>
                    <div class='col-md-2'>
                        <div class='row fw-semibold'>You can do the following:</div>
                        <ul>
                            <li><a style='text-decoration:none;' href="../index.php?req=searchForm">View Tree 📊</a></li>
                            <li><a style='text-decoration:none;' href="?req=pf-insert_person">Add Members ➕</a></li>
                            <li><a style='text-decoration:none;' href="?req=pf-display_persons">Edit Members ✏️</a.</li>
                            <?php if($this->object->getUAPID() == "admin"){ ?>
                            <li><a style='text-decoration:none;' href="?req=uf-table">Add User 👤➕</a></li>
                            <li><a style='text-decoration:none;' href="?req=ut-table">Edit User Table 📝</a></li>
                            <li><a style='text-decoration:none;' href="?req=es-table">Edit Settings ⚙️</a></li>
                            <li><a style='text-decoration:none;' href="?req=generateImages">Generate Images 🖼️</a></li>
                            <?php } ?>
                            <li><a style='text-decoration:none;' href="logout.php">Logout 🔒</a></li>
                        </ul>
                    </div>
                    <?php if($this->object->getRole() != "admin"){ ?>
                    <div class='col-md-2'>
                        You can edit all people who are descendants of:
                        <ul>
                            <li><?php echo $UAPIDperson->getFirstName();?>
                        </ul>
                    </div>
                    <?php } ?>
                    <div class='col-md-3'>
                        <div class='fw-semibold'>
                            You have added/edited/deleted the following individuals to our system:
                        </div>
                        <ul id='beforeDropdown' class='custom-summary mb-0'>
                            <?php
                            ?>
                        </ul>
                    </div>
                    <div class='col-md-2'>
                        <div class='fw-semibold'>
                            You are allowed to edit the following individuals:
                        </div>
                        <ul id='beforeDropdown' class='custom-summary mb-0'>
                            <?php for($i=0; $i < min(5, count($persons)); $i++): ?>
                            <li><?= $persons[$i]->firstName . " " . $persons[$i]->lastName ?></li>
                            <?php endfor; ?>
                        </ul>
                        <?php if(count($persons) > 5): ?>
                            <a href="#" id='showMore' onclick="showMore()" class='pl-4'>Show more</a>

                            <ul id='duringDropdown' style='display:none'>
                            <?php for($i = 5; $i < count($persons); $i++): ?>
                                <li><?= $persons[$i]->firstName . " " . $persons[$i]->lastName ?></li>
                            <?php endfor; ?>

                            <a href="#" id='showLess' onclick="showLess()" class='pl-4'>Show Less</a>
                            </ul>
                        <?php endif; ?>
                    </div>

                    <!-- Account Details Section -->
                    <div class='col-md-2'>
                        <div class='fw-semibold'>Account Details:</div>
                        <div>Name: <?= $this->object->getName(); ?></div>
                        <div class='text-nowrap'>Username: <?= $this->object->getUsername(); ?></div>
                        <div class='text-nowrap'>Password: ***** </div>
                    </div>
            </div>
        </div>
        <script>
         function showMore(){
            console.log("showMore");
            document.getElementById("duringDropdown").style.display='block';
            document.getElementById("showMore").style.display = 'none';
         } 
         function showLess(){
            console.log("showLess");
            document.getElementById('duringDropdown').style.display = 'none';
            document.getElementById('showLess').style.display = 'none';
            document.getElementById('showMore').style.display='block';
         }  
        </script>
        <?php
    }
}