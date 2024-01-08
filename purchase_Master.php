<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Master</title>
    <link rel="stylesheet" href="bootstrap.min.css">
    <style>
        label.error{
            color:#f00;
        }
        table{
            border:2px solid black;
        }
        tr ,td{
            border:2px solid black;
        }
        .custom-width {
            max-width: 800px; 
            
        }

    </style>
</head>
<body>
    <center>
        <h2>Purchase Master</h2>

        <form action="" metehod="POST" id="mypage">

            <label for="purchasename" >purchasename</label><br>
            <input type="text" name="purchasename" id="purchasename">
            <hr>
            <h2>Details</h2>
            <label for="Category">Category</label>
            <select name="catid" id="catid">
                <option value="" selected>Select Category</option>
            </select>

            <label for="Subcategory">Subcategory</label>
            <select name="subcatid" id="subcatid">
                <option value="" selected>Select Subcategory</option>
            </select>

            <label for="Item">Item</label>
            <select name="itemid" id="itemid">
                <option value="" selected>Select Item</option>
            </select>

            <label for="itemamount">itemamount</label>
            <input type="text" name="itemamount" id="itemamount">

            <label for="quantity">quantity</label>
            <input type="text" name="quantity" id="quantity">

            <input type="hidden" name="totalamount" id="totalamount">
            <input type="button" value="Add" id="addData" name="addData">

            <h2>Grid Details</h2>
            <table>
                <thead>
                    <tr>
                        <td>Category</td>
                        <td>Subcategory</td>
                        <td>Item</td>
                        <td>Item Amount</td>
                        <td>Quantity</td>
                        <td>Total Amount</td>
                    </tr>
                </thead>

                <tbody id="gridBody">

                </tbody>
            </table>
            <br><br>
            <input type="submit" value="submit" id="submit" name="submit">
            <input type="submit" value="Update" id="updateId" name="updateId">
            <input type="reset" value="Reset">
            <input type="hidden" name="purchaseid" id="purchaseid">
        </form>

    </center>

    <center>
        <h2>Purchase Details :</h2>
        <table>
            <thead>
                <tr>
                    <td>PurchaseName</td>
                    <td colspan="2">Action</td>
                </tr>
            </thead>
            <tbody id="purchaseBody">

            </tbody>
        </table>
    </center>


