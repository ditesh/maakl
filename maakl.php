<?php

require_once("simplehtmldom/simple_html_dom.php");

$url = "https://www.maaklmutual.com.my/Pages/PnI/Arc/archive.aspx";
$html= file_get_html($url);

$r = $html->find("#__VIEWSTATE", 0);
$viewState = $r->value;
$r = $html->find("#__EVENTVALIDATION", 0);
$eventValidation = $r->value;

$from = date("dd-MM-yyyy", time()-3600*24*2);
$to = date("dd-MM-yyyy", time()-3600*24);
$code = "EA";
$btn = "Get Prices / indices";

$from = "05-07-2012";
$to = "06-07-2012";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, true);

$data = array(
    "txtDateFrom" => $from,
    "txtDateTo" => $to,
    "ddlFundCode" => $code,
    "BtnShowPrice" => $btn,
    'Header1$TextBox1' =>  "",
    "__EVENTTARGET" => "",
    "__EVENTARGUMENT" => "",
    "__EVENTVALIDATION" => $eventValidation,
    "__VIEWSTATE" => $viewState,

);

curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
$html = curl_exec($ch);
curl_close($ch);

$html= str_get_html($html);
$r = $html->find(".ITEMSDATESTYLE", 0)->next_sibling()->next_sibling();
$price = floatval($r->innertext);

mail("ditesh@gathani.org", "MAAKL current price is RM $price", "Current price is $price");
echo "MAAKL current price is RM $price";
