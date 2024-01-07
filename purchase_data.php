<?php
$conn = new PDO("mysql:host=localhost;dbname=instance;",'root','');

$id = isset($_POST['id']) ? $_POST['id'] : '' ;
$catid = isset($_POST['catid']) ? $_POST['catid'] : '' ;
$subcatid = isset($_POST['subcatid']) ? $_POST['subcatid'] : '' ;
$itemid = isset($_POST['itemid']) ? $_POST['itemid'] : '' ;
$purchaseid = isset($_POST['purchaseid']) ? $_POST['purchaseid'] : '' ;
$purchasename = isset($_POST['purchasename']) ? $_POST['purchasename'] : '' ;
$action = isset($_POST['action']) ? $_POST['action'] : '' ;

if($action == 'category_fetch')
{
    $sql = "select * from category";

    $result = $conn->prepare($sql);
    $result->execute();

    if($result)
    {
        $i = 0;
        while($data = $result->fetch(PDO::FETCH_ASSOC))
        {
            $res['categorydata'][$i]['id'] = $data['id'];
            $res['categorydata'][$i]['name'] = $data['name'];
            $i++;
        }
        $res['status'] = 1;
        $res['message'] = "Data Fetched";
    }
    else
    {
        $res['status'] = 0;
        $res['message'] = "No Data Found";
    }
}
else if($action == 'subcategory_fetch')
{
    //$sql = "select * from subcategory";
    $sql = "select s.id, s.subcatname, c.name as catid from subcategory s inner join category c on s.catid = c.id where c.id = '$catid'";

    $result = $conn->prepare($sql);
    $result->execute();

    if($result)
    {
        $i = 0;
        while($data = $result->fetch(PDO::FETCH_ASSOC))
        {
            $res['categorydata'][$i]['id'] = $data['id'];
            $res['categorydata'][$i]['catid'] = $data['catid'];
            $res['categorydata'][$i]['subcatname'] = $data['subcatname'];
            $i++;
        }
        $res['status'] = 1;
        $res['message'] = "Data Fetched";
    }
    else
    {
        $res['status'] = 0;
        $res['message'] = "No Data Found";
    }
}

else if($action == 'item_fetch')
{
    $sql = "select i.itemname, i.itemamount, s.id, s.subcatname as subcatid, c.name as catid from item i inner join subcategory s on i.subcatid = s.id inner join category c on i.catid = c.id where s.id = '$subcatid'";

    $result = $conn->prepare($sql);
    $result->execute();

    if($result)
    {
        $i = 0;
        while($data = $result->fetch(PDO::FETCH_ASSOC))
        {
            $res['categorydata'][$i]['id'] = $data['id'];
            $res['categorydata'][$i]['catid'] = $data['catid'];
            $res['categorydata'][$i]['subcatid'] = $data['subcatid'];
            $res['categorydata'][$i]['itemname'] = $data['itemname'];
            $res['categorydata'][$i]['itemamount'] = $data['itemamount'];
            $i++;
        }
        $res['status'] = 1;
        $res['message'] = "Data Fetched";
    }
    else
    {
        $res['status'] = 0;
        $res['message'] = "No Data Found";
    }
}

