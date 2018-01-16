<?php
//取得Google公開的表單回覆資料，回傳Json檔
$url = $_GET['url'];
$html = file_get_contents($url);
$dom = new DOMDocument();
@$dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
// grab all the on the page
$xpath = new DOMXPath($dom);
//這裡抓到標題列，確認有幾個欄位
$hrefs = $xpath->evaluate("/html/body//tr[1]//td");
$titleCount = 0;
echo '{"title":[';
for ($i = 0; $i < $hrefs->length; $i++) 
{
	$href = $hrefs->item($i);
	$url = $href->nodeValue;
	if($i == $hrefs->length-1)
		echo '{"name":"'.$url.'"}';
	else
		echo '{"name":"'.$url.'"},';
	$titleCount++;
}
echo '],"count":"'.$titleCount.'","comments":[';

//這裡抓第n筆資料，對應到欄位下面
$n = 3;
while(true)
{
	$hrefs = $xpath->evaluate("/html/body//tr[".$n."]//td");
	if($hrefs->length>0)
	{
		echo '{"reply":[';
		for ($i = 0; $i < $hrefs->length; $i++) 
		{
			$href = $hrefs->item($i);
			$url = $href->nodeValue;
			if($i == $hrefs->length-1)
				echo '{"data":"'.$url.'"}';
			else
				echo '{"data":"'.$url.'"},';
		}
		echo ']}';
	}
	else
		break;
	$n++;
	$hrefs = $xpath->evaluate("/html/body//tr[".$n."]//td");
	if($hrefs->length>0)
	{
		echo ',';
	}
}
echo "]}";
?>