<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>purchase</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <style>
        label.error{
            color:#f00;
        }
        table{
            border:2px solid black
        }
        tr ,td{
            border:2px solid black
        }
       /* #popupBox {
    display: none;
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: white;
    padding: 20px;
    border: 2px solid black;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
    z-index: 999;
}*/
.custom-width {
    max-width: 800px; 
    width: 80%; 
}

    </style>
</head>
<body>
    <center>
        <h2>Purchase Master:</h2>
        <form action="" method="POST" id="mypage">
            <label for="">Purchase Name</label>
            <input type="text" name="purchasename" id="purchasename">
            <hr><br>
            <h2>Purchase Details</h2>

            <label for="" Category>Category</label>
            <select name="catid" id="catid">
                <option value="" selected>Select Category</option>
            </select>

            <label for="" Subcategory>Subcategory</label>
            <select name="subcatid" id="subcatid">
                <option value="" selected>Select Subcategory</option>
            </select>

            <label for="" Item>Item</label>
            <select name="itemid" id="itemid">
                <option value="" selected>Select Item</option>
            </select>

            <label for="" itemamount>itemamount</label>
            <input type="text" name="itemamount" id="itemamount">

            <label for="" quantity>quantity</label>
            <input type="text" name="quantity" id="quantity">

            <input type="hidden" name="totalamount" id="totalamount">
            <input type="button" value="Add" id="addData" name="addData">

            <hr>
            <h2>Details Fetch</h2>
            <table>
                <thead>
                    <tr>
                        <td>Category</td>
                        <td>Subcategory</td>
                        <td>Item</td>
                        <td>Itemamount</td>
                        <td>Quantity</td>
                        <td>Total Amount</td>
                    </tr>
                </thead>
                <tbody id="gridBody">

                </tbody>
            </table>
        <br><br>
            <input type="submit" value="submit" id="submit" name="submit">
            <input type="submit" value="update" id="updateId" name="updateId">
            <input type="hidden" name="purchaseid" id="purchaseid">
            <input type="hidden" name="id" id="id">
        </form>
    </center>