else if($action == 'purchase_insert')
{
    $data = [
        'purchasename' => $purchasename,
    ];
    $sql = "INSERT INTO purchasetbl1 (purchasename) VALUES (:purchasename)";
    $result = $conn->prepare($sql);
    $result->execute($data);
    
    $purchaseid = $conn->lastInsertId();  
    
    if ($result) {
        foreach ($_POST['tblcatid'] as $index => $tblcatid) {
            $tblcatname = $_POST['tblcatname'][$index];
            $tblsubcatid = $_POST['tblsubcatid'][$index];
            $tblsubcatname = $_POST['tblsubcatname'][$index];
            $tblitemid = $_POST['tblitemid'][$index];
            $tblitemname = $_POST['tblitemname'][$index];
            $tblitemamount = $_POST['tblitemamount'][$index];
            $tblquantity = $_POST['tblquantity'][$index];
            $tbltotalamount = $_POST['tbltotalamount'][$index]; // If not relevant, remove this line
    
            $sql = "INSERT INTO purchasetbl2 (purchaseid,catid,catname,subcatid,subcatname,itemid,itemname,itemamount,quantity,totalamount) 
                    VALUES (:purchaseid, :tblcatid, :tblcatname, :tblsubcatid, :tblsubcatname, :tblitemid, :tblitemname, :tblitemamount, :tblquantity,:tbltotalamount)";
    
            $data = [
                'purchaseid' => $purchaseid,
                'tblcatid' => $tblcatid,
                'tblcatname' => $tblcatname,
                'tblsubcatid' => $tblsubcatid,
                'tblsubcatname' => $tblsubcatname,
                'tblitemid' => $tblitemid,
                'tblitemname' => $tblitemname,
                'tblitemamount' => $tblitemamount,
                'tblquantity' => $tblquantity,
                'tbltotalamount'=>$tbltotalamount,
            ];
    
            $result = $conn->prepare($sql);
            $result->execute($data);
        }
    
        $res['status'] = 1;
        $res['message'] = "Data Inserted";
    } else {
        $res['status'] = 0;
        $res['message'] = "Data Not Inserted";
    }
    
}
else if($action == 'purchase_master_fetch')
{
    $sql = "select * from purchasetbl1";

    $result = $conn->prepare($sql);
    $result->execute();

    if($result)
    {
        $i = 0;
        while($data = $result->fetch(PDO::FETCH_ASSOC))
        {
            $res['categorydata'][$i]['id'] = $data['id'];
            $res['categorydata'][$i]['purchasename'] = $data['purchasename'];
            $i++;
        }
        $res['status'] = 1;
        $res['message'] = "Data Fetched";
    }
    else
    {
        $res['status'] = 0;
        $res['message'] = "No Data Found";
    }
}

else if($action == 'purchase_details_fetch')
{
    $sql = "select p.itemamount, p.quantity, p.totalamount, i.itemname as itemid ,s.subcatname as subcatid , c.name as catid from purchasetbl2 p inner join item i on p.itemid = i.id inner join subcategory s on i.subcatid = s.id inner join category c on s.catid = c.id where p.purchaseid = '$purchaseid' ";


    $result = $conn->prepare($sql);
    $result->execute();

    if($result)
    {
        $i = 0;
        while($data = $result->fetch(PDO::FETCH_ASSOC))
        {
            $res['categorydata'][$i]['catid'] = $data['catid'];
            $res['categorydata'][$i]['subcatid'] = $data['subcatid'];
            $res['categorydata'][$i]['itemid'] = $data['itemid'];
            $res['categorydata'][$i]['itemamount'] = $data['itemamount'];
            $res['categorydata'][$i]['quantity'] = $data['quantity'];
            $res['categorydata'][$i]['totalamount'] = $data['totalamount'];
            $i++;
        }
        $res['status'] = 1;
        $res['message'] = "Data Fetched";
    }
    else
    {
        $res['status'] = 0;
        $res['message'] = "No Data Found";
    }
}
else if($action == 'purchase_Deleted')
{
    $data = [
        'purchaseid' => $id,
        'purchaseid' => $purchaseid,
    ];
    
    $sql1 = "DELETE FROM purchasetbl1 WHERE id = :purchaseid";
    $result1 = $conn->prepare($sql1);
    $result1->execute($data);
    

    $sql2 = "DELETE FROM purchasetbl2 WHERE purchaseid = :purchaseid";
    $result2 = $conn->prepare($sql2);
    $result2->execute($data);

    if ($result1 && $result2) {
        $res['status'] = 1;
        $res['message'] = "Data Deleted";
    } 
    else 
    {
        $res['status'] = 0;
        $res['message'] = "Data Not Deleted";
    }
}

