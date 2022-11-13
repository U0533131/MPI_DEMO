<?php
	//check db檔案是否存在 不存在則新增db
	if(!is_file('db/MPI.sqlite3')){
		file_put_contents('db/MPI.sqlite3', null);
	}
	
    //連接db
	$conn = new PDO('sqlite:db/MPI.sqlite3');
	
    //檔案不存在則建立TABLE
	$query = "CREATE TABLE IF NOT EXISTS user (
        C_Id INTEGER PRIMARY KEY AUTOINCREMENT, 
        C_Name VARCHAR(20) NOT NULL, 
        Gender VARCHAR(1) NOT NULL, 
        Addr VARCHAR(50), 
        Phone VARCHAR(10) NOT NULL UNIQUE, /*不許允空值也不允許重複*/ 
        Birthday DATE NOT NULL
    )";

	//執行query
	$conn->exec($query);
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
        <form name="myForm" method="post" action="post.php">
            <!--顯示清單-->
            <div>
                <?php
                try{
                $db = new PDO('sqlite:db/MPI.sqlite3'); //連接並開啟db
                print"<table border=1>";
                print"<tr><td>ID</td> <td>Name</td> 
                <td>Gender</td> <td>Address</td> 
                <td>Phone</td> <td>Birthday</td> </tr>";

                $result=$db->query('SELECT * FROM user');

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
            <p>請按照格式輸入</p>
            <div class="form_group"><label>姓名：</label>
                <input name="C_Name" type="text" class="form-control item"  placeholder="請輸入Name" autocomplete="off" required="required">
            </div>
            <div class="form_group"><label>性別：</label>
                <select name="Gender"class="form-control item"  placeholder="選擇Gender" required="required">
                <option value="Male">男</option>
                <option value="Female">女</option>
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