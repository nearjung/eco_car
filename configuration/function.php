<?php
class API
{
	public function popup($title, $text, $icon = 'success', $path = null)
	{
		echo "<script>
		Swal.fire({
			icon: '" . $icon . "',
			title: '" . $title . "',
			text: '" . $text . "'
		  })";
		if ($path) {
			echo ".then(result=>{
			  if(result) {
				window.top.location='" . $path . "';
			  }
		  })";
		}
		echo "</script>";
	}

	public function go($link)
	{
		echo "<script>window.top.location='" . $link . "';</script>";
	}

	public function wait($link, $time)
	{
		echo '<script> window.setTimeout(function(){
        window.location.href = "' . $link . '"; }, ' . $time . ');</script>';
	}

	public function log($data)
	{
		global $db, $date;
		$sql = $db->prepare("INSERT INTO trlog(data, createDate) VALUES(:data, :createDate)");
		$sql->bindParam(":data", $data);
		$sql->bindParam(":createDate", $date);
		$sql->execute();
	}

	public function jsonParse($data)
	{
		return json_encode($data, JSON_UNESCAPED_UNICODE);
	}
}