else if($action == 'purchase_edit')
{
    $data1 = [
        'purchaseid' => $id,
        'purchaseid' => $purchaseid,
    ];
    
    $sql1 = "SELECT * FROM purchasetbl1 WHERE id = :purchaseid";
    $result1 = $conn->prepare($sql1);
    $result1->execute($data1);
    
    $dataPurchase = $result1->fetch(PDO::FETCH_ASSOC);
    
    if ($result1) {
        $res['purchasename'] = $dataPurchase['purchasename'];
        $res['status'] = 1;
        $res['message'] = "Data Found";
    } else {
        $res['status'] = 0;
        $res['message'] = "No Data Found";
    }
    
    $data2 = [
        'purchaseid' => $id,
        'purchaseid' => $purchaseid,
    ];
    
    $sql2 = "SELECT * FROM purchasetbl2 WHERE purchaseid = :purchaseid";
    $result2 = $conn->prepare($sql2);
    $result2->execute($data2);
    
    if ($result2) {
        $i = 0;
        while ($data = $result2->fetch(PDO::FETCH_ASSOC)) {
            $res['categorydata'][$i]['catid'] = $data['catid'];
            $res['categorydata'][$i]['catname'] = $data['catname'];
            $res['categorydata'][$i]['subcatid'] = $data['subcatid'];
            $res['categorydata'][$i]['subcatname'] = $data['subcatname'];
            $res['categorydata'][$i]['itemid'] = $data['itemid'];
            $res['categorydata'][$i]['itemname'] = $data['itemname'];
            $res['categorydata'][$i]['itemamount'] = $data['itemamount'];
            $res['categorydata'][$i]['quantity'] = $data['quantity'];
            $res['categorydata'][$i]['totalamount'] = $data['totalamount'];
            $i++;
        }
    
        $res['status'] = 1;
        $res['message'] = "Data Found";
    } else {
        $res['status'] = 0;
        $res['message'] = "No Data Found";
    }
}
else if($action == 'purchase_update')
{
    $data = [
        'purchasename' => $purchasename,
        'purchaseid' => $purchaseid,
    ];
    
    $sqlUpdate = "UPDATE purchasetbl1 SET purchasename = :purchasename WHERE id = :purchaseid";
    $resultUpdate = $conn->prepare($sqlUpdate);
    $resultUpdate->execute($data);
    
    if ($resultUpdate) {
        $sqlDelete = "DELETE FROM purchasetbl2 WHERE purchaseid = :purchaseid";
        $resultDelete = $conn->prepare($sqlDelete);
        $resultDelete->execute($data);
    
        // Insert new data into purchasetbl2
        for ($i = 0; $i < count($_POST['tblcatid']); $i++) {
            $tblcatid = $_POST['tblcatid'][$i];
            $tblcatname = $_POST['tblcatname'][$i];
            $tblsubcatid = $_POST['tblsubcatid'][$i];
            $tblsubcatname = $_POST['tblsubcatname'][$i];
            $tblitemid = $_POST['tblitemid'][$i];
            $tblitemname = $_POST['tblitemname'][$i];
            $tblitemamount = $_POST['tblitemamount'][$i];
            $tblquantity = $_POST['tblquantity'][$i];
            $tbltotalamount = $_POST['tbltotalamount'][$i];
    
            $dataInsert = [
                'purchaseid' => $purchaseid,
                'tblcatid' => $tblcatid,
                'tblcatname' => $tblcatname,
                'tblsubcatid' => $tblsubcatid,
                'tblsubcatname' => $tblsubcatname,
                'tblitemid' => $tblitemid,
                'tblitemname' => $tblitemname,
                'tblitemamount' => $tblitemamount,
                'tblquantity' => $tblquantity,
                'tbltotalamount' => $tbltotalamount,
            ];
    
            $sqlInsert = "INSERT INTO purchasetbl2 (purchaseid, catid, catname, subcatid, subcatname, itemid, itemname, itemamount, quantity, totalamount) 
                          VALUES (:purchaseid, :tblcatid, :tblcatname, :tblsubcatid, :tblsubcatname, :tblitemid, :tblitemname, :tblitemamount, :tblquantity, :tbltotalamount)";
    
            $resultInsert = $conn->prepare($sqlInsert);
            $resultInsert->execute($dataInsert);
        }
    
        $res['status'] = 1;
        $res['message'] = "Data Updated";
    } else {
        $res['status'] = 0;
        $res['message'] = "Data Not Updated";
    }
}
$jsondata = json_encode($res);
echo $jsondata;
?>