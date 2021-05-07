<?php
session_start();
if(isset($_POST["mail"])){
    if(isset($_SESSION["name"],$_SESSION["height"],
        $_SESSION["weight"],$_SESSION["bmi"])){
            date_default_timezone_set('Asia/Tokyo');
            $reg_date= date("Y年m月d日 H:i:s");
            $mail=$_POST["mail"];
            $name=$_SESSION["name"];
            $height=$_SESSION["height"];
            $weight=$_SESSION["weight"];
            $bmi=$_SESSION["bmi"];
            $meter=$height/100;
            $bmi = $weight / ($meter * $meter); //bmiは、体重÷（身長 × 身長）
            $bmi = round($bmi,1); //小数点第一位まで四捨五入する
            $rw	= ($meter * $meter)*22;//適正体重の計算（身長 × 身長）X22
            $rw	=round($rw,2);//小数点第二位まで四捨五入する
            $r_weight=$weight-$rw; //適正体重との差

            }
    }
?>

<!DOCTYPE>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>BMI比較計算</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
    div#wrap {
        width: 80%;
        margin: auto;
        padding: 30px 5%;
    }

    table {
        width: 84%;
        margin: 20px auto;
    }

    table tr th {
        width: auto;
        padding: 3px 2px;

    }

    table tr td {
        width: auto;
        padding: 3px 2px;
    }

    section {
        margin: 30px auto;
    }
    </style>
</head>

<body>
    <div id="wrap">
        <header id="siteheader">
            <h1 class="wrap">あなたの肥満度計ります！ BMI比較表</h1>
        </header>
        <section>

            <?php
			//最後に登録した際のデータ呼び出し
			if(!empty($mail))	{
				$db_id="root";
				$db_pass="";
				$db_host="localhost";
				$dbname="php_2021";

				$pdo = new PDO("mysql:host={$db_host};
                                    dbname={$dbname}","{$db_id}","{$db_pass}",
                                    array(PDO::MYSQL_ATTR_INIT_COMMAND =>
                                        "SET CHARACTER SET `utf8`" ) );

                                    if($pdo){
                                        //echo "接続に成功しました。";
                                    }else{ echo "PDO接続に失敗しました。";}

		//SQL実行準備 prepare()
		$sql=$pdo ->prepare("SELECT * FROM bmi_data WHERE id= '$mail' ORDER by `no` DESC");
		$sql->execute();
		$data= $sql -> fetch();


		      //前回記録と最新記録の比較表作成
			echo"
                <table border='1'>";
			?>
            <caption>
                <h2><?php if(!empty($name)){ echo "{$name} 様　のBMIデータ"; } ?></h2>
            </caption>
            <?php
			if(!empty($data)){
				//比較する同一IDがあった場合
				$current_gap=$data["weight"] -$weight;
			echo"
                <tr><th>比較項目</th>
                <th><h4>前回データ</h4></th>
                <th><h4>最新データ</h4></th>
                <th><h4>前回との偏差</h4></th></tr>
				<tr><th>アカウントID </th><td colspan='3'> {$data["id"]}</td></tr>
				<tr><th>身長</th><td> {$data["height"]}cm</td><td> {$height}cm</td>
                        <td> <h4>前回との体重差：{$current_gap}kg</h4></td></tr>
				<tr><th>体重</th><td>{$data["weight"]}kg</td><td> {$weight}kg</td>
                        <td><h4>標準体重：{$rw}kg</h4></td></tr>
				<tr><th>BMI</th><td>{$data["bmi"]}</td><td> {$bmi}</td>
                        <td>標準体重まで：{$r_weight}kg</td></tr>
				<tr><th>登録日</th><td>{$data["reg_date"]}</td><td> {$reg_date}</td></tr>
                </table> ";

			}else{
				//初回メールアドレス登録時(比較IDが無い時)
				echo"
				<tr><th>アカウントID </th><td colspan='2'> {$mail}</td></tr>
				<tr><th>身長</th><td>{$height}cm</td></tr>
				<tr><th>体重</th><td>{$weight}kg</td>
                        <td><h4>標準体重：{$rw}kg</h4></td></tr>
				<tr><th>BMI</th><td> {$bmi}</td>
                        <td>標準体重まで：{$r_weight}kg</td></tr>
				<tr><th>登録日</th> <td>{$reg_date}</td></tr>
                </table> ";

			}

			}//empty(mail)終了

			//最新記録のDB登録
			$db_id="root";
			$db_pass="";
			$db_host="localhost";
			$dbname="php_2021";

			$pdo2 = new PDO("mysql:host={$db_host}; dbname={$dbname}","{$db_id}","{$db_pass}",
			array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET CHARACTER SET `utf8`" ) );

			if($pdo2){
			    //echo "接続に成功しました。";
			}else{ echo "PDO接続に失敗しました。";}


			$sql2="INSERT INTO bmi_data(
		id , name , height, weight, bmi , reg_date
		)values(?,?,?,?,?,?)";
			//上記$SQLのインサート文のデーターにフォームの内容を挿入する

			//noは自動で加算するのでここには入れないでおく。　valueは$sentでデーターを代入後入れるのでOK
			$sent=$pdo2 ->prepare($sql2);
			$sent->bindParam("id",$mail);
			$sent->bindParam("name",$name);
			$sent->bindParam("height",$height);
			$sent->bindParam("weight",$weight);
			$sent->bindParam("bmi",$bmi);
			$sent->bindParam("reg_date",$reg_date);
			$ck = $sent->execute(array( $mail ,$name ,
			    $height , $weight , $bmi, $reg_date));

			if(!$ck){echo "エラー";}
			else{ echo"登録完了<br>
            <input type='button' value='　戻る　'
						 OnClick='location.href=\"index.php\"'>";}
			?>
            <br>

        </section>
        <footer></footer>
    </div>
</body>

</html>