<?php
session_start();

require_once 'core/User.php';
require_once 'core/config.php';
$user_home = new User();

?>
<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="css/bootstrap-datetimepicker.min.css" />
    <title>Hours Tracker</title>
</head>
<body>
<br>

<nav class="navbar fixed-top navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="index.php">Hours Tracker</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link" href="index.php">Home <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="fill.php">Fill Hours</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="plus.php">Plus Hours</a>
            </li>
        </ul>
    </div>
</nav>

    <div class="container-responsive" >
        <br><br><br>
        <h1 class="text-center">Plus Hour 2018</h1>
        <br><br>
        <div class="row">
            <div class="col-md-1"></div>
            <div class="col-md-10">
                <table class="table">
            <thead class="thead-dark">
            <tr>
                <th class="text-center" scope="col">Month</th>
                <th class="text-center" scope="col">Total Worked Hours in the Month</th>
                <th class="text-center" scope="col">Should Work Hours</th>
                <th class="text-center" scope="col">Worked Days</th>
                <th class="text-center" scope="col">Plus/Min Hours</th>
            </tr>
            </thead>
            <tbody>
        <?php
        $gran_total_days = 0;
        $gran_total_plus_min = 0;
        $gran_total_worked_hours = 0;
        $gran_total_should_work_hours = 0;
        for ($x = 1; $x <= 12; $x++) {
            $stmt_table = $user_home->runQuery(
                "SELECT * FROM hours WHERE YEAR(date) = 2018 
                                              AND MONTH(date) = $x"
            );
            $stmt_table->execute();
            $total_plus_min =0;
            $total =0;
            $total_day=0;
            $should_work_hours =0;
            $worked_days=0;
            while($row_table = $stmt_table->fetch(PDO::FETCH_ASSOC)):

                $start_hours = $row_table['start_hours'];
                $end_hours = $row_table['end_hours'];
                $pause = $row_table['pause'];

                $start_hour_array = explode(":", $start_hours);
                $start_num = $user_home->convertHours($start_hour_array[0], $start_hour_array[1]);
                $end_hour_array = explode(":", $end_hours);
                $end_num = $user_home->convertHours($end_hour_array[0], $end_hour_array[1]);

                $hoursPause = $pause / 60;

                $total_day= $end_num - $start_num - $hoursPause;

                $day = $total_day - WORKING_DAY_HOURS;

                $total_plus_min += $day;

                $total +=$total_day;
                $worked_days++;

                $should_work_hours += WORKING_DAY_HOURS;

                $total_plus_min = number_format($total_plus_min, 2, '.', '');
                $total = number_format($total, 2, '.', '');

                endwhile;

                if($x == 1){
                    $month = "January";
                }
                elseif($x == 2) {
                    $month = "February";
                }
                elseif($x == 3) {
                    $month = "March";
                }
                elseif($x == 4) {
                    $month = "April";
                }
                elseif($x == 5) {
                    $month = "May";
                }
                elseif($x == 6) {
                    $month = "June";
                }
                elseif($x == 7) {
                    $month = "Juli";
                }
                elseif($x == 8) {
                    $month = "August";
                }
                elseif($x == 9) {
                    $month = "September";
                }
                elseif($x == 10) {
                    $month = "October";
                }
                elseif($x == 11) {
                    $month = "November";
                }
                elseif($x == 12) {
                    $month = "December";
                }
                    ?>

                <tr>
                    <td class="text-center"><?php echo $month ;?></td>
                    <td class="text-center"><?php echo $total;?></td>
                    <td class="text-center"><?php echo $should_work_hours;?></td>
                    <td class="text-center"><?php echo $worked_days;?></td>
                    <td class="text-center"><?php echo $total_plus_min;?></td>
                </tr>

            <?php
            $gran_total_days += $worked_days;
            $gran_total_plus_min += $total_plus_min;
            $gran_total_worked_hours += $total;
            $gran_total_should_work_hours += $should_work_hours;
        }
        ?>
        <tr>
            <td class="text-center"><strong>TOTAL</strong>  </td>
            <td class="text-center"><?php echo $gran_total_worked_hours;?></td>
            <td class="text-center"><?php echo $gran_total_should_work_hours;?></td>
            <td class="text-center"><?php echo $gran_total_days;?></td>
            <td class="text-center"><?php echo $gran_total_plus_min;?></td>
        </tr>
            </tbody>
        </table>
            </div>
            <div class="col-md-1"></div>
        </div>
    </div>
<br><br>

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.3.1.js" ></script>
<script type="text/javascript" src="js/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
<script type="text/javascript" src="js/bootstrap-datetimepicker.min.js"></script>
<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
<script>
    //calls the function from the dataTable with the id from the table
    $(document).ready(function(){
        $('#employee_data').DataTable({
            "order": [[ 2, 'dsc' ]]
        });
    });
</script>
</body>
</html>