<?php
	// Made By Seven (Minjae Seon) - seven@sevens.pe.kr
	// Required : PHP Simple HTML DOM Parser (http://simplehtmldom.sourceforge.net/)
	
	// UTF-8 HEADER SETTING
	header('Content-Type: application/json;charset=UTF-8');
	
	// INCLUDE PARSER
	include("include/simple_html_dom.php");
	
	function json_encode2($data) {
	switch (gettype($data)) {
		case 'boolean':
		return $data?'true':'false';
		case 'integer':
		case 'double':
		return $data;
		case 'string':
		return '"'.strtr($data, array('\\'=>'\\\\','"'=>'\\"')).'"';
		case 'array':
		$rel = false; // relative array?
		$key = array_keys($data);
		foreach ($key as $v) {
		if (!is_int($v)) {
		$rel = true;
		break;
		}
		}

		$arr = array();
		foreach ($data as $k=>$v) {
		$arr[] = ($rel?'"'.strtr($k, array('\\'=>'\\\\','"'=>'\\"')).'":':'').json_encode2($v);
		}

		return $rel?'{'.join(',', $arr).'}':'['.join(',', $arr).']';
		default:
		return '""';
		}
	}
	
	$dates = $_GET["date"];
	
	$page = file_get_html("http://eoram.ms.kr/lunch.view?date=$dates");
	
	$datas = array();
	foreach($page->find('div.menuName') as $element)
		$datas[] = $element;
	
	if(isset($element))
	{
		foreach ($datas as $out)
		{
			$removestring = array('<div class="menuName">  <span>', ' </span>  </div>', '①', '②', '③', '④', '⑤', '⑥', '⑦', '⑧', '⑨', '⑩', '⑪', '⑫', '⑬', '⑭', '⑮', '⑰', '&#9328;', '&#9327;', '</span> </div');
			$out = str_replace($removestring, "", $out);
			$out = str_replace("<br /> ", ",", $out);
			$len = strlen($out) - 1;
			$out = substr($out, 0, $len);
			$json["success"] = "true";
			$json["code"] = "200";
			$json["data"] = $out;
		}
	}
	else
	{
		$json["success"] = "false";
		$json["code"] = "501";
		$json["data"] = "";
	}
	echo json_encode2($json);
?>