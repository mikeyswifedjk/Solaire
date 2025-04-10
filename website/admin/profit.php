<?php  
session_start();  
if(!isset($_SESSION["user"]))
{
 header("location:index.php");
}
?> 

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
      <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>AMETHYST HOTEL</title>
	<!-- Bootstrap Styles-->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
     <!-- FontAwesome Styles-->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    
	<link rel="stylesheet" href="assets/css/morris.css">
	<script src="assets/js/jquery.min.js"></script>
	<script src="assets/js//raphael-min.js"></script>
	<script src="assets/js/morris.min.js"></script>

   
        <!-- Custom Styles-->
    <link href="assets/css/custom-styles.css" rel="stylesheet" />
     <!-- Google Fonts-->
   <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
     <!-- TABLE STYLES-->
    <link href="assets/js/dataTables/dataTables.bootstrap.css" rel="stylesheet" />
</head>
<body>
    <div id="wrapper">
        
        <nav class="navbar navbar-default top-navbar" role="navigation">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="home.php"><?php echo $_SESSION["user"]; ?> </a>
            </div>

            <ul class="nav navbar-top-links navbar-right">
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false">
                        <i class="fa fa-user fa-fw"></i> <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="usersetting.php"><i class="fa fa-user fa-fw"></i> User Profile</a>
                        </li>
                        <li><a href="settings.php"><i class="fa fa-gear fa-fw"></i> Settings</a>
                        </li>
                        <li class="divider"></li>
                        <li><a href="logout.php"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                        </li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
                <!-- /.dropdown -->
            </ul>
        </nav>
        <!--/. NAV TOP  -->
        <nav class="navbar-default navbar-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav" id="main-menu">

                    <li>
                        <a href="home.php"><i class="fa fa-dashboard"></i> Status</a>
                    </li>
                    <li>
                        <a  href="messages.php"><i class="fa fa-desktop"></i> Maintenance</a>
                    </li>
					<li>
                        <a href="roombook.php"><i class="fa fa-bar-chart-o"></i>Room Booking</a>
                    </li>
                    <li>
                        <a  href="payment.php"><i class="fa fa-qrcode"></i> Payment</a>
                    </li>
					 <li>
                        <a class="active-menu" href="profit.php"><i class="fa fa-qrcode"></i> Profit</a>
                    </li>
                    
                    <li>
                        <a href="logout.php" ><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                    </li>
                    

                    
            </div>

        </nav>
        <!-- /. NAV SIDE  -->
        <div id="page-wrapper" >
            <div id="page-inner">
			 <div class="row">
                    <div class="col-md-12">
                        <h1 class="page-header">
                           Profit Details<small> </small>
                        </h1>
                    </div>
                </div> 
                 <!-- /. ROW  -->
				 
				 
            <div class="row">
			
            <?php 
//index.php
//$connect = mysqli_connect("localhost", "root", "", "hotel");
include('db.php');

$query = "SELECT b.*, r.RoomType AS CategoryName, r.RoomName, c.Price AS RoomPrice
          FROM tblbooking b
          INNER JOIN tblroom r ON b.RoomId = r.ID
          INNER JOIN tblcategory c ON r.RoomType = c.ID";
$result = mysqli_query($con, $query);
$chart_data = '';
$tot = 0;

