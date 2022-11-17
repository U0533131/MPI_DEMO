<?php
	//check db檔案是否存在 不存在則新增db
	if(!is_file('db/MPI.sqlite3')){
		file_put_contents('db/MPI.sqlite3', null);
	}
	
    //連接db
	$conn = new PDO('sqlite:db/MPI.sqlite3');
	
    //檔案不存在則建立TABLE
	$query = "CREATE TABLE IF NOT EXISTS users (
        C_Id INTEGER PRIMARY KEY AUTOINCREMENT, 
        C_Name VARCHAR(20) NOT NULL, 
        Gender VARCHAR(6) NOT NULL, /*varchar最多存入6個字元長度 如果用chard空的會填空*/ 
        Addr VARCHAR(50), 
        Phone VARCHAR(10) NOT NULL,  
        Birthday DATE NOT NULL
    )";

	//執行query
	$conn->exec($query);



    /*POST*/
    if(ISSET($_POST['submit'])){

        $Phone = $_POST["Phone"];

        //確認電話是否有重複 有則返回error
        $sql = $conn->prepare("SELECT count(*) FROM users WHERE Phone = ?");
        $sql->execute([$Phone]);
        $result = $sql->fetchColumn(); //rowCount() 
        //檢查有沒有重複的資料 print $result."-".$Phone;
        if($result > 0){
            $error = "<span style=color:red>電話已存在，請重新輸入。</span>";
        }
        
        $C_Name = $_POST['C_Name'];
        $Gender = $_POST["Gender"];
        $Addr = $_POST["Addr"];
        $Birthday = $_POST["Birthday"];
        
        if(empty($error)){
        //變數前加上:表示為placeholder
		$query=$conn->prepare("INSERT INTO users(C_Name, Gender, Addr, Phone, Birthday) 
        VALUES(:C_Name, :Gender, :Addr, :Phone, :Birthday)");
        $query->execute([
            'C_Name' => $C_Name,
            'Gender' => $Gender,
            'Addr' => $Addr,
            'Phone' => $Phone,
            'Birthday' => $Birthday
        ]);

        $msg = "<span style='color:blue'>已成功建立！</span>";
        }

/*
        //prepare()準備要執行的SQL語句
        $stmt=$conn->prepare($query);
        $stmt->bindParam(':C_Name',$C_Name ); //綁定參數到指定的變數名
        $stmt->bindParam(':Gender',$Gender);
        $stmt->bindParam(':Addr',$Addr);
        $stmt->bindParam(':Phone',$Phone);
        $stmt->bindParam(':Birthday',$Birthday);

        if($stmt->execute()){ //執行綁定參數的預處理語句
        }
*/

	}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>DEMO</title>
    <style>
        body{
            background-color: #dee9ff;
        }

        .customers_form{
            padding-top: 100px ;
        }

        .customers_form form{
            background-color: #fff;
            max-width: 300px;
            margin: auto;
            padding: 80px 70px 70px 100px;
            border-top-left-radius: 30px;
            border-top-right-radius: 30px;
            box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.075);
        }

        .customers_form h1 {
        color: #5791ff;
        text-align: left;
        font-size: 30px;
        font-weight: bold;
        margin-bottom: 10px;
        }

        .customers_form p {
        color: #8D8D8D;
        text-align: left;
        font-size: 16px;
        font-weight: bold;
        line-height: 20px;
        margin-bottom: 30px;
        }

        .customers_form .item{
            border-radius: 10px;
            margin-bottom: 25px;
            padding: 10px 25px;
        }

        .customers_form .create_customers{
            border-radius: 30px;
            padding: 10px 20px;
            font-size: 18px;
            font-weight: bold;
            background-color: #5791ff;
            border: none;
            color: white;
            margin-top: 20px;
            margin-left: 60px;
        }
    </style>
</head>

<body>
    <div class="customers_form">  
        <form name="myForm" method="post" action="index.php">
            <!--顯示清單-->
            <div>
                <?php
                try{
                $db = new PDO('sqlite:db/MPI.sqlite3'); //連接並開啟db
                print"<table border=1>";
                print"<tr><td>ID</td> <td>Name</td> 
                <td>Gender</td> <td>Address</td> 
                <td>Phone</td> <td>Birthday</td> </tr>";

                $result=$db->query('SELECT * FROM users');
/*
foreach讀取陣列資料 (1)$array as $value (2)$array as $key => $value
另個寫法: 
$query='SELECT...';
foreach($result->query($query) as $row){
    print $row['...'];
}
*/
                foreach($result as $row){ 
                    print "<tr><td>".$row['C_Id']."</td>";
                    print "<td>".$row['C_Name']."</td>";
                    print "<td>".$row['Gender']."</td>";
                    print "<td>".$row['Addr']."</td>";
                    print "<td>".$row['Phone']."</td>";                    
                    print "<td>".$row['Birthday']."</td>";
                    }
                }catch(PDOException $e){
                    echo $e->getMessage();
                }
                print"</table>";
                ?>
            </div>
            <!--輸入表單-->
            <h1>新增客戶資料</h1>
            <?php if(isset($error)){echo $error;}?>
            <?php if(isset($msg)){echo $msg;}?>
            <p>請按照格式輸入</p>
            <div class="form_group"><label>姓名：</label>
                <input name="C_Name" type="text" class="form-control item"  placeholder="請輸入Name" autocomplete="off" required="required">
            </div>
            <div class="form_group"><label>性別：</label>
                <select name="Gender"class="form-control item"  placeholder="選擇Gender" required="required">
                <option value="Male">男(Male)</option>
                <option value="Female">女(Female)</option>
                </select>
            </div>
            <div class="form_group"><label>地址：</label>
                <input name="Addr" type="text" class="form-control item" placeholder="請輸入Address" autocomplete="off" required="required">
            </div>
            <div class="form_group"><label>手機：</label>
                <input name="Phone" type="text" class="form-control item"  placeholder="09XX-XXX-XXX" autocomplete="off" required="required">
            </div>
            <div class="form_group"><label>生日：</label>
                <input name="Birthday" type="date" class="form-control item" required="required" >
            </div>
            <div class="form_group">
            <button class="create_customers" name="submit"><span class="glyphicon glyphicon-save"></span> submit</button>
            </div>
        </form>
    </div>
</body>
</html>