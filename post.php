<?php
    require_once 'index.php'; 

	if(ISSET($_POST['submit'])){
        $C_Name = $_POST['C_Name'];
        $Gender = $_POST["Gender"];
        $Addr = $_POST["Addr"];
        $Phone = $_POST["Phone"];
        $Birthday = $_POST["Birthday"];

        //變數前加上:表示為placeholder
		$query="INSERT INTO user(C_Name, Gender, Addr, Phone, Birthday) 
        VALUES(:C_Name, :Gender, :Addr, :Phone, :Birthday)";

        $stmt=$conn->prepare($query);
        $stmt->bindParam(':C_Name',$C_Name );
        $stmt->bindParam(':Gender',$Gender);
        $stmt->bindParam(':Addr',$Addr);
        $stmt->bindParam(':Phone',$Phone);
        $stmt->bindParam(':Birthday',$Birthday);
        
        if($stmt->execute()){ //值綁定參數
        header("location:index.php");
        }

	}

?> 