<br><br>
    <center>
        <table>
            <thead>
                <tr>
                    <td>Purchasename</td>
                    <td colspan="2">Actions</td>
                </tr>
            </thead>
            <tbody id="purchaseBody">

            </tbody>
        </table>
    </center>



    <div class="modal fade" id="purchaseDetailsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog custom-width" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <table id="modalPopupTable" class="table">
                </table>
                <div id="purchaseDetailsModal">
                    <h2>Purchase Master Details<span id="popupPurchaseName"></span></h2>
                    <table id="popupTable" class="table">
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

    <script>
    $(document).ready(function(){
        category_fetch()
        purchase_master_fetch()
        showsubmitbutton()
    });

    function category_fetch()
    {
        $.ajax({
            method:'POST',
            url:'purchase_data.php',
            data:{'action':'category_fetch'},
            dataType:'json',
            success:function(data)
            {
                var jsonData = JSON.stringify(data)
                var resultdata = jQuery.parseJSON(jsonData);

                if(resultdata.status == 1)
                {
                    var table = '';
                    table += '<option value = "" >Select Category</option>'
                    for(var i in resultdata.categorydata)
                    {
                        table += '<option value ="'+resultdata.categorydata[i].id+'">'+resultdata.categorydata[i].name+'</option>';
                    }
                    $('#catid').html(table)
                    subcategory_fetch()
                }
            }
        })
    }
    $('#catid').change(function(){
        subcategory_fetch()
    })

    function subcategory_fetch()
    {
        var catid = $('#catid').val()
        $.ajax({
            method:'POST',
            url:'purchase_data.php',
            data:{catid:catid,'action':'subcategory_fetch'},
            dataType:'json',
            success:function(data)
            {
                var jsonData = JSON.stringify(data)
                var resultdata = jQuery.parseJSON(jsonData);

                if(resultdata.status == 1)
                {
                    var table = '';
                    table += '<option value = "" >Select Subcategory</option>'
                    for(var i in resultdata.categorydata)
                    {
                        table += '<option value ="'+resultdata.categorydata[i].id+'">'+resultdata.categorydata[i].subcatname+'</option>';
                    }
                    $('#subcatid').html(table)
                    item_fetch()
                }
            }
        });
    }

    $('#subcatid').change(function(){
        item_fetch()
    });

    $('#itemid').change(function(){
        var itemselect = $('#itemid option:selected');
        var itemamount = itemselect.attr('itemamount');
        $('#itemamount').val(itemamount)
    })

    function item_fetch()
    {
        var subcatid = $('#subcatid').val()
        $.ajax({
            method:'POST',
            url:'purchase_data.php',
            data:{subcatid:subcatid,'action':'item_fetch'},
            dataType:'json',
            success:function(data)
            {
                var jsonData = JSON.stringify(data)
                var resultdata = jQuery.parseJSON(jsonData);

                if(resultdata.status == 1)
                {
                    var table = '';
                    table += '<option value = "" >Select Item</option>'
                    for(var i in resultdata.categorydata)
                    {
                        table += '<option value ="'+resultdata.categorydata[i].id+'"itemamount= "'+resultdata.categorydata[i].itemamount+'">'+resultdata.categorydata[i].itemname+'</option>';
                    }
                    $('#itemid').html(table)
                }
            }
        });
    }

    $('#addData').click(function(){
        var catid = $('#catid').val();
        var catname = $('#catid option:selected').text();
        var subcatid = $('#subcatid').val();
        var subcatname = $('#subcatid option:selected').text();
        var itemid = $('#itemid').val();
        var itemname = $('#itemid option:selected').text();
        var itemamount = $('#itemamount').val();
        var quantity = $('#quantity').val();
        var totalamount = calAmount(itemamount,quantity); 

        if(!catid || !subcatid || !itemid || !itemamount || !quantity)
        {
            alert("Please fill all the field");
        }
        else
        {

            var row = findData(catid,subcatid,itemid);

            if(row)
            {
                row.remove();
            }

        var table = '';
        table += '<tr>';
        table += '<td><input type="hidden" id="tblcatid" name="tblcatid[]" value="'+catid+'" class="tblcatid"><input type="hidden" id="tblcatname" name="tblcatname[]" value="'+catname+'">'+catname+'</td>'

        table += '<td><input type="hidden" id="tblsubcatid" name="tblsubcatid[]" value="'+subcatid+'" class="tblsubcatid"><input type="hidden" id="tblsubcatname" name="tblsubcatname[]" value="'+subcatname+'">'+subcatname+'</td>'

        table += '<td><input type="hidden" id="tblitemid" name="tblitemid[]" value="'+itemid+'" class="tblitemid"><input type="hidden" id="tblitemname" name="tblitemname[]" value="'+itemname+'">'+itemname+'</td>'

        table += '<td><input type="text" id="tblitemamount" name="tblitemamount[]" value="'+itemamount+'" class="tblitemamount"></td>'

        table += '<td><input type="text" id="tblquantity" name="tblquantity[]" value="'+quantity+'" class="tblquantity"></td>'

        table += '<td><input type="hidden" id="tbltotalamount" name="tbltotalamount[]" value="'+totalamount+'" class="tbltotalamount">'+totalamount+'</td>'

        table += '<td><button class="removeRow" onclick="removeRow(this)">❎</button></td>' 
        table += '</tr>';


        $('#gridBody').append(table);
    }

    });

    function calAmount(itemamount,quantity)
    {
        return (parseFloat(itemamount) * parseFloat(quantity)).toFixed(2);
    }

    function updateAmount(row) {
    var itemamount = row.find('.tblitemamount').val();
    var quantity = row.find('.tblquantity').val();
    var totalamount = calAmount(itemamount, quantity);
    row.find('.tbltotalamount').text(totalamount);
}

function findData(catid, subcatid, itemid) {
    var row = null;

    $('#gridBody tr').each(function () {
        var rowCatid = $(this).find('.tblcatid').val();
        var rowSubcatid = $(this).find('.tblsubcatid').val();
        var rowItemid = $(this).find('.tblitemid').val();

        if (rowCatid === catid && rowSubcatid === subcatid && rowItemid === itemid) {
            row = $(this);
            return false;
        }
    });

    return row;
}

