<?php
include 'conn.php';
$id= isset($_POST['id']) ? $_POST['id'] : '';
$catid= isset($_POST['catid']) ? $_POST['catid'] : '';
$subcatid= isset($_POST['subcatid']) ? $_POST['subcatid'] : '';
$action= isset($_POST['action']) ? $_POST['action'] : '';
$purchaseid= isset($_POST['purchaseid']) ? $_POST['purchaseid'] : '';

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

else if($action == 'SubCategory_Fetch')
{
    $sql = "select s.id , s.subcatname, c.name as catid from subcategory s inner join category c on s.catid = c.id where c.id = '$catid'";
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

else if($action == 'item_Fetch')
{
    $sql = "select i.id, i.itemname, i.itemno, i.itemamount, s.subcatname as subcatid , c.name as catid from item i inner join subcategory s on i.subcatid = s.id inner join category c on i.catid = c.id where s.id = '$subcatid'";

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
            $res['categorydata'][$i]['itemno'] = $data['itemno'];
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
    $purchasename = $_POST['purchasename'];
    $data = [
       'purchasename' =>$purchasename,
    ];

    $sql = "insert into purchasetbl1 (purchasename) values(:purchasename)";

    $result = $conn->prepare($sql);
    $result->execute($data);

    $purchaseid = $conn->lastInsertId();

    if($result)
    {
        foreach($_POST['tblcatid'] as $index=>$tblcatid)
        {
            $tblcatname = $_POST['tblcatname'][$index];
            $tblsubcatid = $_POST['tblsubcatid'][$index];
            $tblsubcatname = $_POST['tblsubcatname'][$index];
            $tblitemid = $_POST['tblitemid'][$index];
            $tblitemname = $_POST['tblitemname'][$index];
            $tblitemamount = $_POST['tblitemamount'][$index];
            $tblquantity = $_POST['tblquantity'][$index];
            $tbltotalamount = $_POST['tbltotalamount'][$index];

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
    }
    else
    {
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
        $res['status']= 1;
        $res['message'] = "Data Fetched";
    }
    else
    {
        $res['status'] = 0;
        $res['message'] = "No Data Found";
    }
}

else if($action == 'purchase_detail_fetch')
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

else if($action == 'purchase_delete')
{
    $data = [
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
    $data = [
        'purchaseid' => $purchaseid,
    ];
    
    $sql1 = "SELECT * FROM purchasetbl1 WHERE id = :purchaseid";
    $result = $conn->prepare($sql1);
    $result->execute($data);
    
    $dataPurchase = $result->fetch(PDO::FETCH_ASSOC);
    if ($result) 
    {
        $res['purchasename'] = $dataPurchase['purchasename'];
    
        $sql2 = "SELECT * FROM purchasetbl2 WHERE purchaseid = :purchaseid";
        $result = $conn->prepare($sql2);
        $result->execute($data);
        
        
        $i = 0;
        while ($data = $result->fetch(PDO::FETCH_ASSOC))
        {
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
    } 
    else 
    {
        $res['status'] = 0;
        $res['message'] = "No Data Found";
    }
}


else if($action == 'purchase_update')
{
    $purchasename = $_POST['purchasename'];
    $updatedata = [
        'purchasename'=>$purchasename,
        'purchaseid' =>$purchaseid,
    ];

    $sql = "update purchasetbl1 set purchasename = :purchasename where id=:purchaseid";
    $result = $conn->prepare($sql);
    $result->execute($updatedata);

    if($result)
    {
        $deletData = [
            'purchaseid' => $purchaseid,
        ];
        $sql = "delete from purchasetbl2 where purchaseid = :purchaseid";
        $result = $conn->prepare($sql);
        $result->execute($deletData);

        for($i = 0; $i < count($_POST['tblcatid']); $i++)
        {
            $tblcatid = $_POST['tblcatid'][$i];
            $tblcatname = $_POST['tblcatname'][$i];
            $tblsubcatid = $_POST['tblsubcatid'][$i];
            $tblsubcatname = $_POST['tblsubcatname'][$i];
            $tblitemid = $_POST['tblitemid'][$i];
            $tblitemname = $_POST['tblitemname'][$i];
            $tblitemamount = $_POST['tblitemamount'][$i];
            $tblquantity = $_POST['tblquantity'][$i];
            $tbltotalamount = $_POST['tbltotalamount'][$i];

            $Insertdata = [
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

            $sql = "insert into purchasetbl2 (purchaseid,catid,catname,subcatid,subcatname,itemid,itemname,itemamount,quantity,totalamount) values(:purchaseid,:tblcatid,:tblcatname,:tblsubcatid,:tblsubcatname,:tblitemid,:tblitemname,:tblitemamount,:tblquantity,:tbltotalamount)";

            $result = $conn->prepare($sql);
            $result->execute($Insertdata);
    
        }
        $res['status'] = 1;
        $res['message'] = "Data Updated";
    }
    else
    {
        $res['status'] = 0;
        $res['message'] = "Data Not Updated";
    }


}
$jsondata = json_encode($res);
echo $jsondata;

?>