<?php
session_start();

require_once 'core/User.php';
$user_home = new User();

$msg = "";
$msgHappy = "";
if(isset($_POST["btn-hours"])){
    if(isset($_POST["date"]) && !empty($_POST["date"])){
        if(isset($_POST["start_hour"]) && !empty($_POST["start_hour"])){
            if(isset($_POST["end_hour"]) && !empty($_POST["end_hour"])){
                $date = $_POST["date"];
                $start_hours = $_POST["start_hour"];
                $end_hours = $_POST["end_hour"];
                if(isset($_POST["note"]) && !empty($_POST["note"])){
                    $note = $_POST["note"];
                }
                else{
                    $note = "";
                }
                if(isset($_POST["pause"]) && !empty($_POST["pause"])){
                    $pause = $_POST["pause"];
                }
                else{
                    $pause = 0;
                }
                $user_home->add_hour($date,$start_hours, $end_hours, $pause, $note);
                $msgHappy = "Your hour was added with success!";
            }
            else{
                $msg = "We need a ending hour";
            }
        }
        else{
            $msg = "We need a starting hour";
        }
    }
    else{
        $msg = "We need a date";
    }
}
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
    <link href="css/bootstrap4-glyphicons-master/bootstrap4-glyphicons/css/bootstrap-glyphicons.css" rel="stylesheet" type="text/css" />
    <title>Hours Tracker</title>
</head>
<body>
<br>
<div class="container-responsive" >
    <nav class="navbar fixed-top navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="index.php">Hours Tracker</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
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
    <br><br><br>
    <h1 class="text-center">Add Hour</h1>
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

    <div class="row">
        <div class="col-2"></div>
        <div class="col-8 align-self-center">
            <form action="fill.php" method="post">

                <!--DATE-->
                <div class="form-group">
                    <label for="date" class="col-2 col-form-label">Date *</label>
                    <div class="col-10">
                        <input class="form-control" type="date" value="<?php echo ((isset($_POST["date"]))? $_POST["date"] : "" ) ;?>" id="date" name="date">
                    </div>
                </div>

                <!--START HOUR-->
                <div class="form-group">
                    <label for="start_hour" class="col-2 col-form-label">Start Hour *</label>
                    <div class="col-10">
                        <div class='input-group date' id='datetimepicker4'>
                            <input type='text' class="form-control" id="start_hour" name="start_hour" value="<?php echo ((isset($_POST["start_hour"]))? $_POST["start_hour"] : "" ) ;?>" />
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
                            <input type='text' class="form-control" id="end_hour" name="end_hour" value="<?php echo ((isset($_POST["end_hour"]))? $_POST["end_hour"] : "" ) ;?>" />
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-time"></span>
                            </span>
                        </div>
                    </div>
                </div>

                <!--PAUSE-->
                <div class="form-group">
                    <label for="pause" class="col-2 col-form-label">Pause (min)</label>
                    <div class="col-10">
                        <input class="form-control" type="text" value="<?php echo ((isset($_POST["pause"]))? $_POST["pause"] : "" ) ;?>" id="pause" name="pause">
                    </div>
                </div>

                <!--NOTE-->
                <div class="form-group">
                    <label for="note" class="col-2 col-form-label">Notes</label>
                    <div class="col-10">
                        <textarea class="form-control" id="note" name="note" rows="3"><?php echo ((isset($_POST["note"]))? $_POST["note"] : "" ) ;?></textarea>
                    </div>
                </div>
                <br>

                <!--SUBMIT BUTTON-->
                <div class="button_wrapper" style="text-align: center">
                    <button style=" width: 200px;" class="btn btn-large btn-primary" type="submit" name="btn-hours">Add Hour</button>
                </div>
            </form>
        </div><!--EIND DIV COL-MD-8-->
        <div class="col-2"></div>
    </div>
</div>
<br><br><br>


<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script type="text/javascript" src="js/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
<script type="text/javascript" src="js/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript">
    $(function () {
        var datePickerStartHour = $('#datetimepicker4');
        datePickerStartHour.datetimepicker({
            format: 'H:mm'
        });

        var datePickerEndHour = $('#datetimepicker5');
        datePickerEndHour.datetimepicker({
            format: 'H:mm'
        });
    });
</script>
</body>
</html>


