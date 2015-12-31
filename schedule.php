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
		
	$page = file_get_html("http://eoram.ms.kr/calendar.view?date=$dates");
	
	$datas = array();
	foreach($page->find('table') as $row) {
		$flight = array();
		foreach($row->find('td[class=listTit]') as $cell) {
			$flight[] = $cell->plaintext;
		}
		$datas[] = $flight;
	}
	
	if(isset($cell))
	{
		foreach ($datas as $out)
		{
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