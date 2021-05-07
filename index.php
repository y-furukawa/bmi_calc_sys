<?php
session_start();
	if(isset($_POST["height"])){//$_POST["height"]が存在していれば
		$height = htmlspecialchars($_POST["height"]);
	}else{//$_POST["height"]が存在していなかったら
		$height = NULL;//空箱を作ることでエラーを回避
	}

	//上記のweightバージョン
	if(isset($_POST["weight"])){
		$weight = htmlspecialchars($_POST["weight"]);
	}else{
		$weight = NULL;
	}
	//print $height; //クロスサイドスクリプティング確認用

	//身長と体重の値が0以外の時
	if($weight != 0 && $height != 0){
		$meter = $height / 100;//高さをメートルに直す
		$bmi = $weight / ($meter * $meter); //bmiは、体重÷（身長 × 身長）
		$bmi = round($bmi,1); //小数点第一位まで四捨五入する
		$rw	= ($meter * $meter)*22;//適正体重の計算（身長 × 身長）X22
		$rw	=round($rw,2);//小数点第二位まで四捨五入する
	}else $bmi = ""; //条件外でも$rwを存在させる

?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>BMI計測器</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <header id="siteheader">
        <h1 class="wrap">あなたの肥満度計ります！ BMI計測器</h1>
    </header>

    <main>
        <section>
            <h1>測定</h1>
            <p>身長と体重からBMI（肥満度）を算出します</p>
            <form method="post" action="#result">
                <table id="input_area">
                    <tr>
                        <th><label for="name">お名前</label></th>
                        <td><input type="text" name="name" id="name" value="" required></td>
                    </tr>
                    <tr>
                        <th><label for="height">身長</label></th>
                        <td><input type="text" name="height" id="height" placeholder="cm" value="" requireed></td>
                    </tr>

                    <tr>
                        <th><label for="weight">体重</label></th>
                        <td><input type="text" name="weight" id="weight" placeholder="kg" value="" required></td>
                    </tr>
                    <tr>
                        <td colspan="2"><input type="submit" value="BMIをチェック！"></td>
                    </tr>
                </table>
            </form>
            <?php
			if($bmi<18.5){
				$i=0;
			}else if($bmi>=18.5 && $bmi<25){
				$i=1;
			}else if($bmi>=25 && $bmi<30){
				$i=2;
			}else if($bmi>=30 && $bmi<35){
				$i=3;
			}else if($bmi>=35 && $bmi<40){
				$i=4;
			}else if($bmi>=40){
				$i=5;
			}
		?>
            <?php if($bmi): //$bmiにちゃんとデータが入っていたら
			$tekisei=$weight - $rw;
			     if($weight< $tekisei ){
			        	$teki=-$tekisei;
			        	$gap="+".$teki;
				 }else if($weight>=$tekisei){
			        $gap=$tekisei;
				 }
				 $_SESSION["gap"]=$gap;
		?>
            <div id="result">
                <?php
        	if(isset($_POST["name"])){
        		echo "<h2>{$_POST['name']} 様の測定結果</h2>";
        		$_SESSION["name"]=$_POST["name"];
        		$_SESSION["height"]=$height;
        		$_SESSION["weight"]=$weight;
        		$_SESSION["bmi"]=$rw;
        	}
        	?>
                <?php  switch($i){
        		case 0:
        	?>
                <p>身長<?= $height ?>cm、体重<?= $weight; ?>kgのあなたのBMIは……<br>
                    <span class="bigger"><?= $bmi; ?></span>　低体重です。
                </p>
                <p>貧血や栄養失調など気をつけないといけません。</p>
                <p>あなたの適正体重は…<span
                        class="bigger"><?= $rw;  echo"kg</span><br><span class='large'>適正体重まで、", $gap; ?>kgです。</span>
                </p>
                <?php break; ?>
                <?php
        		case 1:
        	?>
                <p>身長<?= $height ?>cm、体重<?= $weight; ?>kgのあなたのBMIは……<br>
                    <span class="bigger"><?= $bmi; ?></span>　普通体重です。
                </p>
                <p>今の体重を維持できると、健康上とても良いと思います。</p>
                <p>あなたの適正体重は…<span
                        class="bigger"><?= $rw;  echo"kg</span><br><span class='large'>適正体重まで、", $gap; ?>kgです。</span>
                </p>
                <?php break; ?>
                <?php
        		case 2:
        	?>
                <p>身長<?= $height ?>cm、体重<?= $weight; ?>kgのあなたのBMIは……<br>
                    <span class="bigger"><?= $bmi; ?></span>　肥満度1です。
                </p>
                <p>肥満度1は過体重レベルですが、肥満予備軍です。　少し運動などお勧めです</p>
                <p>あなたの適正体重は…<span
                        class="bigger"><?= $rw;  echo"kg</span><br><span class='large'>適正体重まで、", $gap; ?>kgです。</span>
                </p>
                <?php break; ?>
                <?php
        		case 3:
        	?>
                <p>身長<?= $height ?>cm、体重<?= $weight; ?>kgのあなたのBMIは……<br>
                    <span class="bigger"><?= $bmi; ?></span>　肥満度2です。
                </p>
                <p>肥満度2は肥満レベルですが、まだ軽度。　運動などでBMIを落としましょう。</p>
                <p>あなたの適正体重は…<span
                        class="bigger"><?= $rw;  echo"kg</span><br><span class='large'>適正体重まで、", $gap;?>kgです。</span></p>
                <?php break; ?>
                <?php
        		case 4:
        	?>
                <p>身長<?= $height ?>cm、体重<?= $weight; ?>kgのあなたのBMIは……<br>
                    <span class="bigger"><?= $bmi; ?></span>　肥満度3です。
                </p>
                <p>肥満度3は高度肥満レベルです。ダイエットや、運動などで<br>
                    健康管理しないと成人病の危険もあります。</p>
                <p>あなたの適正体重は…<span
                        class="bigger"><?= $rw;  echo"kg<br><span class='large'>適正体重まで、", $gap; ?></span>kgです。</p>
                <?php break; ?>
                <?php
        		case 5:
        	?>
                <p>身長<?= $height ?>cm、体重<?= $weight; ?>kgのあなたのBMIは……<br>
                    <span class="bigger"><?= $bmi; ?></span>　肥満度4です。
                </p>
                <p>肥満度4は重症の高度肥満レベルです。ダイエットと運動で減量を<br>
                    心がけないと、高血圧・高血漿・糖尿などの症状が出る可能性が高いです。</p>
                <p>あなたの適正体重は…<span class="bigger"><?= $rw;  echo"kg<br><span class='large'>適正体重まで、", $gap;?></span>kgです。
                </p>
                <?php break; ?>
            </div>
            <?php }//switch終了
        		endif; ?>
            <br>
            <table id="data">
                <caption>判定表</caption>
                <tr>
                    <th>指標</th>
                    <th>判定</th>
                </tr>
                <tr>
                    <td>18.5未満</td>
                    <td>低体重(痩せ型)　</td>
                </tr>
                <tr>
                    <td>18.5～25未満　</td>
                    <td>普通体重</td>
                </tr>
                <tr>
                    <td>25～30未満</td>
                    <td>肥満(1度)</td>
                </tr>
                <tr>
                    <td>30～35未満</td>
                    <td>肥満(2度)</td>
                </tr>
                <tr>
                    <td>35～40未満</td>
                    <td>肥満(3度)</td>
                </tr>
                <tr>
                    <td>40以上</td>
                    <td>肥満(4度)</td>
                </tr>
            </table>
        </section>
        <br></hr><br>
        <form method="post" action="reg_list.php">
            <h3>メールアドレスで登録すると、過去データとの比較が可能になります。</h3>
            Eメールアドレス：<input type="email" name="mail" placeholder="sample@mail.com" size="40" required><br>
            <input type="submit" value="登録してデータを保存">
        </form>

    </main>

    <footer id="sitefooter">
        <small>&copy; Yuki Furukawa portfolio.</small>
    </footer>

</body>

</html>