<div class="modal fade" id="purchaseDetails" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog custom-width" role="document">
    <div class="modal-content">
      <div class="modal-body">
      <table id="modalPopupTable" class="table">
      </table>
      <div id="purchaseDetails">
        <h2>Purchase Master Details<span id="purchasenameBody"></span></h2>
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

    <script src="jquery.min.js"> </script>  
    <script src="jquery.validate.min.js"> </script>
    <script src="popper.min.js"></script>
    <script src="bootstrap.min.js"></script>

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
                    var jsonData = JSON.stringify(data);
                    var resultdata = jQuery.parseJSON(jsonData);

                    if(resultdata.status == 1)
                    {
                        var table = '';
                        table += '<option value = "">Select Category</option>';
                        for(var i in resultdata.categorydata)
                        {
                            table += '<option value = "'+resultdata.categorydata[i].id+'">'+resultdata.categorydata[i].name+'</option>';
                        }
                        $('#catid').html(table);
                        SubCategory_fetch()
                    }
                }
            });
        }

        $('#catid').change(function(){
            SubCategory_fetch();
        });

        function SubCategory_fetch()
        {
            var catid = $("#catid").val();
            $.ajax({
                method:'POST',
                url:'purchase_data.php',
                data:{catid:catid,'action':'SubCategory_Fetch'},
                dataType:'json',
                success:function(data)
                {
                    var jsonData = JSON.stringify(data);
                    var resultdata = jQuery.parseJSON(jsonData);

                    if(resultdata.status == 1)
                    {
                        var table = '';
                        table += '<option value = "">Select Subcategory</option>';
                        for(var i in resultdata.categorydata)
                        {
                            table += '<option value="'+resultdata.categorydata[i].id+'">'+resultdata.categorydata[i].subcatname+'</option>'
                        }
                        $('#subcatid').html(table);
                        item_Fetch()
                    }
                }
            });
        }

        $('#subcatid').change(function(){
            item_Fetch()
        });

        $('#itemid').change(function(){
            var item = $('#itemid option:selected');
            var itemamount = item.attr('itemamount');
            $('#itemamount').val(itemamount)
        });

        function item_Fetch()
        {
            var subcatid = $('#subcatid').val()
            $.ajax({
                method:'POST',
                url:'purchase_data.php',
                data:{subcatid:subcatid,'action':'item_Fetch'},
                dataType:'json',
                success:function(data)
                {
                    var jsonData = JSON.stringify(data);
                    var resultdata = jQuery.parseJSON(jsonData);

                    if(resultdata.status == 1)
                    {
                        var table = '';
                        table += '<option value = "">Select Item</option>';
                        for(var i in resultdata.categorydata)
                        {
                            table += '<option value= "' +resultdata.categorydata[i].id+ '" itemamount="' +resultdata.categorydata[i].itemamount+ '">'+resultdata.categorydata[i].itemname+'</option>';
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
            var totalamount = calculate(itemamount,quantity);

            if(!catid || !subcatid || !itemid || !itemamount || !quantity)
            {
                alert('Please fill all the field')
            }
            else
            {
                var raw = findData(catid,subcatid,itemid)

                    if(raw)
                    {
                        raw.remove();
                    }
                    var table = '';
                    table += '<tr>';
                    table += '<td><input type="hidden" id="tblcatid" name="tblcatid[]" class="tblcatid" value="'+catid+'"><input type ="hidden" id="tblcatname" name="tblcatname[]" value="'+catname+'">'+catname+'</td>'
                    table += '<td><input type="hidden" id="tblsubcatid" name="tblsubcatid[]" class="tblsubcatid" value="'+subcatid+'"><input type ="hidden" id="tblsubcatname" name="tblsubcatname[]" value="'+subcatname+'">'+subcatname+'</td>'
                    table += '<td><input type="hidden" id="tblitemid" name="tblitemid[]" class="tblitemid" value="'+itemid+'"><input type ="hidden" id="tblitemname" name="tblitemname[]" value="'+itemname+'">'+itemname+'</td>'
                    table += '<td><input type="text" name="tblitemamount[]" id="tblitemamount" class="tblitemamount" value="'+itemamount+'"></td>'
                    table += '<td><input type="text" name="tblquantity[]" id="tblquantity" class="tblquantity" value="'+quantity+'"></td>'
                    table += '<td><input type="hidden" name="tbltotalamount[]" class="tbltotalamount" value="'+totalamount+'"><span class="tbltotalamount">'+totalamount+'</span></td>';
                    table += '<td><button class="removeRow" onclick="removeRow(this)">X</button></td>'
                    table += '</tr>';

                    $('#gridBody').append(table)
                    $('#catid').val('');
                    $('#subcatid').val('');
                    $('#itemid').val('');
                    $('#itemamount').val('');
                    $('#quantity').val('');
            }

        });

        function findData(catid,subcatid,itemid)
        {
            var row = null;

            $('#gridBody tr').each(function(){
                var rowCatid = $(this).find('.tblcatid').val();
                var rowSubcatid = $(this).find('.tblsubcatid').val();
                var rowItemid = $(this).find('.tblitemid').val();

                if(rowCatid == catid && rowSubcatid == subcatid && rowItemid == itemid)
                {
                    row = $(this);
                    return false;
                }
            });
            return row;
        }

        function calculate(itemamount,quantity)
        {
            return (parseFloat(itemamount) * parseFloat(quantity)).toFixed(2);
        }

        $('#gridBody').on('input', '.tblitemamount, .tblquantity', function() {
           // var row = $(this).closest('tr');
            var row = $(this).parent().parent() 
            var itemamount = row.find('.tblitemamount').val() || 0;
            var quantity = row.find('.tblquantity').val() || 0;
            var totalamount = calculate(itemamount, quantity);
            row.find('.tbltotalamount').val(totalamount);
            row.find('.tbltotalamount').text(totalamount);
            
        });

        function removeRow(button) 
        {
            $(button).parent().parent().remove();
        }


        function purchase_master_fetch()
        {
            $.ajax({
                method:'POST',
                url:'purchase_data.php',
                data:{'action':'purchase_master_fetch'},
                dataType:'json',
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
                            table += '<td><a class="viewData" data-id="'+resultdata.categorydata[i].id+'">'+resultdata.categorydata[i].purchasename+'</a></td>'
                            table += '<td><a href="javascript:void(0)" data-id="'+resultdata.categorydata[i].id+'" class="EditRecoard">Edit</a></td>'
                            table += '<td><a href="javascript:void(0)" data-id="'+resultdata.categorydata[i].id+'" class="DeleteRecoard">Delete</a></td>'
                            table += '</tr>';
                        }
                        $('#purchaseBody').html(table)
                    }
                }
            })
        }

        $('#purchaseBody').on('click','.viewData',function(){
            var purchaseid = $(this).attr('data-id');
            purchase_detail_fetch(purchaseid)
            $('#purchaseDetails').modal('show');
            console.log("purchase id " + purchaseid);
        });

        function purchase_detail_fetch(purchaseid)
        {
            $.ajax({
                method:'POST',
                url:'purchase_data.php',
                data:{purchaseid:purchaseid,'action':'purchase_detail_fetch'},
                dataType:'json',
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
                            table += '</tr>';
                        }
                        $('#popupTable').html(table);
                        $('#purchaseDetails').show();
                    }
                }
            })
        }

        $('#purchaseBody').on('click','.DeleteRecoard',function(){
            var purchaseid = $(this).attr('data-id');
            purchase_delete(purchaseid)
        })

        function purchase_delete(purchaseid)
        {
            $.ajax({
                method:'POST',
                url:'purchase_data.php',
                data:{purchaseid:purchaseid,'action':'purchase_delete'},
                dataType:'json',
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
            })
        }

        $('#purchaseBody').on('click','.EditRecoard',function(){
            var purchaseid = $(this).attr('data-id');
            $('#purchaseid').val(purchaseid);
            purchase_edit(purchaseid)
        })

        function purchase_edit(purchaseid)
        {
            $.ajax({
                method:'POST',
                url:'purchase_data.php',
                data:{purchaseid:purchaseid, 'action':'purchase_edit'},
                dataType:'json',
                success:function(data)
                {
                    var jsonData = JSON.stringify(data);
                    var resultdata = jQuery.parseJSON(jsonData);

                    if(resultdata.status == 1)
                    {
                        var table = '';
                        for(var i in resultdata.categorydata)
                        {
                            var tblcatid = resultdata.categorydata[i].catid;
                            var tblcatname = resultdata.categorydata[i].catname;
                            var tblsubcatid = resultdata.categorydata[i].subcatid;
                            var tblsubcatname = resultdata.categorydata[i].subcatname;
                            var tblitemid = resultdata.categorydata[i].itemid;
                            var tblitemname = resultdata.categorydata[i].itemname;
                            var tblitemamount = resultdata.categorydata[i].itemamount;
                            var tblquantity = resultdata.categorydata[i].quantity;
                            var tbltotalamount= resultdata.categorydata[i].totalamount;


                            table += '<tr>';
                            table += '<td><input type="hidden" id="tblcatid" name="tblcatid[]" class="tblcatid" value="'+tblcatid+'"><input type ="hidden" id="tblcatname" name="tblcatname[]" value="'+tblcatname+'">'+tblcatname+'</td>'
                            table += '<td><input type="hidden" id="tblsubcatid" name="tblsubcatid[]" class="tblsubcatid" value="'+tblsubcatid+'"><input type ="hidden" id="tblsubcatname" name="tblsubcatname[]" value="'+tblsubcatname+'">'+tblsubcatname+'</td>'
                            table += '<td><input type="hidden" id="tblitemid" name="tblitemid[]" class="tblitemid" value="'+tblitemid+'"><input type ="hidden" id="tblitemname" name="tblitemname[]" value="'+tblitemname+'">'+tblitemname+'</td>'
                            table += '<td><input type="text" name="tblitemamount[]" id="tblitemamount" class="tblitemamount" value="'+tblitemamount+'"></td>'
                            table += '<td><input type="text" name="tblquantity[]" id="tblquantity" class="tblquantity" value="'+tblquantity+'"></td>'
                            table += '<td><input type="hidden" name="tbltotalamount[]" class="tbltotalamount" value="'+tbltotalamount+'"><span class="tbltotalamount">'+tbltotalamount+'</span></td>';
                            table += '<td><button class="removeRow" onclick="removeRow(this)">X</button></td>'
                            table += '</tr>';
                        }
                        $('#gridBody').html(table);
                        $('#id').val(resultdata.id);
                        $('#purchasename').val(resultdata.purchasename);
                        showupdatebutton()
                    }
                }
            });
        }

        function showsubmitbutton()
        {
            $('#submit').show()
            $('#updateId').hide()
            $('#id').val('');
            $('#purchaseid').val('');
            $('#purchasename').val('');
            $('#catid').val('');
            $('#subcatid').val('');
            $('#itemid').val('');
            $('#itemamount').val('');
            $('#quantity').val('');
            $('#totalamount').val('');
        }

        function showupdatebutton()
        {
            $('#submit').hide()
            $('#updateId').show()
        }

        $('#mypage').validate({
            rules:{
                purchasename:{
                    required:true
                }
            },
            messages:{
                purchasename:{
                    required:"Purchasename is required field!"
                }
            },
            submitHandler:function(form)
            {
                var rowData = $('#gridBody tr').length;
                if(!rowData)
                {
                    alert('Please fill all the field')
                }
                else
                {
                    var formdata = new FormData(form);
                    if($('#purchaseid').val() == '')
                    {
                        url = "purchase_data.php";
                        formdata.append('action','purchase_insert');
                    }
                    else
                    {
                        url = "purchase_data.php";
                        formdata.append('action','purchase_update');
                    }
                
                    $.ajax({
                        method:'POST',
                        url:url,
                        data:formdata,
                        dataType:'json',
                        processData:false,
                        contentType:false,
                        success:function(data)
                        {
                            var jsonData = JSON.stringify(data);
                            var resultdata = jQuery.parseJSON(jsonData);

                            if(resultdata.status == 1)
                            {
                                alert(resultdata.message)
                                purchase_master_fetch()
                                showsubmitbutton()
                                $('#gridBody').empty();
                            }
                            else
                            {
                                alert(resultdata.message)
                            }
                        }
                    })
                }
            }
        })
        
    </script>
</body>
</html>
