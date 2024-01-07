<?php
// session.php
session_start();
include 'conn.php';
$action = isset($_POST['action']) ? $_POST['action'] : '';

if($action == 'SessionAdd')
{

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $name = $_POST['name'];
        $contact = $_POST['contact'];
        $email = $_POST['email'];
        $catid = $_POST['catid'];
        $catname = $_POST['catname'];
        $subcatid = $_POST['subcatid'];
        $subcatname = $_POST['subcatname'];
        $itemid = $_POST['itemid'];
        $itemname = $_POST['itemname'];
        
        $_SESSION['name'] = $name;
        $_SESSION['contact'] = $contact;
        $_SESSION['email'] = $email;
        $_SESSION['catid'] = $catid;
        $_SESSION['catname'] = $catname;
        $_SESSION['subcatid'] = $subcatid;
        $_SESSION['subcatname'] = $subcatname;
        $_SESSION['itemid'] = $itemid;
        $_SESSION['itemname'] = $itemname;
        
        $res['status'] = 1;
        $res['message'] = "Session data saved successfully";
    } else {
        $res['status'] = 0;
        $res['message'] = "No session data found";
    }
}
else if($action == 'SessionFetch')
{
    if (isset($_SESSION['name']) && isset($_SESSION['contact']) && isset($_SESSION['email']) && isset($_SESSION['catid']) && isset($_SESSION['catname']) && isset($_SESSION['subcatid']) && isset($_SESSION['subcatname']) && isset($_SESSION['itemid']) && isset($_SESSION['itemname'])) 
    {
        $res['name'] = $_SESSION['name'];
        $res['contact'] = $_SESSION['contact'];
        $res['email'] = $_SESSION['email'];
        $res['catid'] = $_SESSION['catid'];
        $res['catname'] = $_SESSION['catname'];
        $res['subcatid'] = $_SESSION['subcatid'];
        $res['subcatname'] = $_SESSION['subcatname'];
        $res['itemid'] = $_SESSION['itemid'];
        $res['itemname'] = $_SESSION['itemname'];

        $res['status'] = 1;
        $res['message'] = "Data Fetched";
    } 
    else 
    {
        $res['status'] = 0;
        $res['message'] = "No data Found";
    }
}

else if($action == 'RemoveSession')
{
    if ($_SERVER['REQUEST_METHOD'] == 'POST') 
    {
        session_destroy();
        //session_unset();

        $res['status'] = 1;
        $res['message'] = "Session Remove";
    }
    else
    {
        $res['status'] = 0;
        $res['message'] = "No Data Found";
    }


}

$jsonData = json_encode($res);
echo $jsonData;
?>