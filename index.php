<?php
session_start();

require_once 'core/User.php';
require_once 'core/config.php';
$user_home = new User();

//delete hour
if(isset($_GET['delete'])){
    $id_delete = $_GET['delete'];
    $stmt_delete = $user_home->runQuery("DELETE FROM hours WHERE id=:uid");
    if($stmt_delete->execute(array(":uid" => $id_delete))){
        ?>
        <!--success message-->
        <script>
            alert('Succes deleted');
            window.location.href='index.php';
        </script>

        <?php
    }
    else{
        ?>
        <!--error message-->
        <script>
            alert('An error has occurred trying to delete the data');
            window.location.href='index.php';
        </script>

        <?php
    }

}
//edit user with id from GET
if(isset($_GET['edit'])){
    $hour_id = $_GET['edit'];
    $stmt_edit = $user_home->runQuery("SELECT * FROM hours WHERE id=:uid");
    $stmt_edit->execute(array(":uid"=>$hour_id));
    $row_edit = $stmt_edit->fetch(PDO::FETCH_ASSOC);

    //get the values to fill fields in the form edit
    $date = $row_edit['date'];
    $start_hours = $row_edit['start_hours'];
    $end_hours = $row_edit['end_hours'];
    $pause = $row_edit['pause'];
    $note = $row_edit['note'];

    //if clicks to edit hour, update in database
    if(isset($_POST['submit'])){
        $date = trim($_POST['date']);
        $start_hours = trim($_POST['start_hour']);
        $end_hours = trim($_POST['end_hour']);
        if(isset($_POST['pause']) && !empty($_POST['pause'])){
            $pause = trim($_POST['pause']);
        }
        else{
            $pause = 0;
        }
        if(isset($_POST['note']) && !empty($_POST['note'])){
            $note = trim($_POST['note']);
        }
        else{
            $note = "";
        }
        $msg = "";
        $msgHappy = "";
        //check if nothing is empty
        if(empty($date)) {
            $msg = "We need a Date";
        }
        else if(empty($start_hours)) {
            $msg = "We need a start hour";
        }
        else if(empty($end_hours)) {
            $msg = "We need a end hour";
        }
        else {
            if($user_home->edit_hour($date, $start_hours, $end_hours, $pause, $note, $hour_id)) {
                $msgHappy = "The hour Information is Updated.";
                header( "Location: index.php" );
            }
            else {
                $msg = "sorry , Query could no execute...";
            }
        }
    }
}


?>
<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Hour Tracker</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="css/bootstrap-datetimepicker.min.css" />
    <link rel="stylesheet" href="css/styles.css" />
