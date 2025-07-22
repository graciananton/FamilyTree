<?php
class FamilyBuilderImpl implements Builder{
    private $html = "";
    private $cssFileName;
    private static $MALE = "man";
    private static $FEMALE = "woman";
    private $imagePath;
    public function __construct(){
        $this->DatabaseManager = new DatabaseManager("");
        $this->SettingService = new SettingServiceImpl($this->DatabaseManager);
    }
    private function build(string $pid, string $select, array $request): string{
    // 1) load person
    $this->imagePath = $this->SettingService->getSettingValueByName("homeTreeImagePath")['value'];


    $personData = $this->DatabaseManager->getPersonAndRelationship($pid);
    $person     = new Person($personData);

    // 2) write the person block
    $this->html .= $this->buildPerson($person);

    // 3) if they have a spouse, open “marriages” array
    if ($person->getPsid() !== "0") {
        $this->html .= ",\n    \"marriages\": [\n";

        // spouse object
        $spouseData = $this->DatabaseManager->getPersonAndRelationship($person->getPsid());
        $spouse     = new Person($spouseData);

        if(trim($spouse->getGender()) == "M"){
            $gender = "man";
        }
        else{
            $gender = "woman";
        }

        $this->html .= "      {\n";
        $this->html .= "        \"spouse\": {\n";
        $this->html .= "          \"name\": \"" . $spouse->getFirstName() . "\",\n";
        $this->html .= "          \"class\": \"" . $gender . "\",\n";
        $this->html .= "          \"extra\": {\n";
        $this->html .= "                \"pid\":". $spouse->getPid() . ",\n";
        $this->html .= "                \"fpid\":". $spouse->getFpid() ."\n";
        $this->html .= "          }\n";
        $this->html .= "        }";

        // fetch children
        $childrenList = $this->DatabaseManager->getChildrenList(
            $spouse->getPid(),
            $spouse->getGender()
        );

        if (count($childrenList) > 0) {
            $this->html .= ",\n        \"children\": [\n";

            // recurse for each child
            foreach ($childrenList as $i => $child) {
                $this->html .= "            {\n";
                // this recursive call will append the child’s own "name","class", and any nested marriages
                $this->build($child->pid, "", $request);
                $this->html .= "            \n}" 
                    . ($i < count($childrenList) - 1 ? ",\n" : "\n");
            }

            $this->html .= "        ]\n";  // close children array
        } else {
            $this->html .= "\n";  // no children, just newline
        }

        $this->html .= "      }\n";  // close spouse object
        $this->html .= "    ]";     // close marriages array
    }

    return $this->html;
    }

    private function buildPerson(object $person): string
    {
            if(trim($person->getGender()) == "M"){
                $gender = "man";
            }
            else{
                $gender = "woman";
            }
        return
            "           \"name\": \"" . $person->getFirstName() . "\",\n" .
            "           \"class\": \"" . $gender . "\",\n".
            "           \"extra\": { \n".
            "               \"pid\": ". $person->getPid() .",\n".
            "               \"fpid\":". $person->getFpid() ."\n".
            "           \n}";
    }

    public function generateTree(string $pid, string $select, array $request): string {
            $jsonPayload = "[{\n" . $this->build($pid, $select, $request) . "\n}]";
            $tree = '<div id="graph" style="transform:scale(1.2,1.2);"></div>';
            ?>
            <script>
                document.addEventListener("DOMContentLoaded", () => {
                    const treeData = <?= $jsonPayload ?>;
                    console.log(treeData);

                        dTree.init(treeData,
                                    {
                                        target: "#graph",
                                        debug: false,
                                        hideMarriageNodes: true,
                                        marriageNodeSize: 5,
                                        height: 800,
                                        width: 1600,
                                        callbacks: {
                                            /*nodeClick: function(name, extra) {
                                                alert(extra.pid)
                                            },*/
                                           /* nodeRightClick: function(name, extra) {
                                                alert('Right-click: ' + name);
                                            },*/
                                            textRenderer: function(name, extra, textClass) {
                                            if (extra && extra.pid) {
                                            
                                                string = "<p align='center' class='" + textClass + "' id='node'>"
                                                    + name
                                                    + "<br/>"
                                                    + "<a href='?pid="+extra.pid+"&pageType=page_profile&req=searchForm'/><img id='person_image' src='<?php echo $this->imagePath; ?>" + extra.pid + ".png' "
                                                    +     "onerror=\"this.onerror=null; this.src='admin/img/man.png';\" style='margin-bottom:9px;box-shadow: 5px 10px 18px #06635a;' "
                                                    + "/></a>"
                                                    + "</p>";
                                                return string;
                                            }
                                            return "";
                                            }
                                            /*marriageClick: function(extra, id) {
                                                alert('Clicked marriage node' + id);
                                            },
                                            marriageRightClick: function(extra, id) {
                                                alert('Right-clicked marriage node' + id);
                                            },*/
                                        }
                                    });

                })
            </script>
            <?php
            return $tree;
    }
}
?>

<script src='js/js.js'></script>
