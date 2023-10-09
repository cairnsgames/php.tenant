<?php

include_once "./dbutils.php";
include_once "./utils.php";
include_once "./corsheaders.php";

$email = '';
$conn = null;
$out = [];
$errors = array();

// TODO: Get application detaisl for chnage password page


$appid = getParam("app_id","NONE");
if (!isset($appid)) {
    $appid = getHeader("APP_ID");
}
$out["appid"] = $appid;

if ($appid == "") {
    array_push($errors, array("message" => "APP_ID is required."));
} else
    try {
        // Check if email exists
        $sql = "SELECT application_property.name,application_property.value 
                FROM application, application_property 
                WHERE uuid = ?
                  and application_property.application_id = application.id";
        $params = array($appid);
        $rows = PrepareExecSQL($sql, "s", $params);
        if (count($rows) == 0) {
            throw new Exception('Application does not exist.');
        }
        
        $out["data"] = $rows;
    } catch (Exception $e) {
        array_push($errors, array("message" => $e->getMessage()));
    }

if (count($errors) > 0) {
    $out["errors"] = $errors;
}

echo json_encode($out);