<?php 
class AdminView extends View{
    public function renderSuccessMessage(){
        ?>
        <div class='container'>
            <div class='row' style='display:flex;justify-content:flex-start;align-items:flex-start;margin-top:50px;height:100vh;'>
                <div class='col-md-8 col-sm-8' style='border:3px solid #7F4444;'>
                    <div class='col-md-9 col-sm-9' style='font-size:25px;font-weight:bold;color:#7F4444;'>
                        Message:
                    </div>
                    <div class='col-md-9 col-sm-9'>
                        The update was successful!
                        <br/>Updated By: <?php echo $this->object->getName(); ?>
                        <br/>Date Updated: <?php echo date("Y/m/d"); ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    public function renderErrorMessage(){
                ?>
        <div class='container'>
            <div class='row' style='display:flex;justify-content:flex-start;align-items:flex-start;margin-top:50px;height:100vh;'>
                <div class='col-md-8 col-sm-8' style='border:3px solid #7F4444;'>
                    <div class='col-md-9 col-sm-9' style='font-size:25px;font-weight:bold;color:#7F4444;'>
                        Message:
                    </div>
                    <div class='col-md-9 col-sm-9'>
                        The update was not successful
                        <br/>By: <?php echo $this->object->getName(); ?>
                        <br/>Date: <?php echo date("Y/m/d"); ?>
                    </div>
                </div>
            </div>
        </div>
        <?php

    }
    public function render(){
        $personImagePath = $this->SettingService->getSettingValueByName("personImagePath");
        $personImagePath = new Setting($personImagePath);
        $adminErrorImagePath = $this->SettingService->getSettingValueByName("adminErrorImagePath");
        $adminErrorImagePath = new Setting($adminErrorImagePath);
        $add = $this->SettingService->getSettingValueByName('add');
        $add = new Setting($add);
        $edit = $this->SettingService->getSettingValueByName('edit');
        $edit = new Setting($edit);
        $delete = $this->SettingService->getSettingValueByName('delete');
        $delete = new Setting($delete);
        if(($this->request['req'] == "uf-table" || $this->request['req'] == "ut-edit")){
            if(!is_object($this->object)){
                $firstName = $lastName = $phoneNumber = $country = $city = $emailAddress = $role = $UAPID = $pid = "";
                $title ="Add User:";
                $this->renderHeadingInfo("addUserHeading",$title); 

            }
            else{
                $firstName = $this->object->getfirstName();
                $lastName = $this->object->getlastName();
                $phoneNumber = $this->object->getphoneNumber();
                $country = $this->object->getcountry();
                $city = $this->object->getcity();
                $emailAddress = $this->object->getemailAddress();
                $role = $this->object->getrole();
                $UAPID = $this->object->getUAPID();
                $pid = $this->object->getPid();
                $title ="Edit User:";
                $this->renderHeadingInfo("editUserHeading",$title); 

            }
            ?>
            <div id='userTable' class='container-fluid m-3 col-md-6'> 
                <form enctype = 'multipart/form-data' action='' method="POST">
                  <div class='row'>
                    <div class='form-group col-md-3'>
                        
                        <label for='firstName' class='form-label'>First Name:<span style='color:#7F4444;font-weight:bold;'>*</span></label>
                        <input type='text' class='form-control' id='firstName' name='firstName' value="<?php echo isset($firstName) ? htmlspecialchars($firstName): "" ;?>" required/>
                    </div>
                    <div class='form-group col-md-3'>
                        <label for='lastName' class='form-label'>Last Name:<span style='color:#7F4444;font-weight:bold;'>*</span></label>
                        <input type='text' class='form-control' id='lastName' name='lastName' value='<?php echo isset($lastName) ? htmlspecialchars($lastName):"";?>' required/>
                    </div>
                  </div>
                  <div class='row'>
                    <div class='form-group col-md-3'>
                        <label for='phoneNumber' class='form-label'>Phone Number:</label>
                        <input type='text' class='form-control' id='phoneNumber' name='phoneNumber' value='<?php echo isset($phoneNumber) ? htmlspecialchars($phoneNumber):"";?>'/>
                    </div>
                    <div class='form-group col-md-3'>
                        <label for='country' class='form-label'>Country:</label>
                        <input type='text' class='form-control' id='country' name='country' value='<?php echo isset($country) ? htmlspecialchars($country) : ""; ?>'/>
                    </div>
                  </div>
                  <div class='row'>
                    <div class='form-group col-md-3'>
                        <label for='city' class='form-label'>City:</label>
                        <input type='text' class='form-control' id='city' name='city' value='<?php echo isset($city) ? htmlspecialchars($city) : ""; ?>'/>
                    </div>
                    <div class='form-group col-md-3'>
                        <label for='emailAddress' class='form-label'>Email Address:</label>
                        <input type='email' class='form-control' id='emailAddress' name='emailAddress' value='<?php echo isset($emailAddress) ? htmlspecialchars($emailAddress) : "" ;?>'/>
                    </div>
                  </div>
                  <div class='form-group'>
                            <label for='role' class='fs-5'>Role:<span style='color:#7F4444;font-weight:bold;'>*</span></label>
                            <div class='form-check form-check-inline'>
                                <label for='user' class='form-check-label'>User</label>
                                <input type='radio' id='user' class='form-check-input' name='role' value='user' <?php echo (isset($role) && $role == "user") ? "checked": ""; ?> required/>
                            </div>
                            <div class='form-check form-check-inline'>
                                <label for='admin' class='form-check-label'>Admin</label>
                                <input type='radio' id='admin' class='form-check-input' name='role' value='admin' <?php echo (isset($role) && $role == "admin") ? "checked": ""; ?>/>
                            </div>
                    </div>

                  <div class='row'>
                    <div class='form-group col-md-4'>
                        <label for='UAPID' class='form-label'>UAPID:<span style='color:#7F4444;font-weight:bold;'>*</span></label>
                        <input type='text' class='form-control' id='UAPID' name='UAPID' value='<?php echo isset($UAPID) ? htmlspecialchars($UAPID) : ""; ?>' required/>
                    </div>
                  </div>
                  <div class='form-group'>
                    <?php $this->renderTemplate("addUserNote"); ?>
                  </div>
                  <input type='hidden' name='pid' value='<?php echo $pid; ?>' />
                  <div class='row'>
                    <div class='form-group col-md-4 mt-3'>
                        <?php
                        if(is_object($this->object) && isset($this->object)){
                        ?>
                            <input type='submit' id='edit' value="Edit"/>
                            <input type='hidden' name='req' value='ufe-table'/>
                            
                        <?php
                        }
                        else{
                        ?>
                            <input type='submit' id='submit' value='Insert'/>
                            <input type='hidden' name='req' value='ufs-table'/>
                        <?php
                        }
                        ?>
                    </div>
                  </div>
                </form>
            </div>
        <?php
        }
        else if($this->req == "generateImages"){
            $files = $this->ImageHandler->getFilesFromDefault();
            $title ="Generate Images:";
            $this->renderHeadingInfo("generateImages",$title); 

            ?>
            <div class='container-fluid pt-3'>
              <div class='row'>
                <div class='col-md-3'>
                The following files are all in the default directory:<br/>
                <ul>
                <?php
                for($i=0;$i<count($files);$i++){
                    echo "<li>".$files[$i]."</li>";
                }
                ?>
                </ul>
                </div>
                <div class='col-md-6'>
                    <div class='col-md-12'>Which folder/file size do you want to resize the default images</div>
                    <div class='col-md-3'>
                        <form enctype='multipart/form-data' action='' method='POST' id='imageSize'>
                            <div class='form-group'>
                                <label for='folder' class='form-label'>Folder:</label>
                                <select name='folder' id='folder' class='form-control'>
                                    <option value='Choose an option'>Select option</option>
                                <?php 
                                    $folders = $this->ImageHandler->getFoldersFromDirectory("img/people"); 
                                    for($i=0;$i<count($folders);$i++){
                                        ?>
                                        <option value="<?php echo $folders[$i]; ?>"><?php echo $folders[$i]; ?></option>
                                        <?php
                                    }
                                ?>
                                <option value="all">All Listed</option>
                            </div>
                            <div class='form-group'>
                                <input type='submit' name='resize' value='Resize Files'/>
                                <input type='hidden' value='sf-generateImages' name='req'/>
                            </div>
                            <div id='response'></div>
                        </select>
                        </div>
                    </div>
                </div>
                </div>
            </div>
            <script>
                $("#imageSize").on('submit',function(e){
                    e.preventDefault();
                    $.ajax({
                        url: "ajax.php",
                        type: "POST",
                        data: $(this).serialize(),
                        success: function(response){
                            document.getElementById("response").innerHTML = "Files successfully resized and moved to folders";
                        

                        }
                    })
                });
            </script>
            <?php
        }
        else if($this->req == "es-table"){
            $title ="Edit Settings:";
            $this->renderHeadingInfo("editSettingsHeading",$title); 

            ?>
            <div class='container-fluid mt-3'>
                <div class='row justify-content-center'>
                   <div class='col-8'>
                    <table class='table table-bordered border mt-4'>
                        <thead>
                            <tr class='text-center'><th style='color:#7F4444'>Name</th><th style='color:#7F4444'>Value</th><th style='color:#7F4444'>Edit</th></tr>
                        </thead>
                        <tbody>
                            <?php
                                for($i=0;$i<count($this->object);$i++){
                                $row = $this->object[$i];
                                    ?>
                                    <tr class='text-center'>
                                            <td><?php echo $row['name']; ?></td>
                                            <td><?php echo $row['value']; ?></td>
                                            <td><a href='?req=es-form&pid=<?php echo $row['pid']; ?>'><img src='<?php echo $edit->getValue(); ?>' alt=''/></a></td>
                                    </tr>
                                    <?php
                                }
                            ?>
                        </tbody>
                    </table>
                 </div>
                </div>
            </div>
            <?php
    
        }
        else if($this->req == "es-form"){
            $title ="Edit Setting:";
            $this->renderHeadingInfo("editSettingHeading",$title); 
        ?>
            <div class='container-fluid m-3'>
                <form enctype = 'multipart/form-data' action='' method = "GET" >
                    <div class='row'>
                        <div class='form-group col-md-3'>
                            <label for='name' class='form-label'>Name:<span style='color:#7F4444;'>*</span></label>
                            <input type='text' name='name' id='name' class='form-control'  value='<?php echo $this->object->getName(); ?>' readonly/>
                        </div>
                    </div>
                    <div class='row'>
                        <div class='form-group col-md-3'>
                            <label for='value' class='form-label'>Value:</label>
                            <input type='text' class='form-control' name='value' value='<?php echo $this->object->getValue(); ?>'/>
                        </div>
                    </div>
                    <input type='hidden' name='pid' value='<?php echo $this->object->getPid(); ?>'/>
                    <input type='submit' value='Save' name='req'/>
                    <input type='hidden' name='req' value='es-submit'/>
                </form>
            </div>
        <?php
        }
        else if($this->req == "ut-table"){
            $title ="Edit Users:";
            $this->renderHeadingInfo("editUsersHeading",$title); 

        ?>
        <div class='col-12 d-flex justify-content-center'>
            <table class='table table-bordered mt-4' style='width:99%;'>
                <thead>
                    <?php
                    if(count($this->object) > 0){
                    ?>
                    <tr class='text-center'>
                        <th scope = 'col' style='color: #7F4444;'>First Name</th>
                        <th scope = 'col' style='color: #7F4444;'>Last Name</th>
                        <th scope = 'col' style='color: #7F4444;'>Email Address</th>
                        <th scope = 'col' style='color: #7F4444;'>UAPID</th>
                        <th scope = 'col' style='color: #7F4444;'>Role </th>
                        <th scope = 'col' style='color: #7F4444;'>Edit </th>
                        <th scope = 'col' style='color: #7F4444;'> Delete </th>
                    </tr>
                    <?php
                    }
                    else{
                    ?>
                    <div style='margin-left:3px;'>No users available</div>
                    <?php
                    }
                    ?>
                </thead>
                <tbody>
                    <?php
                        if(is_array($this->object)){
                            for($i=0;$i<count($this->object);$i++){
                                $user = $this->object[$i];
                                echo "<tr class='text-center'>";
                                echo "<td>".$user->firstName."</td>
                                      <td>".$user->lastName."</td>
                                      <td>".$user->emailAddress."</td>
                                      <td>".$user->UAPID."</td>
                                      <td>".$user->role."</td>
                                      <td><a class='nav-link text-primary' href='?req=ut-edit&pid=$user->pid'>Edit User</a></td>
                                      <td><a class='nav-link text-primary' href='?req=ut-delete&pid=$user->pid'>Delete User</a></td>
                                      ";
                                echo "</tr>";
                            }

                        }
                        else{
                            echo 'not array';
                        }
                    ?>
                </tbody>
            </table>
        </div>
        <?php
        }
        if($this->req  == "pf-insert_person" || $this->req == "pf-edit_person"){
            if($this->req == "pf-edit_person"){
                $title ="Edit Member:";
                $this->renderHeadingInfo("editMemberHeading",$title); 

                $person = $this->object;
                $firstName = $person->getfirstName();
                $lastName = $person->getlastName();
                $birthDate = $person->getbirthDate();
                $gender = $person->getGender();
                $email = $person->getEmail();
                $phoneNumber = $person->getphoneNumber();
                $address = $person->getAddress();
                $image = $person->getImage();
                $biography = $person->getBiography();
                $deathDate = $person->getDeathDate();
                $pid = $person->getPid();

            }
            else{
                $title ="Add Member:";
                $this->renderHeadingInfo("addMemberHeading",$title); 

                $person = new stdClass();
                $firstName = $lastName = $image = $birthDate = $gender = $email = $phoneNumber = $address = $image = $biography = $deathDate = $createdDate = $pid = '';
            }
        ?> 

            <div class='container-fluid'>
                <div class="row pl-3 pt-0">
                    <div class='col-md-3'>
                        <form enctype='multipart/form-data' action='index.php' method="post" id='insert_first_person'>
                            <div class='row'>
                                <div class='col-sm-6'>
                                    <label for='firstName' class='form-label'>First Name:<span style='color:#7F4444;font-weight:bold;'>*</span></label>
                                    <input type='text' class='form-control' id='firstName' name='firstName' value="<?php echo $firstName; ?>" required />
                                </div> 
                                <div class='col-sm-6'> 
                                    <label for='lastName' class='form-label'>Last Name:<span style='color:#7F4444;font-weight:bold;'>*</span></label>
                                    <input type='text' class='form-control' id='lastName' name='lastName' value='<?php echo $lastName; ?>' required />
                                </div>
                            </div>

                            <div class='row'>
                                <div class='col-sm-6'>
                                    <label for="birthDate" class='form-label'>Birth Date:</label>
                                    <input type="date" id="birthDate" class='form-control' name="birthDate" value='<?php echo $birthDate;?>'>
                                </div>
                                <div class='col-sm-6'>
                                    <label for="deathDate" class='form-label'>Death Date:</label>
                                    <input type="date" class='form-control' id="deathDate" name="deathDate" value='<?php echo $deathDate;?>'>
                                </div> 
                            </div>

                            <div class="form-group">
                                <label for='gender' class='form-label d-block'>Gender:<span style='color:#7F4444;font-weight:bold;'>*</span></label>
                                <div class='form-check form-check-inline'>
                                    <input type="radio" id="male" class='form-check-input' name="gender" value="M" <?php if (isset($gender) && $gender == 'M') echo 'checked'; ?> required>
                                    <label for="male" class='form-check-label'>Male</label>
                                </div>
                                <div class='form-check form-check-inline'>
                                    <input type="radio" id="female" class='form-check-input' name="gender" value="F" <?php if (isset($gender) && $gender == 'F') echo 'checked'; ?> required>
                                    <label for="female" class='form-check-label'>Female</label>
                                </div>
                            </div>

                            <div class='row mt-3'>
                                <div class='col-sm-6'>
                                    <label for='email' class='form-label'>Email:</label>
                                    <input type='email' class='form-control' name='email' value='<?php echo $email; ?>'/>
                                </div>
                                <div class='col-sm-6'>
                                    <label for='phoneNumber' class='form-label'>Phone Number:</label>
                                    <input type='text' class='form-control' name='phoneNumber' value='<?php echo $phoneNumber; ?>'/>
                                </div>
                            </div>

                            <div class='form-group mt-3'>
                                <label for='address' class='form-label'>Address:</label>
                                <input type='text' class='form-control' name='address' value='<?php echo $address; ?>'/>
                            </div>

                            <div class="form-group mt-3">
                                <label for="image">Select an Image:</label>
                                <input type="file" name="image" id='imageInput' class='form-control'>
                                <div id='preview'></div>
                            </div>

                            <script>
                                const pid = "<?php echo $pid; ?>";
                                console.log(pid);
                                if(pid !== ""){
                                    document.getElementById('preview').innerHTML = "<img src='img/people/ph_50/" + pid + ".png'>";
                                }

                                document.getElementById('imageInput').addEventListener('change', function(event) {
                                    if(pid !== ""){
                                        document.getElementById('preview').innerHTML = "Successfully Uploaded";
                                    }
                                });
                            </script>

                            <div class='form-group mt-3'>
                                <label for='bio1' class='form-label'>Biography</label>
                                <textarea class='form-control' rows="4" cols="30" name='biography'><?php echo $biography; ?></textarea>                
                            </div>

                            <div class='form-group'>
                                <?php $this->renderTemplate("addMemberNote");?>
                            </div>

                            <?php if(isset($_SESSION['role'])): ?>
                                <input type='hidden' name='role' value= "<?php echo $_SESSION['role']; ?>" />
                            <?php endif; ?>

                            <input type='hidden' name='pid' value='<?php echo $pid;?>'>

                            <?php if(!empty(trim($pid))): ?>
                                
                                <input type='submit' id='submit' value="Save">
                                <input type='hidden' value='sf_update_person_details' name='req'/>
                            <?php else: ?>
                                <input type='submit' value="Save" id='submit'/>
                                <input type='hidden' value='sf_insert_person_details' name='req'>
                            <?php endif; ?>
                        </form>
                    </div>
                    <div class='col-md-6 p-3'>
                        <ul id='hints' style='list-style-type:none;' >

                        </ul>
                    </div>
                </div>
            </div>
            <script>
                $(document).ready(function(){
                    $(document).on("input","#firstName, #lastName",function(event){
                        let sfValue = $(this).val();
                        let selector = this.id;
                        if(sfValue.length > 0){
                            $.ajax({
                                url:"ajax.php",
                                method: "POST",
                                data: {
                                    sfValue:sfValue,
                                    selector:selector
                                },
                                success:function(persons){
                                    persons = JSON.parse(persons);
                                    let hints = document.getElementById('hints');
                                    hints.innerHTML = '';
                                    for(i=0;i<persons.length;i++){
                                        person = persons[i]
                                        console.log(person);
                                        let hint = document.createElement('li');
                                        hint.id='hint';
                                        hint.textContent = person.firstName + " " +person.lastName;
                                        hints.appendChild(hint);
                                    }
                                }   

                            })
                        }
                    });
                });
            </script>
        <?php
        }
        else if($this->request['req'] == 'pf-display_persons'){
            
            $role = $this->object->getRole();
            $UAPID = $this->object->getUAPID();
            $activeUser = array('role' => $role, 'UAPID' => $UAPID);
            $relationships  = $this->DatabaseManager->getPersonsAndRelationships();
            /*echo '<pre>';
            print_r($relationships);
            echo "</pre>";*/
            $title ="Edit Members:";
            $this->renderHeadingInfo("editMembersHeading",$title); 
        ?>
        <div>
        <div class="container-fluid">   
            <form enctype="multipart/form-data" class="pt-0 pl-3 form-inline">
                <div class="form-group">
                    <!-- Label + Select -->
                        <label for="category" class="form-label me-2">Search By:</label>
                        <select id="category" name="category" class="form-control me-2">
                            <option value="firstName">First Name</option>
                            <option value="lastName">Last Name</option>
                        </select>
                        <input type="text" id="search" name="search" class="form-control" placeholder="Enter member name" >
                        <div id="searchForm"></div>
                </div>
            </form>
            <div id='table' style='width:70%;margin:auto;'></div>
            </div>
        </div>
            <script>
                
                document.getElementById('category').addEventListener('change',function(event){
                        document.getElementById('search').style.display='block';
                        document.getElementById('searchLabel').style.display='block';
                        document.getElementById('searchForm').style.display='none';
                });
                $(document).ready(function(){
                    $(document).on("keyup change", "#search", function(event){
                        var sValue = $(this).val(); 
                        let sId = $(this).attr("id");    
                        var relationships = <?php echo json_encode($relationships); ?>;
                        var activeUser = <?php echo json_encode($activeUser); ?>;
                        var category = document.querySelector('#category').value;   
                        var trimmedSValue=sValue.trim();

                        console.log(sId)
                        if(sValue.length > 0 || trimmedSValue== "-"){
                            if(trimmedSValue == "-"){
                                sValue ="";
                            }
                            $.ajax({
                                url:"ajax.php",
                                method: "POST",
                                data: {
                                    sValue: sValue,
                                    req: sId,
                                    category:category,
                                    activeUser: activeUser
                                },
                                success: function(persons){
                                    console.log(persons);
                                    persons = JSON.parse(persons);
                                    html="";
                                    if(persons.length > 0){
                                        html = "<table class='table table-bordered table-hover ms-0'><thead><tr class='text-center'>";
                                        html += "<th style='color:#7F4444;'>UAPID:</th><th style='color:#7F4444;'> FirstName </th> <th style='color:#7F4444;'>Last Name</th> <th style='color:#7F4444;'>Birth Date</th><th style='color:#7F4444;'>Gender</th><th style='color:#7F4444;'>Image</th><th style='color:#7F4444;'>Action</th></tr></thead>";
                                    
                                        for(i=0;i<persons.length;i++){
                                            person = persons[i];
                                            image = person.pid+".png";

                                            console.log(person.pid);

                                            html +="<tbody><tr>";
                                            
                                            var imagePath = "<?php echo $personImagePath->getValue(); ?>"; // PHP executes on server
                                            html += "<td class='text-center'>"+person.pid+"</td><td class='text-center'>" + person.firstName + "</td>" +
                                                    "<td class='text-center'>" + person.lastName + "</td>" +
                                                    "<td class='text-center'>" + person.birthDate + "</td>" +
                                                    "<td class='text-center'>" + person.gender + "</td>" +
                                                    "<td class='text-center d-flex justify-content-center align-items-center'>" +
                                                    "<img src='" + imagePath + person.pid  + "' onerror=\"this.onerror=null; this.src='<?php echo  $adminErrorImagePath->getValue(); ?>';\" /></td>";
                                            relationshipExists = false;
                                            for(j=0;j<relationships.length;j++){
                                                relationship = relationships[j];
                                                if(person.pid == relationship.pid){
                                                    html+="<td class='text-center'><a href='?req=pf-edit_relationship&pid="+person.pid+"' id='relative' class='me-3'><img src='<?php echo $add->getValue();?>' alt=''/></a>";
                                                    relationshipExists = true;
                                                    break;
                                                }
                                            }
                                            if(!relationshipExists){
                                                html+="<td class='text-center'><a href='?req=pf-insert_relationship&pid="+person.pid+"' id='relative' class='me-3'/><img src='<?php echo $add->getValue();?>' alt=''/></a>";
                                            }


                                            html += "<a href='?req=pf-edit_person&pid="+person.pid+"' id='edit' class='me-3'><img src='<?php echo $edit->getValue();?>' alt=''/></a><a href='?req=pf-delete_person&pid="+person.pid+"' id='delete'><img src='<?php echo $delete->getValue(); ?>' alt=''/></a></td>";
                                            
                                            
                                            html+="</tr></tbody>";
                                            console.log(html);
                                        }
                                    }
                                    document.getElementById('table').innerHTML=html;
                                   
                                },
                                error: function(response){
                                    console.log("You have error: " +response);
                                }
                            })
                        }
                        else if(sValue.length == 0){
                            html = "";
                            document.getElementById('table').innerHTML=html;
                        }
                    })

                })
            </script>

        </div>
        <?php
        }
        else if($this->request['req'] == "pf-insert_relationship" || $this->request['req'] == "pf-edit_relationship"){
            if($this->request['req'] == "pf-edit_relationship"){
                $person = $this->object[0];
                $name = $person->firstName." ".$person->lastName;
                $title ="Edit Relationship:";
                $this->renderHeadingInfo("editMemberRelationship",$title); 
            }
            else{
                $person = $this->DatabaseManager->getPersonWithoutRelationship($this->request['pid']);
                $name = $person->firstName ." ".$person->lastName;
            }
            $relationshipList = explode(",",Config::getFormOptions());
        ?>
            <div class='container-fluid'>
                <form enctype='multipart/form-data' action ='index.php' id='insert_relation_details'>
                    Person: <?php echo $name; ?>
                    <div id='form-group'>
                        <input type='hidden' class='form-control' name='pid' value="<?php echo $this->request['pid']; ?>"/>
                    </div>
                    <div id='form-group'>
                    <label for="father" class='form-label'>Father:</label>
                        <input list="fatherList" id="father" class='form-control w-25' >
                        <select name="fpid" id='fathers' class='mb-3'>
                            <!-- <option value='john doe'>John Doe </option> --> 
                             <?php
                                if($this->request['req'] == "pf-edit_relationship"){
                                    for($i=0;$i<count($this->object);$i++){
                                        $individual = $this->object[$i];
                                        $identifier = $individual->type;
                                        if($identifier == "fpid"){
                                            echo "<option value = '".$individual->pid."'>".$individual->firstName."</option>";
                                        }
                                        else{
                                            echo "";
                                        }
                                    }
                                }
                             ?>
                        </select>
                    </div>
                    <div id='form-group'>
                        
                        <label for='mother' class='form-label'>Mother:</label>
                        <input list="motherList" id="mother" class='form-control w-25'>
                        <select name="mid" id='mothers' class='mb-3'>
                            <!-- <option value='john doe'>John Doe </option> --> 
                            <?php
                            if($this->request['req'] == "pf-edit_relationship"){
                                for($i=0;$i<count($this->object);$i++){
                                    $individual = $this->object[$i];
                                    $identifier = $individual->type;
                                    if($identifier == "mid"){
                                        echo "<option value = '".$individual->pid."'>".$individual->firstName."</option>";
                                    }
                                    else{
                                        echo "";
                                    }
                                }
                            }
                             ?>
                        </select>
                    </div>
                    <div id='form-group'>
                        <label for='partner' class='form-label'>Partner:</label>
                        <input list="partnerList" id="partner" class= "form-control w-25">
                        <select name="psid" id='partners' class='mb-3'>
                            <!-- <option value='john doe'>John Doe </option> --> 
                            <?php
                            if($this->request['req'] == "pf-edit_relationship"){
                                for($i=0;$i<count($this->object);$i++){
                                    $individual = $this->object[$i];
                                    $identifier = $individual->type;
                                    if($identifier == "psid"){
                                        echo "<option value = '".$individual->pid."'>".$individual->firstName."</option>";
                                    }
                                    else{
                                        echo "";
                                    }
                                }
                            }
                             ?>

                        </select>
                    </div>
                    <script>
                        var relationshipList = <?php echo json_encode($relationshipList); ?>;
                        console.log(relationshipList)
                            $(document).ready(function(){
                                $("input").on("keyup",function(){
                                    let fValue = $(this).val()
                                    console.log("fValue is "+fValue);
                                    let elementId = $(this).attr("id")
                                    console.log(elementId);
                                    if(fValue.length == 0){
                                        parent_element = document.getElementById(elementId+"s");
                                        parent_element.innerHTML = '';  
                                        var option = document.createElement('option');  
                                        option.value = "";
                                        option.textContent = "";
                                        parent_element.appendChild(option);  

                                    }
                                    if(fValue.length >0){
                                        $.ajax({
                                            url:"ajax.php",
                                            method:"POST",
                                            data: {
                                                req: elementId,
                                                relationshipList: relationshipList,
                                                letter: fValue
                                            },
                                           
                                            success: function(response){
                                                console.log(response);
                                                response = JSON.parse(response);
                                                parent_element = document.getElementById(elementId+"s");
                                                parent_element.innerHTML = '';  
                                                for (var i = 0; i < response.length; i++) {
                                                    var element = response[i];
                                                    console.log(element);
                                                    
                                                    var option = document.createElement('option');  

                                                    option.value = element['pid'];  
                                                    for(let key in element){
                                                        console.log(key);
                                                        if(key != "pid" && key != "birthDate"){
                                                            option.textContent += " "+element[key];
                                                        }
                                                        if(key == "birthDate"){
                                                            option.textContent += " ("+element[key]+')';
                                                        }
                                                    }
                                                    parent_element.appendChild(option);  
                                                }
                                            }
                                        })                                    
                                    }
                                })
                            })
                            function setFather(father){
                                console.log('settingFather');
                                document.getElementById("father").value=father;
                                document.getElementById('fathers').style.display='none';
                            }
                        </script>
                    <?php
                        if($this->request['req'] == "pf-insert_relationship"){

                    ?>
                            <input type='submit' value='Insert Relationship' id='submit'>
                            <input type='hidden' name='req' value='sf_insert_person_relationship'/>
                    <?php
                        }
                        else{
                    ?>
                            <input type='submit' value='Edit Relationship' id='submit'>
                            <input type='hidden' name='req' value='sf_edit_person_relationship'/>
                    <?php
                        }
                    ?>
                </form>
            </div>
        <?php
        }
        else if($this->req == "pf-delete_person"){
            
            $title ="Delete Person:";
            $this->renderHeadingInfo("deleteMember",$title);             

            $person = $this->object;
            
            $relationsMatch = $this->DatabaseManager->selectRelationsWithPid($person['pid']);
            ?>
            <div class='container-fluid pl-3 pt-0'>
                <?php
                echo "<table cellpadding='5' style='text-align:center;'>";
                echo "<caption style='caption-side: top; font-weight:bold;color:#7F4444;text-align:center;borde:1px solid black;'>Dependencies:</caption>";
                echo "<tr style='border:1px solid black;'><th style='border:1px solid black;text-align:center;color:#7F4444;'>Relation Type:</th><th style='border:1px solid black;text-align:center;color:#7F4444;'>Person:</th>";
                
                foreach($relationsMatch as $relation){
                    $relation_person = $this->DatabaseManager->getPerson("pid",$relation['pid']);
                    
                    echo "<tr style='border:1px solid black;'><td style='border:1px solid black;'>".$relation['matched_field']." to</td><td style='border:1px solid black;'>".$relation_person['firstName']." ".$relation_person['lastName']."</td></tr>";
                }
                
                echo "</table>";
                $pid = $person['pid'];
                $name = $person['firstName']." ".$person['lastName'];
                echo "<br/><b style='color:#7F4444;'>".$name."'s</b> information:";
                echo '<br/>';
                foreach($person as $key=>$value){
                    if($key != "pid"){
                        echo ucfirst($key). " - " . $value."<br/>";
                    }
                }
                echo "<br/>";
                echo "<a href='?req=sf-delete_person&pid=$pid' style='color:#7F4444;'>By clicking this link, ".$name."'s information will be permanently deleted</a><br/><br/>";
                ?>
            </div>
            <?php
        }

    }
    public function renderHeadingInfo(string $path, string $title):void{
    ?>
        <div class='container-fluid pt-2'>
                <div class='row pl-3 pt-4 pb-0'>
                    <div class='col-md-6'>
                        <h3 style = 'display:inline-block;color:#7F4444;' class='font-weight-bold'>
                            <?php echo  $title; ?>
                        </h3>
                    </div>
                    <div class='col-md-6'></div>
                    <div class='col-md-6 pb-3' style='font-size:15px;'>
                        <?php $this->renderTemplate($path); ?>              
                    </div>
                </div>
            </div>
    <?php
    }
    public function setMenu() {
        $this->renderTemplate($this->resolveMenuPath());
    }
    private function resolveMenuPath():String{
        $navLinksMenuUser = explode(",",Config::getNavLinksMenuUser());
        if(is_object($this->object)){
            $file = $this->object->getRole();
        }
        else{
            $file = "login";
        }
        $file = $file."Menu";
        return $file;
    }
}