</head>
<body>
<br>

    <nav class="navbar fixed-top navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">Hours Tracker</a>
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
    <?php if(isset($_GET['edit'])): ?>
        <br><br><br>
        <h1 class="text-center">Edit Hour</h1>
        <br><br>
        <?php
        if(!empty($msg)):
            ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <div class="text-center">
                    <strong>You have errors!</strong> <?php echo $msg; ?>
                </div>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php
        elseif (!empty($msgHappy)):
            ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <div class="text-center">
                    <?php echo $msgHappy; ?>
                </div>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php
        else:
            echo "";
        endif;

        ?>
        <div class="container-responsive" >
            <div class="row">
                <div class="col-2"></div>
                <div class="col-8 align-self-center">
                    <form action="index.php<?php echo '?edit='.$hour_id; ?>" method="post">

                        <!--DATE-->
                        <div class="form-group">
                            <label for="date" class="col-2 col-form-label">Date *</label>
                            <div class="col-10">
                                <input class="form-control" type="date" value="<?php echo $date ;?>" id="date" name="date">
                            </div>
                        </div>

                        <!--START HOUR-->
                        <div class="form-group">
                            <label for="start_hour" class="col-2 col-form-label">Start Hour *</label>
                            <div class="col-10">
                                <div class='input-group date' id='datetimepicker4'>
                                    <input type='text' class="form-control" id="start_hour" name="start_hour" value="<?php echo $start_hours ;?>"/>
                                    <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-time"></span>
                                </span>
                                </div>
                            </div>
                        </div>

                        <!--END HOUR-->
                        <div class="form-group">
                            <label for="end_hour" class="col-2 col-form-label">End Hour *</label>
                            <div class="col-10">
                                <div class='input-group date' id='datetimepicker5'>
                                    <input type='text' class="form-control" id="end_hour" name="end_hour" value="<?php echo $end_hours ;?>"/>
                                    <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-time"></span>
                                </span>
                                </div>
                            </div>
                        </div>

                        <!--PAUSE-->
                        <div class="form-group">
                            <label for="pause" class="col-2 col-form-label">Pause</label>
                            <div class="col-10">
                                <input class="form-control" type="text" id="pause" name="pause" value="<?php echo $pause ;?>">
                            </div>
                        </div>

                        <!--NOTE-->
                        <div class="form-group">
                            <label for="note" class="col-2 col-form-label">Notes</label>
                            <div class="col-10">
                                <textarea class="form-control" id="note" name="note" rows="3" ><?php echo $note ;?></textarea>
                            </div>
                        </div>

                        <!--SUBMIT BUTTON-->
                        <a href="index.php" style="float: left" class="btn btn-large btn-warning" >Cancel</a>
                        <button style="float: right" class="btn btn-large btn-primary" type="submit" name="submit">Edit Hour</button>
                    </form>
                </div><!--EIND DIV COL-MD-8-->
                <div class="col-2"></div>
            </div>
        </div>
    <?php else :?>
        <br><br><br>
        <h1 class="text-center">Worked Hours</h1>
        <br><br>

        <!--TABLE WITH CONTENT INFORMATION-->

        <table id="employee_data" class="table table-bordered table-striped" style="display: block;">
            <thead>
            <th></th>
            <th></th>
            <th>Date</th>
            <th>Start Hour</th>
            <th>End Hour</th>
            <th>Pause</th>
            <th>Note</th>
            <th>Total Hours</th>
            <th>Plus/Min Hours</th>
            </thead>
            <tbody>
            <?php
            $stmt_table = $user_home->runQuery("SELECT * FROM hours");
            $stmt_table->execute();
            while($row_table = $stmt_table->fetch(PDO::FETCH_ASSOC)):
                $id = (int)$row_table['id'];
                $date = $row_table['date'];
                $start_hours = $row_table['start_hours'];
                $end_hours = $row_table['end_hours'];
                $pause = $row_table['pause'];
                $note = $row_table['note'];

                $start_hour_array = explode(":", $start_hours);
                $start_num = $user_home->convertHours($start_hour_array[0], $start_hour_array[1]);
                $end_hour_array = explode(":", $end_hours);
                $end_num = $user_home->convertHours($end_hour_array[0], $end_hour_array[1]);

                $hoursPause = $pause / 60;

                $total= $end_num - $start_num - $hoursPause;

                $plus_min_hours = $total - WORKING_DAY_HOURS;

                $plus_min_hours = number_format($plus_min_hours, 2, '.', '');

                $total = number_format($total, 2, '.', '');
                ?>
                <tr>
                    <td>
                        <!--EDIT BUTTON-->
                        <a href="index.php?edit=<?php echo $id; ?>" class="btn btn-xs btn-primary" style="font-size: x-small">Edit</a>
                    </td>
                    <td>
                        <!--DELETE BUTTON-->
                        <a onclick="return confirm('Ben je zeker?')" href="index.php?delete=<?php echo $id; ?>" style="font-size: x-small" class="btn btn-xs btn-danger">X</a>
                    </td>
                    <td><?php echo $date ; ?></td>
                    <td><?php echo $start_hours ; ?></td>
                    <td><?php echo $end_hours ; ?></td>
                    <td><?php echo $pause ; ?></td>
                    <td><?php echo $note ; ?></td>
                    <td><?php echo $total; ?></td>
                    <td><?php echo $plus_min_hours; ?></td>
                </tr>

            <?php endwhile; ?>
            </tbody>
        </table>



    <?php endif; ?>


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