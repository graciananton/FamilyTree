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
                                var dropdown = '<ul id="searchDropdown" style="list-style: none; position:absolute; padding: 0; margin-top: 10px;width:100%;">';

                                for (var i = 0; i < persons.length; i++) {
                                    var person = persons[i];
                                    var name = person['firstName'] + " " + person['lastName'];

                                    dropdown += '<li id="option" style="border:1px solid #06635a;">';
                                    dropdown += '<form method="POST" action="index.php">';

                                    dropdown += "<input type='hidden' name='req' value='search'/>";
                                    dropdown += "<input type='hidden' name='select' value=''/>";
                                    dropdown += '<input type="hidden" name="pid" value="' + person['pid'] + '"/>';
                                    dropdown += '<input type="hidden" name="personName" value="' + name + '"/>';

                                    dropdown += '<button type="submit" id="searchOption" style="text-align: left; border: none; padding: 8px;">';
                                    dropdown += '<img src="<?php echo $homeDropdownImagePath->getValue();?>' + person['pid'] + '.png" onerror="this.onerror=null; this.src=\'admin/img/man.png\';" style="height: 20px; margin-right: 8px;" />' + name;
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