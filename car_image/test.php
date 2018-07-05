<?php
$host="localhost";
$user="root";
$password="";
$db="vtechnol_naqil";
mysql_connect($host,$user,$password) or die ("Couldn't connect with database!");
mysql_select_db($db);
if(isset($_REQUEST['save']))
{
    $book_id=$_REQUEST['book_id'];
    $driver_id=$_REQUEST['driver_id'];
    $status=$_REQUEST['status'];
    $status_code=$_REQUEST['status_code'];
    $upd="UPDATE bookingdetails set status='$status',status_code='$status_code',assigned_for='$driver_id' WHERE id='$book_id'";
    $rs=mysql_query($upd);
    if($rs)
    {
        echo "update sucessfully";
    }
    else
    {
        echo"not update record";
    }

}
?>
<html>
<body>
<form method="post">
<h1>Driver Test</h1>
<table>
    <tr>
        <td>
            <input type="text" name="book_id" placeholder="book_id">
        </td>
    <td>
        <input type="text" name="driver_id" placeholder="driver id">
    </td>
        <td>
            <select name="status">
                <option>Select</option>
                <option value="1">pending</option>
                <option value="2">waiting</option>
                <option value="3">accepted</option>
                <option value="4">user-cancelled
                </option>
                <option value="5">driver-cancelled</option>
                <option value="6">river-unavailable</option>
                <option value="7">driver-arrived</option>
                <option value="8">on-trip</option>
                <option value="9">completed</option>

            </select>
        </td>
        <td>
            <input type="text" name="status_code"/>
        </td>
        <td>
            <input type="submit" name="save">
        </td>
    </tr>

</table>
    </form>
</body>
</html>
