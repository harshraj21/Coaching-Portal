<?php
date_default_timezone_set('Asia/Kolkata');
session_start();
$message = "";
$messtype = 0;
$status = 0;
$secret = "harsh@123";
$times = 1;
$space = "+";
$newline = "%0a";
$apikey = "010Sr2dh20GaW1E6rwxktR0azOXt1";
function getIPAddress()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}
function crypto_rand_secure($min, $max)
{
    $range = $max - $min;
    if ($range < 1) return $min;
    $log = ceil(log($range, 2));
    $bytes = (int) ($log / 8) + 1;
    $bits = (int) $log + 1;
    $filter = (int) (1 << $bits) - 1;
    do {
        $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
        $rnd = $rnd & $filter;
    } while ($rnd > $range);
    return $min + $rnd;
}
function getToken()
{
    $token = "";
    $codeAlphabet = "0123456789";
    $max = strlen($codeAlphabet);
    for ($i = 0; $i < 8; $i++) {
        $token .= $codeAlphabet[crypto_rand_secure(0, $max - 1)];
    }
    return $token;
}
if (!(empty($_POST["roll"]) || empty($_POST["mob"]))) {
    $link = mysqli_connect("shareddb-u.hosting.stackcp.net", "optimus-3133335204", "superlol@123", "optimus-3133335204");
    $rollno = mysqli_real_escape_string($link, $_POST['roll']);
    $mob = mysqli_real_escape_string($link, $_POST['mob']);
    $user = md5($rollno);
    $sql1 = "SELECT * FROM `students` WHERE rollh = '$user'";
    $result1 = $link->query($sql1);
    if ($result1->num_rows > 1) {
        $message = "ERROR: Multiple Same Roll No Exist! Report Branch Immediately";
    } else if ($result1->num_rows == 1) {
        $row1 = $result1->fetch_assoc();
        if ($row1['verified'] == 1) {
            if ($row1['mobile'] == $mob) {
                $dat = $row1['name'];
                $otp = getToken();
                $otpexp = time() + 960;
                $sql2 = "UPDATE `students` SET otpreset = '$otp', expreset = '$otpexp' WHERE rollh = '$user'";
                if (mysqli_query($link, $sql2)) {
                    $body = "Hey, " . $dat . "\nYour OTP is: " . $otp . "\nTo Reset The Password\nThis OTP is valid for 15min\n(Dont Share This With Anyone)\nWith Best Regards,\nOptimus/Medicus Patna.";
                    $body = str_replace(" ", $space, $body);
                    $body = str_replace("\n", $newline, $body);
                    file_get_contents('http://onextelbulksms.in/shn/api/pushsms.php?usr=621561&key=' . $apikey . '&sndr=MEDICS&ph=' . $mob . '&text=' . $body);
                    $_SESSION['roll'] = $user;
                    $status = 1;
                } else {
                    $message = "ERROR CODE #100";
                }
            } else {
                $message = "Mobile no does not match with your account!";
            }
        } else {
            $message = "Please Verify Your Account First! Or Regenerate Your Password";
        }
    } else {
        $sql2 = "SELECT * FROM `students` WHERE roll = '$rollno'";
        $result2 = $link->query($sql2);
        if ($result2->num_rows == 1) {
            $message = "Please Generate Your Password First!";
        } else {
            $message = "Roll No Doesnot Exist!";
        }
    }
}
if (!(empty($_POST['pass']) || empty($_POST['cpass']))) {
    $link = mysqli_connect("shareddb-u.hosting.stackcp.net", "optimus-3133335204", "superlol@123", "optimus-3133335204");
    $pass = mysqli_real_escape_string($link, $_POST['pass']);
    $cpass = mysqli_real_escape_string($link, $_POST['cpass']);
    $otp = mysqli_real_escape_string($link, $_POST['otp']);
    $user = $_SESSION['roll'];
    if (!(empty($otp))) {
        if ($pass === $cpass) {
            if (strlen($pass) > 5 || strlen($cpass) > 5) {
                $sql1 = "SELECT * FROM `students` WHERE rollh = '$user'";
                $result1 = $link->query($sql1);
                if ($result1->num_rows > 1) {
                    $message = "Multiple Same Roll no Exist! Report Branch Immediately";
                } else if ($result1->num_rows == 1) {
                    if ($row1['otpreset'] === $otp) {
                        $row1 = $result1->fetch_assoc();
                        $stamp1 = time();
                        if ($row1['expreset'] > $stamp1) {
                            $cip = getIPAddress();
                            $salt = array(sha1($cpass), sha1($row['stamp']), sha1($secret), sha1($user));
                            $mpass = sha1($salt[0] . $salt[1] . $salt[2] . $salt[3]);
                            $sql2 = "UPDATE `students` SET password = '$mpass', otpreset = 0, expreset = 0, ipaddress = '$cip' WHERE rollh = '$user'";
                            if (mysqli_query($link, $sql2)) {
                                unset($_SESSION['roll']);
                                $messtype = 1;
                                $message = "New Password Setup Successfull <a href=\"login.php\">CLICK HERE</a>";
                                session_destroy();
                            } else {
                                $message = "ERROR CODE #100";
                            }
                        } else {
                            $message = "Sorry Current OTP Expired! Request a New One.";
                        }
                    } else {
                        $message = "Incorrect OTP Try Again!";
                        $status = 1;
                    }
                } else {
                    $message = "ERROR CODE #101";
                    $status = 1;
                }
            } else {
                $message = "Password Must Be Greater Than 5 Character";
                $status = 1;
            }
        } else {
            $message = "Both Password Doesn't Match";
            $status = 1;
        }
    } else {
        $message = "Error: OTP is Required! Please Enter The OTP & Try Again.";
        $status = 1;
    }
}
if (!(empty($_POST['resend']))) {
    $link = mysqli_connect("shareddb-u.hosting.stackcp.net", "optimus-3133335204", "superlol@123", "optimus-3133335204");
    $user = $_SESSION['roll'];
    $sql1 = "SELECT * FROM `students` WHERE rollh = '$user'";
    $result1 = $link->query($sql1);
    if ($result1->num_rows == 1) {
        $row1 = $result1->fetch_assoc();
        $dat = $row1['name'];
        $mobno = $row1['mobile'];
        $otp = getToken();
        $exp = time() + 960;
        $sql2 = "UPDATE `students` SET otpreset = '$otp', expreset = '$exp' WHERE rollh = '$user'";
        if (mysqli_query($link, $sql2)) {
            $body = "Hey, " . $dat . "\nYour OTP is: " . $otp . "\nTo Reset The Password\nThis OTP is valid for 15min\n(Dont Share This With Anyone)\nWith Best Regards,\nOptimus/Medicus Patna";
            $body = str_replace(" ", $space, $body);
            $body = str_replace("\n", $newline, $body);
            file_get_contents('http://onextelbulksms.in/shn/api/pushsms.php?usr=621561&key=' . $apikey . '&sndr=MEDICS&ph=' . $mobno . '&text=' . $body);
            $status = 1;
            $times = 0;
            $messtype = 1;
            $message = "Resend OTP Successfull! If Still Not Received Try Later";
        } else {
            $message = "ERROR CODE #100";
        }
    } else {
        $message = "ERROR CODE #101";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="assets/icon.png">
    <title>Optimus Student Password Reset</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/css/all.css">
    <style>
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

        .container1 {
            width: 50%;
            height: 100vh;
            overflow: hidden;
            float: left;
        }

        .formbox {
            margin-top: 25%;
            border-radius: 5px;
            width: 50%;
            margin-left: 48%;
            height: 35%;
            background: #35394a;
        }

        .head {
            padding: 0.5%;
            background: #ff6600;
        }

        .log {
            color: white;
            margin-top: 5%;
            padding-left: 3%;
            border-left: 5px solid red;
        }

        .inputgrp {
            padding: 5%;
        }

        .input-group {
            padding-top: 3%;
        }

        .inputcust {
            padding-top: 3%;
        }

        .btnlog {
            margin-top: 5%;
            border-radius: 5px;
        }

        .first {
            margin-top: 5%;
            background: #00b3b3;
            color: white;
            border: 1px solid #00b3b3;
        }

        .bottomright {
            position: absolute;
            bottom: 8px;
            right: 16px;
            font-size: 14px;
        }

        .bottomleft {
            position: absolute;
            bottom: 8px;
            left: 16px;
            font-size: 14px;
        }

        .container2 {
            height: 100vh;
            overflow: hidden;
            width: 25rem;
        }

        .imgbox {
            width: 95%;
            margin-bottom: 5%;
            padding-left: 10%;
            padding-right: 10%;
        }

        .arrange {
            margin-top: 5%;
        }

        .tagbar {
            margin-left: 3%;
        }

        .contbox {
            margin-top: 14.5rem;
        }

        .mesg {
            width: 50%;
            margin-left: 48%;
            margin-top: 2%;
        }

        .bar {
            border-top: 0.5px solid gray;
        }

        .contmain {
            display: none;
        }

        @media screen and (max-width: 1366px) {
            .contbox {
                margin-top: 10rem;
            }
        }

        @media screen and (max-width: 1080px) {
            .container1 {
                width: 98%;
                height: auto;
            }

            .container2 {
                height: auto;
                width: 98%;
            }

            .formbox {
                margin-top: 2%;
                border-radius: 5px;
                width: 100%;
                margin-left: 2%;
            }

            .contbox {
                margin-top: 5%;
            }

            .bottomright {
                display: none;
            }

            .bottomleft {
                display: none;
            }

            .mesg {
                width: 98%;
                margin-left: 2%;
                margin-top: 2%;
            }
        }
    </style>
</head>

<body>
    <div id="loader"></div>
    <div id="main" class="contmain">
        <div class="container1">
            <?php if ($status == 0) { ?>
                <form action="" method="post">
                    <div class="formbox">
                        <div class="head"></div>
                        <h4 class="log">Student Password Reset</h4>
                        <div class="inputgrp">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><i class="fas fa-bars"></i></div>
                                </div>
                                <input type="text" name="roll" class="form-control" minlength="3" placeholder="Registered roll number" required>
                            </div>
                            <div class="input-group inputcust">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><i class="fas fa-phone-alt"></i></div>
                                </div>
                                <input type="text" name="mob" class="form-control" minlength="10" maxlength="10" placeholder="Registered mobile no" required>
                            </div>
                            <button type="submit" class="btn btn-success btnlog btn-block">Recover Password</button>
                            <a class="btn btn-primary first" href="login.php">Back to login</a>
                        </div>
                    </div>
                </form>
            <?php } else if ($status == 1) { ?>
                <div>
                    <div class="formbox">
                        <div class="head"></div>
                        <h4 class="log">Student Set Password</h4>
                        <div class="inputgrp">
                            <form action="" method="post">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-user-lock"></i></div>
                                    </div>
                                    <input type="password" name="pass" class="form-control" minlength="6" placeholder="Set password" required>
                                </div>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-user-lock"></i></div>
                                    </div>
                                    <input type="password" name="cpass" class="form-control" minlength="6" placeholder="Confirm password" required>
                                </div>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-key"></i></div>
                                    </div>
                                    <input type="text" name="otp" class="form-control" minlength="8" maxlength="8" placeholder="OTP Recieved" required>
                                </div>
                                <button type="submit" class="btn btn-success btnlog btn-block">Set Password</button>
                            </form>
                            <?php if ($times) { ?>
                                <form action="" method="post">
                                    <input type="submit" class="btn btn-light first" name="resend" value="Resend OTP" />
                                </form>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <?php if (!(empty($message))) { ?>
                <div class="mesg">
                    <?php if ($messtype) { ?>
                        <div class="alert alert-success" role="alert">
                            <?php echo $message ?>
                        </div>
                    <?php } else { ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo $message ?>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>
            <a class="bottomright" href="https://www.fiverr.com/harshraj126">Build With <i style="color: red;" class="fas fa-heart"></i> by Harsh Raj</a>
            <div class="bottomleft">Optimus Portal V2.0</div>
        </div>
        <div class="container2">
            <div class="contbox">
                <img class="imgbox" src="assets/logo.gif" alt="xyz">
                <div class="bar"></div>
                <div class="tagbar">
                    <p class="arrange"><i style="color: green;" class="far fa-chart-bar">&nbsp;&nbsp;</i>Analyse Your Performance</p>
                    <p class="arrange"><i style="color: orange;" class="fas fa-book">&nbsp;&nbsp;</i>Get Online Content From <strong>OPTIMUS</strong> Teachers</p>
                    <p class="arrange"><i style="color: blue;" class="fas fa-calendar-alt">&nbsp;&nbsp;</i>Stay Up to date With Your Cource Curriculum</p>
                    <p class="arrange"><i style="color: purple;" class="fas fa-pencil-ruler">&nbsp;&nbsp;</i>Class Schedule, Exam Schedule & Much More</p>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
    <script>
        $(window).on("load", () => {
            document.getElementById("loader").style.display = "none";
            document.getElementById("main").style.display = "block";
        });
    </script>
</body>

</html>