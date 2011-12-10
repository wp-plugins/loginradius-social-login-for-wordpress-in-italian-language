<?php 
class LoginRadius{
public $IsAuthenticated,$JsonResponse,$UserProfile; 
public function construct($ApiSecrete){
$IsAuthenticated = false;
if(isset($_REQUEST['token'])){
$ValidateUrl = "http://hub.loginradius.com/userprofile.ashx?token=".$_REQUEST['token']."&apisecrete=".$ApiSecrete."";
$JsonResponse = file_get_contents($ValidateUrl);
if(isset($JsonResponse)){
$UserProfile=json_decode($JsonResponse);
if(isset( $UserProfile->ID) && $UserProfile->ID!=''){ 
$this->IsAuthenticated = true;
return $UserProfile;
}}}}}
?>