<?php
require './config.php';

if (
	(($_FILES["file"]["type"] == "audio/mpeg")
	||($_FILES["file"]["type"] == "audio/wav")
	||($_FILES["file"]["type"] == "audio/mp3"))
	&& ($_FILES["file"]["size"] < 15728670)) // 文件小于15Mb
{
 	if ($_FILES["file"]["error"] > 0)
    {
    	echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
    }
  	else
    {
	    echo "Upload: " . $_FILES["file"]["name"] . "<br />";
	    echo "Type: " . $_FILES["file"]["type"] . "<br />";
	    echo "Size: " . (number_format($_FILES["file"]["size"] / 1048576, 2)) . " Mb<br />";
	    if (file_exists("../public/MP3/" . $_FILES["file"]["name"]))
	    {
	      	echo $_FILES["file"]["name"] . " 已存在,无需重复上传";
	    }
	    else
	    {
	      	move_uploaded_file($_FILES["file"]["tmp_name"],
	      	"../public/MP3/" . $_FILES["file"]["name"]);
	      	echo $_FILES["file"]["name"] . " 已成功上传";
            $src = "../public/MP3/" . $_FILES["file"]["name"];
            $singer = $_POST['singer'];
            $type = $_POST['type'];
            $name = $_POST['name'];
            $lyric = $_POST['lrc'];
            $type = $_POST['type'];
            $insertMusicSql = mysql_query("INSERT INTO app_Music (src, name, lyric) VALUES ('{$src}', '{$name}', '{$lyric}')");
            $insertSingerSql = mysql_query("INSERT INTO app_Singer (singer_name) VALUES ('{$singer}')");
            if ($row = mysql_affected_rows()) {
                $selectMusicSql = mysql_query("SELECT music_id FROM app_Music WHERE src='{$src}'");
                $selectSingerSql = mysql_query("SELECT singer_id FROM app_Singer WHERE singer_name='{$singer}'");
                $mSQL = mysql_fetch_array($selectMusicSql, MYSQL_ASSOC);
                $sSQL = mysql_fetch_array($selectSingerSql, MYSQL_ASSOC);
                $music_id = $mSQL['music_id'];
                $singer_id = $sSQL['singer_id'];
                mysql_query("INSERT INTO app_musicRclass (music_id, class_id) VALUES('{$music_id}', '{$type}')");
                mysql_query("INSERT INTO app_singerRmusic (music_id, singer_id) VALUES('{$music_id}', '{$singer_id}')");
            }
        }
        echo "<br><a href='../'><input type='button' value='返回首页'></a>";

    }
}
else
{	
  echo "上传文件只允许为音乐文件，且小于15M";
}
?>