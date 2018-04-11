<?php
   ob_start();
   session_start();
   require_once 'dbconnect.php';

   if (!isset($_SESSION['user'])) {
       header("Location: login.php");
       exit;
   }
   // select logged in users detail
   $res = $conn->query("SELECT * FROM users WHERE id=" . $_SESSION['user']);
   $userRow = mysqli_fetch_array($res, MYSQLI_ASSOC);

   if (isset($_POST['submit'])) 
   {
        $title = $_POST['title'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $duration = $_POST['duration'];
        $starttime = $_POST['starttime'];
        $tags = $_POST['tags'];

        if (isset($_FILES['images']))
        {
            if (!file_exists('ads/'.md5($title))) {
                mkdir('ads/'.md5($title), 0777, true);
            }
            for ($i=0;$i<sizeof($_FILES['images']['tmp_name']);$i++) {
                $name = $_FILES['images']['name'][$i];
                $file_tmp = $_FILES['images']['tmp_name'][$i];
                move_uploaded_file($file_tmp,'ads/'.md5($title).'/'.$name);
            }
        }
        $loc = md5($title);
        $stmts = $conn->prepare("INSERT INTO ads(user,title,description,tags,images,base_price,start_time,duration) VALUES(?, ?, ?, ?, ?, ?, ?, ?)");
        $stmts->bind_param("ssssssss", $_SESSION['user'], $title, $description, $tags, $loc, $price, $starttime, $duration);
        $res = $stmts->execute();
        $stmts->close();
        header("Location: index.php");
   }

   ?>
    <!DOCTYPE html>
    <html>

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Hello,
            <?php echo $userRow['username']; ?>
        </title>
        <link rel="stylesheet" href="assets/css/bootstrap.min.css" type="text/css" />
        <link rel="stylesheet" href="assets/css/bootstrap-datetimepicker.min.css" type="text/css" />
        <link rel="stylesheet" href="assets/css/index.css" type="text/css" />
        <link href='http://fonts.googleapis.com/css?family=Bitter' rel='stylesheet' type='text/css'>

    </head>

    <body>
        <!-- Navigation Bar-->
        <nav class="navbar navbar-default navbar-fixed-top">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#">BidNBuy</a>
                </div>
                <div id="navbar" class="navbar-collapse collapse">
                    <ul class="nav navbar-nav">
                        <li class=""><a href="index.php">View Ads</a></li>
                        <li class="active"><a href="postad.php">Post Ad</a></li>
                        <li style="padding:8px">
                            <form class="form-inline" style="width:500px">
                                <input class="form-control col-md-12" type="search" placeholder="Search" aria-label="Search" style="width:417px">
                                <button class="btn btn-outline-success" type="submit" style="margin-left:5px">Search</button>
                            </form>
                        </li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                <span class="glyphicon glyphicon-user"></span>&nbsp;Logged in:
                                <?php echo $userRow['email']; ?>
                                    &nbsp;<span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="#"><span class="glyphicon glyphicon-usd"></span>&nbsp;My Bids</a></li>
                                <li><a href="#"><span class="glyphicon glyphicon-th-list"></span>&nbsp;My Ads</a></li>
                                <li><a href="logout.php?logout"><span class="glyphicon glyphicon-log-out"></span>&nbsp;Logout</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="container">
            <div class="form-style-10">
                <h1>Post an Ad Now!<span>Include details and picture for active bidding!</span></h1>
                <form enctype="multipart/form-data" method="post" action="#">
                    <div class="section"><span>1</span>Title, Description &amp; Price</div>
                    <div class="inner-wrap">
                        <label>A catchy title for your Ad
                            <input type="text" name="title" />
                        </label>
                        <label>Describe your Ad here
                            <textarea name="description" rows="5"></textarea>
                        </label>
                        <label>Enter Base Price for your Product
                            <input type="text" name="price" />
                        </label>

                    </div>

                    <div class="section"><span>2</span>Optional Details</div>
                    <div class="inner-wrap">
                        <label>Images &nbsp;
                            <input style="display: inline-block;" type="file" name="images" multiple>
                        </label>
                        <label>Associated Tags with Product (separated by comma)
                            <input type="text" name="tags" />
                        </label>
                    </div>

                    <div class="section"><span>3</span>Schedule</div>
                    <div class="inner-wrap">
                        <label for="dtp_input1" class="control-label">Start Date
                            <div class="input-group date form_datetime" data-date-format="dd MM yyyy - HH:ii p" data-link-field="dtp_input1">
                                <input type="text" value="" readonly>
                                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>

                                <input type="hidden" id="dtp_input1" name="starttime" value="">
                        </label>
                        </div>
                        <br>
                        <label>Duration in days
                            <input type="text" name="duration" />
                        </label>

                    </div>
                    <div class="button-section" style="text-align: center;">
                        <input type="submit" name="submit" value="Post Ad" />
                    </div>
                </form>
            </div>

        </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
        <script src="assets/js/bootstrap.min.js"></script>
        <script src="assets/js/bootstrap-datetimepicker.js"></script>

        <script type="text/javascript">
            $('.form_datetime').datetimepicker({
                //language:  'fr',
                weekStart: 1,
                todayBtn: 1,
                autoclose: 1,
                todayHighlight: 1,
                startView: 2,
                showMeridian: 1,
                pickerPosition: "top-right",
                startDate: new Date(),
            });
        </script>
    </body>

    </html>