$('#gridBody').on('input', '.tblitemamount, .tblquantity', function () {
    var row = $(this).closest('tr');
    updateAmount(row)
});

        function removeRow(button) 
        {
            $(button).closest('tr').remove();
        }

        function purchase_master_fetch(purchaseid)
        {

            $.ajax({
                method:'POST',
                url:'purchase_data.php',
                dataType:'json',
                data:{purchaseid:purchaseid , 'action':'purchase_master_fetch'},
                success:function(data)
                {
                    var jsonData = JSON.stringify(data);
                    var resultdata = jQuery.parseJSON(jsonData);

                    if(resultdata.status == 1)
                    {
                        var table = '';
                        for(var i in resultdata.categorydata)
                        {
                            table += '<tr>';
                            table += '<td><a class="showRecord" data-id="' + resultdata.categorydata[i].id + '">'+resultdata.categorydata[i].purchasename+'</td>';
                            table += '<td><a href="javascript:void(0)" class="editRecoard" data-id="' + resultdata.categorydata[i].id + '" >Edit</a></td>';
                            table += '<td><a href="javascript:void(0)" class="deleteRecoard" data-id="' + resultdata.categorydata[i].id + '">Delete</a></td>';
                            table +='</tr>';
                        }
                        $('#purchaseBody').html(table);
                    }
                    
                }
            });
        }
        $('#purchaseBody').on('click','.showRecord',function(){
            var purchaseid = $(this).attr('data-id');
            console.log("purchase id " + purchaseid)
            purchase_details_fetch(purchaseid)
            $('#purchaseDetailsModal').modal('show'); 
        })

        function closepopup() {
        $('#popupBox').hide();
        }

        function purchase_details_fetch(purchaseid)
        {

            $.ajax({
                method:'POST',
                url:'purchase_data.php',
                data: {purchaseid: purchaseid, 'action':'purchase_details_fetch'},
                dataType: 'json',
                success:function(data)
                {
                    var jsonData = JSON.stringify(data);
                    var resultdata = jQuery.parseJSON(jsonData);

                    if(resultdata.status == 1)
                    {
                        var table = '';
                        table += '<thead><tr><th>Category</th><th>Subcategory</th><th>Item</th><th>Item Amount</th><th>Quantity</th><th>Total Amount</th></tr></thead>';
                        for(var i in resultdata.categorydata)
                        {
                            table += '<tr>';
                            table += '<td>'+resultdata.categorydata[i].catid+'</td>'
                            table += '<td>'+resultdata.categorydata[i].subcatid+'</td>'
                            table += '<td>'+resultdata.categorydata[i].itemid+'</td>'
                            table += '<td>'+resultdata.categorydata[i].itemamount+'</td>'
                            table += '<td>'+resultdata.categorydata[i].quantity+'</td>'
                            table += '<td>'+resultdata.categorydata[i].totalamount+'</td>'
                            table +='</tr>';
                        }
                        $('#popupTable').html(table);
                        $('#purchaseDetailsModal').show();
                    }
                }
            });
        }

        $('#purchaseBody').on('click','.deleteRecoard',function(){
            var purchaseid = $(this).attr('data-id');
            purchase_delete(purchaseid)
        });

        function purchase_delete(purchaseid)
        {
            $.ajax({
                method:'POST',
                url:'purchase_data.php',
                dataType:'json',
                data:{purchaseid:purchaseid , 'action':'purchase_Deleted'},
                success:function(data)
                {
                    var jsonData = JSON.stringify(data);
                    var resultdata = jQuery.parseJSON(jsonData);

                    if(resultdata.status == 1)
                    {
                        alert(resultdata.message);
                        purchase_master_fetch()
                    }
                    else
                    {
                        alert(resultdata.message);
                    }
                }
            });
        }

        $('#purchaseBody').on('click', '.editRecoard', function () {
        var purchaseid = $(this).data("id");
        $('#purchaseid').val(purchaseid);
        purchase_edit(purchaseid);
        });

        function purchase_edit(purchaseid) 
        {
            $.ajax({
                method: 'POST',
                //url: 'purchase_edit.php',
                url:'purchase_data.php',
                dataType: 'json',
                data: { purchaseid: purchaseid , 'action':'purchase_edit'},
                success: function (data) 
                {
                    var jsonData = JSON.stringify(data);
                    var resultdata = jQuery.parseJSON(jsonData);

                    if (resultdata.status == 1) 
                    {
                            var table = '';
                            for (var i in resultdata.categorydata) 
                            {
        
                                        var tblcatid = resultdata.categorydata[i].catid;
                                        var tblcatname = resultdata.categorydata[i].catname;
                                        var tblsubcatid = resultdata.categorydata[i].subcatid;
                                        var tblsubcatname = resultdata.categorydata[i].subcatname;
                                        var tblitemid = resultdata.categorydata[i].itemid;
                                        var tblitemname = resultdata.categorydata[i].itemname;
                                        var tblitemamount = resultdata.categorydata[i].itemamount;
                                        var tblquantity = resultdata.categorydata[i].quantity;
                                        var tbltotalamount = resultdata.categorydata[i].totalamount;


                                        table += '<tr>';
                                        table += '<td><input type="hidden" id="tblcatid" name="tblcatid[]" value="'+tblcatid+'" class="tblcatid"><input type="hidden" id="tblcatname" name="tblcatname[]" value="'+tblcatname+'">' + tblcatname + '</td>';
                                        table += '<td><input type="hidden" id="tblsubcatid" name="tblsubcatid[]" value="'+tblsubcatid+'" class="tblsubcatid"><input type="hidden" id="tblsubcatname" name="tblsubcatname[]" value="'+tblsubcatname+'">' + tblsubcatname + '</td>';

                                        table += '<td><input type="hidden" id="tblitemid" name="tblitemid[]" value="'+tblitemid+'" class="tblitemid"><input type="hidden" id="tblitemname" name="tblitemname[]" value="'+tblitemname+'">' + tblitemname + '</td>';
                                        table += '<td><input type="text" id="tblitemamount" name="tblitemamount[]" value="'+tblitemamount+'" class="tblitemamount"></td>';
                                        table += '<td><input type="text" id="tblquantity" name="tblquantity[]" value="'+tblquantity+'" class="tblquantity"></td>';
                                        table += '<td><input type="hidden" id="tblquantity" name="tblquantity[]" value="'+tbltotalamount+'" class="tbltotalamount">'+tbltotalamount+'</td>';
                                        table += '<td><button class="removeRow" onclick="removeRow(this)">❎</button></td>'
                                        table += '</tr>';

                            }

                                $('#gridBody').html(table);
                                $('#id').val(resultdata.id);
                                $('#purchaseid').val(resultdata.purchaseid)
                                $('#purchasename').val(resultdata.purchasename);       
                                showupdatebutton()
                    }
                }
            });
        }
        function showsubmitbutton()
        {
            $('#submit').show();
            $('#updateId').hide();
            $('#id').val('');
            $('#purchasename').val('');
            $('#purchaseid').val('');
            $('#catid').val('');
            $('#catname').val('');
            $('#subcatid').val('');
            $('#subcatname').val('');
            $('#itemid').val('');
            $('#itemname').val('');
            $('#itemamount').val('');
            $('#quantity').val('');
            $('#totalamount').val('');
        }

        function showupdatebutton()
        {
            $('#submit').hide();
            $('#updateId').show();
        }
                   

    $('#mypage').validate({
        rules:{
            purchasename:{
                required:true
            }
        },
        messages:{
            purchasename:{
                required:'Purchase name is required field!'
            }
        },
        submitHandler:function(form)
        {
            var formdata = new FormData(form);
            if ($('#purchaseid').val() == '') 
            {
                url = "purchase_data.php";
                formdata.append('action','purchase_insert')
            }
            else
            {
                url = "purchase_data.php";
                formdata.append('action','purchase_update')
            }
            
           $.ajax({
                method:'POST',
                url:url,
                data:formdata,
                processData : false,
                contentType:false,
                dataType:'json',
                success:function(data)
                {
                    var jsonData = JSON.stringify(data)
                    var resultdata = jQuery.parseJSON(jsonData);

                    if(resultdata.status == 1)
                    {
                        alert(resultdata.message);
                        showsubmitbutton()
                    }
                    else
                    {
                        alert(resultdata.message);
                    }
                }
           })
        }
    })
    </script>
</body>
</html>