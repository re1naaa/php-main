<?php
$name= array(
    "student1"=>"reina",
    "student2"=>"reinaa",
    "student3"=>"reinaaa",
    "student4"=>"reinaaaa",
);

foreach($name as $subject=> $studentName){
    echo"subject:" . $subject . ",name:" , $studentName;
    echo "<br>";
}
?>