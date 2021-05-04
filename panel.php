<?php
date_default_timezone_set('Asia/Kolkata');
session_start();
if (!(isset($_SESSION['ssid']) && isset($_SESSION['batch']) && isset($_SESSION['name']) && isset($_GET['token']))) {
    session_destroy();
    header("Location: login.php");
    exit;
} else {
    $link = mysqli_connect("shareddb-u.hosting.stackcp.net", "optimus-3133335204", "superlol@123", "optimus-3133335204");
    $token = mysqli_real_escape_string($link, $_SESSION['ssid']);
    $sql1 = "SELECT * FROM `students` WHERE sessionid = '$token'";
    $result1 = $link->query($sql1);
    if ($result1->num_rows == 1) {
    } else {
        session_destroy();
        header("Location: login.php");
        exit;
    }
}
$page = $_GET['page'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="icon" href="assets/icon.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Optimus Student Panel</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/css/all.css">
    <link href="https://vjs.zencdn.net/7.7.6/video-js.css" rel="stylesheet" />
    <script src="https://vjs.zencdn.net/ie8/1.1.2/videojs-ie8.min.js"></script>
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

        .contmain {
            display: none;
        }

        .navcont {
            width: 13%;
            height: 100vh;
            overflow: hidden;
            float: left;
        }

        .nav-link {
            color: gray;
            padding-left: 0.1%;
            font-size: 15px;
        }

        .bar {
            margin-top: 2%;
            border-top: 0.5px solid gray;
        }

        .extra {
            margin-top: 15%;
        }

        .nav-link:hover {
            border: 0.5px solid darkorange;
            border-radius: 3px;
            background: darkorange;
            color: white;
        }

        .fas {
            color: #00ccff;
        }

        .fas:hover {
            color: white;
        }

        .heading {
            margin-left: 1%;
            margin-top: 1%;
        }

        .imgbox {
            padding-top: 2%;
            padding-left: 5%;
            width: 85%;
        }

        .container1 {
            overflow: hidden;
        }

        .contusable {
            margin-left: 2%;
            margin-right: 2%;
            margin-top: 2%;
            overflow: hidden;
        }

        .usabelprofile {
            margin-left: 2%;
            width: 35%;
            margin-top: 2%;
            overflow: hidden;
        }

        .notice {
            width: 47%;
            float: left;
            margin-right: 3%;
            margin-bottom: 2%;
        }

        .navbar {
            background-color: #1fc8db;
            background-image: linear-gradient(141deg, #9fb8ad 0%, #1fc8db 51%, #2cb5e8 75%);
        }

        .head {
            border: 1px solid #ff794d;
            text-align: center;
            background: #ff794d;
            font-weight: 800;
            color: white;
            padding-top: 1%;
            padding-bottom: 1%;
        }

        .bodys {

            border-left: 1px solid #ffb366;
            border-right: 1px solid #ffb366;
            text-align: right;
            padding-right: 2%;
            background: #ffb366;
        }

        .end {
            background: #ff8000;
            border: 1px solid #ff8000;
            padding-top: 1%;
            padding-bottom: 1%;
        }

        .viewcl {
            padding-left: 2%;
            display: block;
            font-weight: 800;
            color: white;
        }

        .viewcl:hover {
            color: white;
        }

        .arrow:hover {
            color: white;
        }

        .classes {
            width: 30%;
            border: 1px solid red;
            border-radius: 3px;
            float: left;
            margin-left: 1%;
            margin-right: 2%;
            overflow: hidden;
            margin-bottom: 2%;
        }

        .arrow {
            padding-right: 2%;
            margin-top: 1%;
            color: white;
        }

        .vid {
            margin-left: 24%;
            margin-top: 8%;
        }

        .hell{
            padding-top: 1%;
            padding-left: 1%;
        }

        .navcontinner {
            padding: 8%;
        }

        @media screen and (max-width: 1366px) {
            .navcont {
                width: 15%;
                font-size: 12%;
                height: 100vh;
                overflow: hidden;
                float: left;
            }
            .navcontinner {
                padding: 5%;
            }
            .vid {
                margin-left: 16%;
                margin-top: 5%;
            }
        }
    </style>
</head>

<body>
    <div id="loader"></div>
    <div id="main" class="contmain">
        <div class="navcont shadow">
            <img class="imgbox" src="assets/logo.gif" alt="xyz">
            <nav class="nav flex-column">
                <div class="navcontinner">
                    <div class="bar"></div>
                    <a class="nav-link extra" href="panel.php?token=<?php echo ($_SESSION['ssid']) ?>&page=Dashboard"><i class="fas fa-tachometer-alt">&nbsp;&nbsp;</i>Dashboard</a>
                    <a class="nav-link" href="panel.php?token=<?php echo ($_SESSION['ssid']) ?>&page=Profile"><i class="fas fa-user">&nbsp;&nbsp;</i>My Profile</a>
                    <a class="nav-link" href="panel.php?token=<?php echo ($_SESSION['ssid']) ?>&page=Classroom"><i class="fas fa-play-circle">&nbsp;&nbsp;</i>Digital Classroom</a>
                    <a class="nav-link" href="panel.php?token=<?php echo ($_SESSION['ssid']) ?>&page=Live"><i class="fas fa-file-video">&nbsp;&nbsp;</i>Live Classroom</a>
                    <a class="nav-link" href="panel.php?token=<?php echo ($_SESSION['ssid']) ?>&page=Material"><i class="fas fa-book">&nbsp;&nbsp;</i>Class Material</a>
                    <a class="nav-link" href="panel.php?token=<?php echo ($_SESSION['ssid']) ?>&page=Schedule"><i class="fas fa-list-ol">&nbsp;&nbsp;</i>Class Schedule</a>
                    <a class="nav-link" href="panel.php?token=<?php echo ($_SESSION['ssid']) ?>&page=Report"><i class="fas fa-chart-line">&nbsp;&nbsp;</i>Performance Report</a>
                </div>
            </nav>
        </div>
        <div class="container1">
            <nav class="navbar navbar-light">
                <h4 style="font-weight: 600; color:white;">&nbsp;&nbsp;<?php echo $_SESSION['name']; ?></h4>
                <p style="font-size: 20px; font-weight:600; color:white; margin-bottom: -1px;" id="clock"></p>
                <a type="button" href="logout.php?token=<?php echo $_SESSION['ssid'] ?>" class="btn btn-danger">Logout</a>
            </nav>
            <?php if ($page == "Dashboard") { ?>
                <h3 class="heading">DASHBOARD</h3>
                <div class="contusable">
                    <?php
                    $link = mysqli_connect("shareddb-u.hosting.stackcp.net", "optimus-3133335204", "superlol@123", "optimus-3133335204");
                    $batchid = $_SESSION['batch'];
                    $sql1 = "SELECT * FROM `notice` WHERE batchid = '$batchid' OR batchid = 1";
                    $result1 = $link->query($sql1);
                    if ($result1->num_rows > 0) {
                        while ($row1 = $result1->fetch_assoc()) {
                            if(($row1['duration'] > time()) || ($row1['duration'] == 1)) { 
                    ?>
                                <div class="alert alert-primary notice" role="alert">
                                    <?php echo $row1['content']; ?>
                                </div>
                    <?php
                            }
                        }
                    }
                    ?>
                </div>
            <?php } else if ($page == "Profile") { ?>
                <h3 class="heading">MY PROFILE</h3>
                <div class="usabelprofile shadow p-3 mb-5 bg-white rounded">
                    <?php
                    $link = mysqli_connect("shareddb-u.hosting.stackcp.net", "optimus-3133335204", "superlol@123", "optimus-3133335204");
                    $token = $_SESSION['ssid'];
                    $sql1 = "SELECT * FROM `students` WHERE sessionid = '$token'";
                    $result1 = $link->query($sql1);
                    if ($result1->num_rows == 1) {
                        $row1 = $result1->fetch_assoc();
                    ?>
                        <p>Name: <?php echo $row1['name']; ?></p>
                        <?php
                        $link = mysqli_connect("shareddb-u.hosting.stackcp.net", "optimus-3133335204", "superlol@123", "optimus-3133335204");
                        $bid = $_SESSION['batch'];
                        $sql2 = "SELECT * FROM `batch` WHERE bid = '$bid'";
                        $result2 = $link->query($sql2);
                        if ($result2->num_rows == 1) {
                            $row2 = $result2->fetch_assoc();
                        ?>
                            <p>Batch Name: <?php echo $row2['bname']; ?></p>
                        <?php
                        }
                        ?>
                        <p>Roll No: <?php echo $row1['roll']; ?></p>
                        <p>Mobile No: <?php echo $row1['mobile']; ?></p>
                        <p>Address: <?php echo $row1['address']; ?></p>
                        <p>IP Address: <?php echo $row1['ipaddress']; ?></p>
                    <?php
                    }
                    ?>
                </div>
            <?php } else if ($page == "Classroom") { ?>
                <h3 class="heading">DIGITAL CLASSROOM</h3>
                <?php
                $link = mysqli_connect("shareddb-u.hosting.stackcp.net", "optimus-3133335204", "superlol@123", "optimus-3133335204");
                $batch = $_SESSION['batch'];
                $sql1 = "SELECT * FROM `class` WHERE batch = '$batch'";
                $stamp = time();
                $result1 = $link->query($sql1);
                if ($result1->num_rows > 0) {
                    while ($row1 = $result1->fetch_assoc()) {
                        if ($row1['forever'] != 1) {
                            if ($row1['stamp'] > $stamp - 172800) {
                ?>
                    <div class="contusable shadow p-3 mb-5 bg-white rounded">
                        <h6>&nbsp;&nbsp;<?php echo ($row1['date']); ?></h6>
                    <?php
                        $j = 9001;
                        $link = mysqli_connect("shareddb-u.hosting.stackcp.net", "optimus-3133335204", "superlol@123", "optimus-3133335204");
                        $num = $row1['uid'];
                        $sql2 = "SELECT * FROM `subject` WHERE uid = '$num'";
                        $stamp2 = time();
                        $result2 = $link->query($sql2);
                        if ($result2->num_rows > 0) {
                            while ($row2 = $result2->fetch_assoc()) {
                                if ($row2['stime'] > $stamp2 && $row2['etime'] > $stamp2) {
                    ?>
                        <div class="classes">
                            <div class="head"><span><?php echo($row2['sub']." | ".date("h:i A", $row2['stime'])) ?></span></div>
                            <div class="bodys"><span>&nbsp;</span></div>
                            <div class="bodys"><span>Class Will Be Available In</span></div>
                            <div class="bodys" id="<?php echo(str_replace(" ","",$row2['sub'].$row2['etime'].$row2['stime'].$row2['toppic'])); ?>"><?php echo($row2['stime']); ?></div>
                            <div class="bodys"><span>&nbsp;</span></div>
                            <div class="bodys"><span><?php echo($row2['toppic']) ?></span></div>
                            <div class="bodys"><span>&nbsp;</span></div>
                            <div class="end"><span>
                                <a class="viewcl" style="text-decoration: none;" href="#">View Class<i class="fas fa-arrow-right float-right arrow"></i></a>
                            </span></div>
                        </div>
                        <script>
                            try {
                                var countDownDate<?php echo(str_replace(" ","",$row2['sub'].$row2['etime'].$row2['stime'].$row2['toppic'])); ?> = new Date(parseInt(document.getElementById("<?php echo(str_replace(" ","",$row2['sub'].$row2['etime'].$row2['stime'].$row2['toppic'])); ?>").innerHTML) * 1000);
                                var a<?php echo(str_replace(" ","",$row2['sub'].$row2['etime'].$row2['stime'].$row2['toppic'])); ?> = setInterval(() => {
                                    var now = new Date().getTime();
                                    var distance = countDownDate<?php echo(str_replace(" ","",$row2['sub'].$row2['etime'].$row2['stime'].$row2['toppic'])); ?> - now;
                                    var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                                    var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                                    var seconds = Math.floor((distance % (1000 * 60)) / 1000);
                                    document.getElementById("<?php echo(str_replace(" ","",$row2['sub'].$row2['etime'].$row2['stime'].$row2['toppic'])); ?>").innerHTML = days + "d " + hours + "h " + minutes + "m " + seconds + "s ";
                                    if (distance < 0) {
                                        clearInterval(a<?php echo(str_replace(" ","",$row2['sub'].$row2['etime'].$row2['stime'].$row2['toppic'])); ?>);
                                        document.getElementById("<?php echo(str_replace(" ","",$row2['sub'].$row2['etime'].$row2['stime'].$row2['toppic'])); ?>").innerHTML = "AVAILABLE";
                                        location.reload();
                                    }
                                }, 1000);
                            } catch {}
                        </script>
                    <?php 
                        } else if($row2['stime'] <= $stamp2 && $row2['etime'] > $stamp2) { 
                    ?>
                        <div class="classes">
                            <div class="head"><span><?php echo($row2['sub']." | ".date("h:i A", $row2['stime'])) ?></span></div>
                            <div class="bodys"><span>&nbsp;</span></div>
                            <div class="bodys"><span>This Class Will Expire In</span></div>
                            <div class="bodys" id="<?php echo(str_replace(" ","",$row2['sub'].$row2['etime'].$row2['stime'].$row2['toppic'])); ?>"><?php echo($row2['etime']); ?></div>
                            <div class="bodys"><span>&nbsp;</span></div>
                            <div class="bodys"><span><?php echo ($row2['toppic']) ?></span></div>
                            <div class="bodys"><span>&nbsp;</span></div>
                            <div class="end"><span>
                                <a class="viewcl" style="text-decoration: none;" href="panel.php?token=<?php echo($_SESSION['ssid']); ?>&page=Video&player=<?php echo("HR".$j) ?>&subject=<?php echo($row2['uid']); ?>">View Class<i class="fas fa-arrow-right float-right arrow"></i></a>
                            </span></div>
                        </div>
                        <script>
                            try {
                                var countDownDate<?php echo(str_replace(" ","",$row2['sub'].$row2['etime'].$row2['stime'].$row2['toppic'])); ?> = new Date(parseInt(document.getElementById("<?php echo(str_replace(" ","",$row2['sub'].$row2['etime'].$row2['stime'].$row2['toppic'])); ?>").innerHTML) * 1000);
                                var a<?php echo(str_replace(" ","",$row2['sub'].$row2['etime'].$row2['stime'].$row2['toppic'])); ?> = setInterval(() => {
                                    var now = new Date().getTime();
                                    var distance = countDownDate<?php echo(str_replace(" ","",$row2['sub'].$row2['etime'].$row2['stime'].$row2['toppic'])); ?> - now;
                                    var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                                    var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                                    var seconds = Math.floor((distance % (1000 * 60)) / 1000);
                                    document.getElementById("<?php echo(str_replace(" ","",$row2['sub'].$row2['etime'].$row2['stime'].$row2['toppic'])); ?>").innerHTML = days + "d " + hours + "h " + minutes + "m " + seconds + "s ";
                                    if (distance < 0) {
                                        clearInterval(a<?php echo(str_replace(" ","",$row2['sub'].$row2['etime'].$row2['stime'].$row2['toppic'])); ?>);
                                        document.getElementById("<?php echo(str_replace(" ","",$row2['sub'].$row2['etime'].$row2['stime'].$row2['toppic'])); ?>").innerHTML = "AVAILABLE";
                                        location.reload();
                                    }
                                }, 1000);
                            } catch {}
                        </script>
                    <?php 
                        }
                        $j++;
                    }}
                    ?>
                    </div>
                <?php
                    }}}}
                ?>
            <?php } else if ($page == "Live") { ?>
                <h3 class="heading">LIVE CLASSROOM</h3>
            <?php } else if ($page == "Material") { ?>
                <h3 class="heading">CLASS MATERIAL</h3>
            <?php } else if ($page == "Schedule") { ?>
                <h3 class="heading">CLASS SCHEDULE</h3>
            <?php } else if ($page == "Report") { ?>
                <h3 class="heading">PERFORMANCE REPORT</h3>
            <?php } else if ($page == "Video") { ?>
                <?php
                $i = 0;
                $stamp = time();
                $player = mysqli_real_escape_string($link, $_GET['player']);
                $sub = mysqli_real_escape_string($link, $_GET['subject']);
                $link = mysqli_connect("shareddb-u.hosting.stackcp.net", "optimus-3133335204", "superlol@123", "optimus-3133335204");
                $sql1 = "SELECT * FROM `subject` WHERE uid = '$sub'";
                $player = str_replace("HR","",$player);
                $player = (int)$player;
                $vid = $player - 9001;
                $result1 = $link->query($sql1);
                if ($result1->num_rows > 0) {
                    while($row1 = $result1->fetch_assoc()){
                        if($i == $vid){
                            if (($row1['etime'] > $stamp) && ($row1['stime'] <= $stamp)) {
                ?>
                    <h4 class="hell">&nbsp;Toppic:&nbsp;<?php echo ($row1['toppic']); ?></h4>
                    <video id="my-video" class="video-js vid" width="864" height="486" data-setup="{}" controls preload="auto" poster="<?php echo($row1['linkp']); ?>">
                        <source src="<?php echo ($row1['linkv']) ?>" type="video/mp4" />
                    </video>
                <?php 
                            }
                        break;
                        }
                        $i++;
                    }
                }
                ?>
            <?php } ?>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
    <script>
        $(window).on("load", () => {
            document.getElementById("loader").style.display = "none";
            document.getElementById("main").style.display = "block";
            setTimeout(playvid, 1000);
        });

        function playvid() {
            try {
                document.querySelector("video").play();
            } catch {

            }
        }

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
    <script src="https://vjs.zencdn.net/7.7.6/video.js"></script>
</body>

</html>