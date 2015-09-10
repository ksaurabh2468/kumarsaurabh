<html>
<head>
 <link rel="stylesheet" href="css/bootstrap.min.css" type="text/css" />
</head>
<body style="background-image:url('bg.jpg'); background-size:cover;">
<div class="wrapper-md" style="padding:40px">

<?php 

$html_content = "";

$onedayissues = 0;
$json = 0;
if($_POST)
{
	$sevendayissues = 0;
	$onedayissues = 0;

	$data = [];
	$repository = $_POST['url'];
	$cnt = 1;
	$json = get_web_json("https://api.github.com/repos/" . $repository);
	$total = $json->open_issues_count;
	getdata($cnt , $repository , $data, $sevendayissues , $onedayissues , $total);
	//var_dump($data2);
	
	//var_dump($json);
	    
}





function getdata($cnt , $repository ,  $data , $sevendayissues , $onedayissues ,  $total){

	//$data = array();
	$date = strtotime(date('Y-m-d H:i:s')) - 24*7*60*60;
    $date2 = date('c', $date);
    //$date =	$date->format(DateTime::ISO8601);
    //var_dump($date2);	
    
    $url = "https://api.github.com/repos/" . $repository . "/issues?page=" .  $cnt . "&state=open&since=" . $date2;
    $objs = get_web_json($url);
    
    foreach ($objs as $key=>$obj)
    {
    	$data[$key + ($cnt - 1)*30] = $obj;
    }
    //var_dump($data);
    
   // echo("Success");
    if(count($objs)==30){

    	$cnt += 1;
    	getdata($cnt , $repository, $data, $sevendayissues, $onedayissues , $total);

    }
    else{

    	foreach ($data as $key => $issues) {
    		
    		if(date("U",strtotime($issues->created_at)) > (strtotime(date('Y-m-d H:i:s')) - 24*7*60*60))
    		{
    			$sevendayissues += 1;

    			if(date("U",strtotime($issues->created_at)) > (strtotime(date('Y-m-d H:i:s')) - 24*60*60))
	    		{
	    			$onedayissues += 1;
	    		}

    		}

    	}
    	//var_dump($data);
    	?>
    	<div class="row" style="padding:40px; height:250px; margin: 42px -50px 37px -50px; border: 2px solid #808080; background-color:white;">

    		<div class="col-md-3" style="text-align:center">
    			<div style="height:150px; width:150px; background-image:url('6.GIF'); text-align:center; padding-top:40px; margin-left:60px">
    			<h2>
    				<?php echo  $total; ?>
    			</h2>
    			</div>
    			<p  style="margin-top:10px" ><b>Total Issues</b></p>
    		</div>
    		<div class="col-md-3" style="text-align:center">
    			<div style="height:150px; width:150px; background-image:url('6.GIF'); text-align:center; padding-top:40px; margin-left:60px">
    			<h2>
    				<?php echo ( $total - $sevendayissues) ?>
    			</h2></div>
    			<p style="margin-top:10px" ><b>After Seven Days</b></p>
    		</div>
    		<div class="col-md-3" style="text-align:center">
    			<div style="height:150px; width:150px; background-image:url('6.GIF'); text-align:center; padding-top:40px; margin-left:60px">
    			<h2> 
    				<?php echo ($sevendayissues - $onedayissues) ?>

    			</h2></div>
    			<p style="margin-top:10px" > <b>24 Hours to Seven Days </b></p>
    		</div>
    		<div class="col-md-3" style="text-align:center">
    			<div style="height:150px; width:150px; background-image:url('6.GIF'); text-align:center; padding-top:40px; margin-left:60px">
    			<h2> 
    				<?php echo $onedayissues; ?>
    			</h2></div>
    			<p style="margin-top:10px" > <b>Till 24 Hours </b></p>
    		</div>

    	</div>
    	
    	<?php
    }
    

    
    

}


function get_web_json($url){

	$ch = curl_init();
	$agent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)';
	curl_setopt($ch, CURLOPT_USERAGENT, $agent);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_REFERER, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	$html_content = curl_exec($ch);
	curl_close($ch);
	return json_decode($html_content);
}

?>


<div class="row" style="padding:40px; height:250px; margin-top:40px">
<div>
<form action="" method="POST">
<div class="col-md-2" style="text-align:right; font-size:25px; padding:11px">
	<p>http://github.com/</p>
</div>
<div class="col-md-6">
	<input class="form-control" type="text" id="url" placeholder="org/repo"name="url" style="height:54px">
</div>
<div class="col-md-4">
	<input class="form-control" type="submit" value="Get Issues" style="height:54px; background-color:#ccc; font-size=20px">
</div>
</form>
</div>
</div>
<script src="js/bootstrap.min.js"></script>
</body>
</html>