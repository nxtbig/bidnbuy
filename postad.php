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
        $baseprice = $_POST['baseprice'];
        $tags = $_POST['tags'];
        
        if (isset($_FILES['images']))
        {
            if (!file_exists('ads/'.md5($title))) {
                mkdir('ads/'.md5($title), 0777, true);
            }
        }
        for ($i=0;$i<sizeof($_FILES['images']);$i++) {
            $name = $_FILES['images']['name'][$i];
            $file_tmp = $_FILES['images']['tmp_name'][$i];
            echo "<br/>";
        }
        die();    
   }

   ?>
<!DOCTYPE html>
<head>
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
   <title>Hello,<?php echo $userRow['username']; ?></title>
   <link rel="stylesheet" href="assets/css/bootstrap.min.css" type="text/css"/>
   <link rel="stylesheet" href="assets/css/index.css" type="text/css"/>
   <link rel="stylesheet" href="assets/css/view.css" type="text/css"/>
</head>
<body>
   <!-- Navigation Bar-->
   <nav class="navbar navbar-default navbar-fixed-top">
      <div class="container">
         <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar"
               aria-expanded="false" aria-controls="navbar">
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
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                     aria-expanded="false">
                  <span
                     class="glyphicon glyphicon-user"></span>&nbsp;Logged
                  in: <?php echo $userRow['email']; ?>
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
      <div id="form_container">
         <form class="appnitro" enctype="multipart/form-data" method="post" action="postad.php">
            <div class="form_description">
               <h2>Post an Ad</h2>
            </div>
            <ul >
               <li id="li_1" >
                  <label class="description" for="title">Title </label>
                  <div>
                     <input id="title" name="title" class="element text medium" type="text" maxlength="255" value=""/> 
                  </div>
               </li>
               <li id="li_2" >
                  <label class="description" for="description">Description </label>
                  <div>
                     <textarea id="description" name="description" class="element text medium"></textarea>
                  </div>
               </li>
               <li id="li_3" >
                  <label class="description" for="images">Upload Images </label>
                  <div>
                     <input id="images" name="images[]" class="element file" type="file" multiple /> 
                  </div>
               </li>
               <li id="li_4" >
                  <label class="description" for="baseprice">Base Price </label>
                  <span class="symbol">Rs</span>
                  <span>
                  <input id="baseprice" name="baseprice" class="element text currency" size="10" value="" type="text" />
                  </span>
               </li>
               <li id="li_5" >
                  <label class="description" for="tags">Tags </label>
                  <div>
                     <input id="tags" name="tags" class="element text medium" type="text" maxlength="255" value=""/> 
                  </div>
               </li>
               <li class="buttons">
                  <input id="saveForm" class="button_text" type="submit" name="submit" value="Submit" />
               </li>
            </ul>
         </form>
      </div>
   </div>