<?php
date_default_timezone_set('Asia/Kolkata');
session_start();
// if (!(isset($_SESSION['ssid']) && isset($_SESSION['batch']) && isset($_SESSION['name']) && isset($_GET['token']))) {
//     session_destroy();
//     header("Location: login.php");
//     exit;
// } else {
//     $link = mysqli_connect("shareddb-u.hosting.stackcp.net", "optimus-3133335204", "superlol@123", "optimus-3133335204");
//     $token = mysqli_real_escape_string($link, $_SESSION['ssid']);
//     $sql1 = "SELECT * FROM `students` WHERE sessionid = '$token'";
//     $result1 = $link->query($sql1);
//     if ($result1->num_rows > 1) {
//         session_destroy();
//         header("Location: login.php");
//         exit;
//     } else if ($result1->num_rows == 1) {
//     } else {
//         session_destroy();
//         header("Location: login.php");
//         exit;
//     }
// }
$message = NULL;
$page = $_GET['page'];
if (!(empty($_POST["cont"]) || (empty($_POST['batchp'])) || (empty($_POST['expi'])))) {
    $link = mysqli_connect("shareddb-u.hosting.stackcp.net", "optimus-3133335204", "superlol@123", "optimus-3133335204");
    $batchp = mysqli_real_escape_string($link, $_POST['batchp']);
    $expi = mysqli_real_escape_string($link, $_POST['expi']);
    $cont = mysqli_real_escape_string($link, $_POST['cont']);
    if($expi != 1){
        $expi = time() + $expi;
    }
    if ($batchp&&$expi) {
        $sql1 = "INSERT INTO `notice` ( id, batchid, content, duration ) VALUES (NULL, '$batchp', '$cont', '$expi')";
        if (mysqli_query($link, $sql1)) {
            $message = "Notice Published Sucessfully!";
        } else {
            $message = "Failed To Published Notice!";
        }
    } else {
        $message = "Please Select all Fields!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="icon" href="/assets/icon.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Panel</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="/assets/css/all.css">
    <style>
        #main {
            display: none;
        }

        #loader {
            position: absolute;
            left: 50%;
            top: 50%;
            z-index: 1;
            width: 150px;
            height: 150px;
            margin: -75px 0 0 -75px;
            border: 16px solid #f3f3f3;
            border-radius: 50%;
            border-top: 16px solid blue;
            width: 120px;
            height: 120px;
            -webkit-animation: spin 2s linear infinite;
            animation: spin 2s linear infinite;
            border-right: 16px solid green;
            border-bottom: 16px solid red;
            border-left: 16px solid pink;
        }

        @-webkit-keyframes spin {
            0% {
                -webkit-transform: rotate(0deg);
            }

            100% {
                -webkit-transform: rotate(360deg);
            }
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .animate-bottom {
            position: relative;
            -webkit-animation-name: animatebottom;
            -webkit-animation-duration: 1s;
            animation-name: animatebottom;
            animation-duration: 1s
        }

        @-webkit-keyframes animatebottom {
            from {
                bottom: -100px;
                opacity: 0
            }

            to {
                bottom: 0px;
                opacity: 1
            }
        }

        @keyframes animatebottom {
            from {
                bottom: -100px;
                opacity: 0
            }

            to {
                bottom: 0;
                opacity: 1
            }
        }

        .cont1 {
            height: 100vh;
            width: 13%;
            overflow: hidden;
            float: left;
        }

        .logo {
            height: 8%;
            width: 100%;
        }

        .nav {
            margin-top: 10%;
            margin-left: 5%;
        }

        .nav-link {
            margin-top: 2%;
            margin-bottom: 2%;
            color: gray;
            width: 95%;
        }

        .nav-link:hover {
            border-radius: 5%;
            border: 1px solid #e6e6e6;
            color: #007399;
        }

        .lin {
            border-top: 1px solid #d9d9d9;
            width: 95%;
        }

        .navbar {
            background-color: #1fc8db;
            background-image: linear-gradient(141deg, #9fb8ad 0%, #1fc8db 51%, #2cb5e8 75%);
        }

        .heading {
            margin-left: 25px;
            margin-top: 15px;
        }

        .cont3 {
            width: 50%;
            margin-left: 25%;
            margin-top: 3%;
            padding: 2%;
            height: 65vh;
        }

        .box1 {
            width: 100%;
        }

        textarea.form-control {
            height: 44vh;
        }

        .cont2 {
            overflow: hidden;
        }

        .navtxt {
            margin-top: 8%;
            font-size: 90%;
            text-align: center;
            font-weight: 600;
            text-decoration: underline;
        }

        @media screen and (max-width: 1366px) {
            textarea.form-control {
                height: 35vh;
            }
        }
    </style>
</head>

<body>
    <div id="loader"></div>
    <div id="main">
    <div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Notification</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php echo($message); ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Ok</button>
                </div>
            </div>
        </div>
    </div>
        <div class="cont1 shadow">
            <img class="logo" src="/assets/logo.gif" alt="xyz">
            <p class="navtxt">CONTROL PANEL</p>
            <nav class="nav flex-column">
                <a class="nav-link" href="panel.php?token=<?php echo $_SESSION['ssid']; ?>&page=Notice">Add Notice</a>
                <div class="lin"></div>
                <a class="nav-link" href="panel.php?token=<?php echo $_SESSION['ssid']; ?>&page=Class">Add Class</a>
                <div class="lin"></div>
                <a class="nav-link" href="panel.php?token=<?php echo $_SESSION['ssid']; ?>&page=Students">Register Student</a>
                <div class="lin"></div>
                <a class="nav-link" href="panel.php?token=<?php echo $_SESSION['ssid']; ?>&page=Faculty">Register Faculty</a>
                <div class="lin"></div>
                <a class="nav-link" href="panel.php?token=<?php echo $_SESSION['ssid']; ?>&page=Block">Block Accounts</a>
            </nav>
        </div>
        <div class="cont2">
            <nav class="navbar navbar-light">
                <h4 style="font-weight: 600; color:white;">&nbsp;&nbsp;<?php echo $_SESSION['name']; ?></h4>
                <p style="font-size: 20px; font-weight:600; color:white; margin-bottom: -1px;" id="clock"></p>
                <a type="button" href="logout.php?token=<?php echo $_SESSION['ssid'] ?>" class="btn btn-danger">Logout</a>
            </nav>
            <?php if ($page == "Notice") { ?>
                <h4 class="heading">NOTICE</h4>
                <div class="cont3 shadow p-3 mb-5 bg-white rounded">
                    <form action="" method="post">
                        <label>Cource
                            <select class="form-control" name="batchp" required>
                                <option value="none" selected disabled hidden>select</option>
                                <?php
                                $link = mysqli_connect("shareddb-u.hosting.stackcp.net", "optimus-3133335204", "superlol@123", "optimus-3133335204");
                                $sql1 = "SELECT * FROM `batch`";
                                $result1 = $link->query($sql1);
                                if ($result1->num_rows > 0) {
                                    while ($row1 = $result1->fetch_assoc()) {
                                ?>
                                        <option value="<?php echo ($row1['bid']); ?>"><?php echo ($row1['bname']); ?></option>
                                <?php
                                    }
                                }
                                ?>
                            </select>
                        </label>
                        <label>Notice Expiry
                            <select class="form-control" name="expi" required>
                                <option value="none" selected disabled hidden>select</option>
                                <?php
                                $link = mysqli_connect("shareddb-u.hosting.stackcp.net", "optimus-3133335204", "superlol@123", "optimus-3133335204");
                                $sql2 = "SELECT * FROM `time`";
                                $result2 = $link->query($sql2);
                                if ($result2->num_rows > 0) {
                                    while ($row2 = $result2->fetch_assoc()) {
                                ?>
                                        <option value="<?php echo ($row2['secs']); ?>"><?php echo ($row2['name']); ?></option>
                                <?php
                                    }
                                }
                                ?>
                            </select>
                        </label>
                        <div class="form-group">
                            <label for="exampleFormControlTextarea1">Notice</label>
                            <textarea name="cont" minlength="10" class="form-control box1" id="exampleFormControlTextarea1" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-success">Publish</button>
                    </form>
                </div>
            <?php } else if ($page == "Class") { ?>
                <h4 class="heading">ADD CLASSES</h4>
                <div class="cont3 shadow p-3 mb-5 bg-white rounded">
                    <form action="" method="post">
                        
                    </form>
                </div>
            <?php } else if ($page == "Students") { ?>
            <?php } else if ($page == "Faculty") { ?>
            <?php } else if ($page == "Block") { ?>
            <?php } ?>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
    <?php if($message != NULL){ ?>
    <script>
        $('.modal').modal('show');
    </script>
    <?php } ?>
    <script>
        $(window).on("load", () => {
            document.getElementById("loader").style.display = "none";
            document.getElementById("main").style.display = "block";
        });

        function GetClock() {
            var date = new Date();
            var hours = date.getHours(),
                minutes = date.getMinutes(),
                sec = date.getSeconds(),
                ampm = hours >= 12 ? 'PM' : 'AM';
            hours = hours % 12;
            hours = hours ? hours : 12;
            minutes = minutes < 10 ? '0' + minutes : minutes;
            if (hours <= 9) hours = "0" + hours
            if (sec <= 9) sec = "0" + sec;

            document.getElementById('clock').innerHTML = " " + hours + ":" + minutes + ":" + sec + " " + ampm;
        }
        window.onload = function() {
            GetClock();
            setInterval(GetClock, 1000);
        }
    </script>
</body>

</html>