while ($row = mysqli_fetch_array($result)) {
    // Assuming that 'roomId' is the field in tblbooking that references 'ID' in tblroom
    $roomId = $row['RoomId'];
    
    // Fetch the price from tblcategory using the roomId
    $priceQuery = "SELECT Price FROM tblcategory WHERE ID = (SELECT RoomType FROM tblroom WHERE ID = $roomId)";
    $priceResult = mysqli_query($con, $priceQuery);
    $priceRow = mysqli_fetch_array($priceResult);
    $roomPrice = $priceRow['Price'];

    $chart_data .= "{ date:'" . $row["CheckinDate"] . "', profit:" . $roomPrice * 10 / 100 . "}, ";
    $tot = $tot + $roomPrice * 10 / 100;
}
$chart_data = substr($chart_data, 0, -2);
?>

				 
				<br>
				<br>
				<br>
				<br><div id="chart"></div>
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
											<th>Name</th>
                                            <th>Room Id</th>
                                            <th>Room Name</th>
                                            <th>Check in</th>
											<th>Check out</th>
											<th>Down Payment</th>
											<th>Room Price </th>
											<th>Gr.Total</th>
											<th>Profit</th>
											
                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
									<?php
										
                                        $sql = "SELECT b.*, u.FullName AS UserName, r.RoomType AS CategoryName, r.RoomName, c.Price AS RoomPrice
                                        FROM tblbooking b
                                        INNER JOIN tbluser u ON b.UserID = u.ID
                                        INNER JOIN tblroom r ON b.RoomID = r.ID
                                        INNER JOIN tblcategory c ON r.RoomType = c.ID";

                                        $re = mysqli_query($con, $sql);
                                        while ($row = mysqli_fetch_array($re)) {
                                            $id = $row['ID'];
                                            $fullName = $row['UserName'];
                                            $roomType = $row['CategoryName'];
                                            $roomName = $row['RoomName'];
                                            $checkinDate = $row['CheckinDate'];
                                            $checkoutDate = $row['CheckoutDate'];
                                            $downPay = $row['downPay'];
                                            $price = $row['RoomPrice'];
    
                                            if ($id % 2 == 1) {
                                                echo "<tr class='gradeC'>
                                                        <td>$fullName</td>
                                                        <td>$roomType</td>
                                                        <td>$roomName</td>
                                                        <td>$checkinDate</td>
                                                        <td>$checkoutDate</td>
                                                        <td>$downPay</td>
                                                        <td>$price</td>
                                                        <td>$price</td>
                                                        <td><a href='print.php?pid=$id' class='btn btn-primary'><i class='fa fa-print'></i> Print</a></td>
                                                    </tr>";
                                            } else {
                                                echo "<tr class='gradeU'>
                                                        <td>$fullName</td>
                                                        <td>$roomType</td>
                                                        <td>$roomName</td>
                                                        <td>$checkinDate</td>
                                                        <td>$checkoutDate</td>
                                                        <td>$downPay</td>
                                                        <td>$price</td>
                                                        <td>$price</td>
                                                        <td><a href='print.php?pid=$id' class='btn btn-primary'><i class='fa fa-print'></i> Print</a></td>
                                                    </tr>";
                                            }
                                        }
										
									?>
                                        
                                    </tbody>
                                </table>
                            </div>
                            
                        </div>
                    </div>
                    <!--End Advanced Tables -->
                </div>
            </div>
                <!-- /. ROW  -->
            
                </div>
               
            </div>
        
               
    </div>
             <!-- /. PAGE INNER  -->
            </div>
         <!-- /. PAGE WRAPPER  -->
     <!-- /. WRAPPER  -->
    <!-- JS Scripts-->
    <!-- jQuery Js -->
    <script src="assets/js/jquery-1.10.2.js"></script>
      <!-- Bootstrap Js -->
    <script src="assets/js/bootstrap.min.js"></script>
    <!-- Metis Menu Js -->
    <script src="assets/js/jquery.metisMenu.js"></script>
     <!-- DATA TABLE SCRIPTS -->
    <script src="assets/js/dataTables/jquery.dataTables.js"></script>
    <script src="assets/js/dataTables/dataTables.bootstrap.js"></script>
        <script>
            $(document).ready(function () {
                $('#dataTables-example').dataTable();
            });
    </script>
         <!-- Custom Js -->
    <script src="assets/js/custom-scripts.js"></script>
    
   
</body>
</html>
<script>
Morris.Bar({
 element : 'chart',
 data:[<?php echo $chart_data; ?>],
 xkey:'date',
 ykeys:['profit'],
 labels:['Profit'],
 hideHover:'auto',
 stacked:true
});
</script>