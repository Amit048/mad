<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Session Demo</title>

    <style>
    table {
        border: 2px solid black;
    }

    tr,
    td {
        border: 2px solid black;
    }

    label.error {
        color: #f00;
    }

    h2 {
        color: green;
    }
    </style>
</head>

<body>
    <center>
        <h2>Session Demo</h2>
        <hr><br>
        <form action="" method="POST" id="mypage">
            <table>
                <tr>
                    <td>
                        Name: <input type="text" name="name" id="name">
                    </td>
                </tr>
                <tr>
                    <td>
                        Contact:<input type="text" name="contact" id="contact">
                    </td>
                </tr>
                <tr>
                    <td>
                        Email:<input type="text" name="email" id="email">
                    </td>
                </tr>
                <tr>
                    <td>
                        Category <select id="catid" name="catid">
                            <option value="" selected>Select Category</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        Subcategory <select name="subcatid" id="subcatid">
                            <option value="" selected>Select Subcategory</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        Item <select name="itemid" id="itemid">
                            <option value="" selected>Select Item</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type="submit" value="Add" id="add" name="add">
                    </td>
                </tr>
            </table>
        </form>
    </center>

    <center>
        <hr>
        <h2>Showing Session</h2>
        <div id="sessionData">

        </div>

    </center>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>

    <script>
    $(document).ready(function() {
        category();
        session_demo_fetch();
    });

    function category() {
        $.ajax({
            method: 'POST',
            url: 'category_fetch.php',
            dataType: 'json',
            success: function(data) {
                var jsonData = JSON.stringify(data);
                var resultdata = jQuery.parseJSON(jsonData);

                if (resultdata.status == 1) {
                    var table = '';
                    table += '<option value = "">Select Category</option>';
                    for (var i in resultdata.categorydata) {
                        table += '<option value = "' + resultdata.categorydata[i].id + '">' + resultdata
                            .categorydata[i].name + '</option>';
                    }
                    $('#catid').html(table);
                    subcategory()
                }
            }
        });
    }

    $('#catid').change(function() {
        subcategory();
    });

    function subcategory() {
        var catid = $('#catid').val();
        $.ajax({
            method: 'POST',
            url: 'SubCategory_Fetch.php',
            dataType: 'json',
            data: {
                catid: catid,
                flag: 1
            },
            success: function(data) {
                var jsonData = JSON.stringify(data);
                var resultdata = jQuery.parseJSON(jsonData);

                if (resultdata.status == 1) {
                    var table = '';
                    table += '<option value = "">Select SubCategory</option>';
                    for (var i in resultdata.categorydata) {
                        table += '<option value = "' + resultdata.categorydata[i].id + '">' + resultdata
                            .categorydata[i].subcatname + '</option>';
                    }
                    $('#subcatid').html(table);
                    Itemaster()
                } else {
                    alert(resultdata.message);
                }
            }
        });
    }

    $('#subcatid').change(function() {
        Itemaster();
    });

    function Itemaster() {
        var subcatid = $('#subcatid').val();
        $.ajax({
            method: 'POST',
            url: 'item_Fetch.php',
            dataType: 'json',
            data: {
                subcatid: subcatid,
                formPurchaseItemMaster: 1
            },
            success: function(data) {
                var jsonData = JSON.stringify(data);
                var resultdata = jQuery.parseJSON(jsonData);

                if (resultdata.status == 1) {
                    var table = '';
                    table += '<option value = "">Select Item</option>';
                    for (var i in resultdata.categorydata) {
                        table += '<option value="' + resultdata.categorydata[i].id + '">' + resultdata
                            .categorydata[i].itemname + '</option>';
                    }
                    $('#itemid').html(table);
                }
            }
        });
    }



    function session_demo_fetch() {
        $.ajax({
            method: 'POST',
            //url: 'session_demo_fetch.php',
            url: 'session.php',
            dataType: 'json',
            data: {
                'action': 'SessionFetch'
            },
            success: function(data) {
                var jsonData = JSON.stringify(data);
                var resultdata = jQuery.parseJSON(jsonData);

                if (resultdata.status == 1) {
                    var dataRow = '';
                    dataRow += '<h4><button onclick="removeSession(this)">Remove Session</button></h4>';
                    dataRow += '<h4>Name: ' + resultdata.name + '</h4>';
                    dataRow += '<h4>Contact: ' + resultdata.contact + '</h4>';
                    dataRow += '<h4>Email: ' + resultdata.email + '</h4>';
                    dataRow += '<h4>Category: ' + resultdata.catname + '</h4>';
                    dataRow += '<h4>Subcategory: ' + resultdata.subcatname + '</h4>';
                    dataRow += '<h4>Item: ' + resultdata.itemname + '</h4>';


                    $('#sessionData').html(dataRow);
                    $('#name').val('');
                    $('#contact').val('');
                    $('#email').val('');
                    $('#catid').val('');
                    $('#subcatid').val('');
                    $('#itemid').val('');
                } else {
                    alert(resultdata.message);
                }
            }
        });
    }

    function removeSession(button) {
        $.ajax({
            method: 'POST',
            url: 'session.php',
            data: {
                'action': 'RemoveSession'
            },
            dataType: 'json',
            success: function(data) {
                var jsonData = JSON.stringify(data);
                var resultdata = jQuery.parseJSON(jsonData);

                if (resultdata.status == 1) {
                    $('#sessionData').html('');
                    alert(resultdata.message);
                } else {
                    alert(resultdata.message);
                }
            }
        })
    }

    $('#mypage').validate({
        rules: {
            name: {
                required: true
            },
            contact: {
                number: true,
                required: true
            },
            email: {
                required: true,
                email: true
            },
            catid: {
                required: true
            },
            subcatid: {
                required: true
            },
            itemid: {
                required: true
            }
        },
        messages: {
            name: {
                required: 'Name field is required!'
            },
            contact: {
                number: 'Contact must be a number',
                required: 'contact Field is requried!'
            },
            email: {
                required: 'Email field is required!',
                email: "Please enter a valid Email address"
            },
            catid: {
                required: 'Category field is required!'
            },
            subcatid: {
                required: 'SubCategory field is required!'
            },
            itemid: {
                required: 'Item field is required!'
            }
        },
        submitHandler: function(form) {
            alert('submitted');
            
                var name = $('#name').val();
                var contact = $('#contact').val();
                var email = $('#email').val();
                var catid = $('#catid').val();
                var catname = $('#catid option:selected').text();
                var subcatid = $('#subcatid').val();
                var subcatname = $('#subcatid option:selected').text();
                var itemid = $('#itemid').val();
                var itemname = $('#itemid option:selected').text();

               
                var formdata = new FormData(form);
                formdata.append('action', 'SessionAdd');

                    $.ajax({
                        method: 'POST',
                        url: 'session.php',
                        data: formdata,
                        dataType: 'json',
                        processData: false,
                        contentType: false,
                        success: function(data) {
                            var jsonData = JSON.stringify(data);
                            var resultdata = jQuery.parseJSON(jsonData);

                            if (resultdata.status == 1) {
                                session_demo_fetch();
                            } else {
                                alert(resultdata.message);
                            }
                        }
                    });
        }
    });
    </script>
</body>

</html>