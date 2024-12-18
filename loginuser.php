<?php
   include("indexDB.php");
   session_start();
    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
      }
	
    $username='';$password='';$b=true;
	$pass_hash=""; $passErr="";
	$login_attempts=0;
	//$incr_query="";
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        if(isset($_POST['username']))
				$username=test_input($_POST['username']);
				else $b=false;
        if(isset($_POST['password']))
				$password=test_input($_POST['password']);
				else $b=false;
		//secretkey of google recaptcha
		$secretkey = "6Le8G5QqAAAAAErXlpgl1V7m3StPEk70X2-7U7zo";
		$recaptcha_response = $_POST['g-recaptcha-response'];
		$pass_hash = hash("sha256", $password);
		// Verify response of recaptcha
		$verifylink = "https://www.google.com/recaptcha/api/siteverify";
		$response = file_get_contents($verifylink."?secret=".$secretkey."&response=".$recaptcha_response);
		$responseData = json_decode($response);
		
        if(isset($_POST['type']))
						$type=test_input($_POST['type']);
						else $b=false;
				$tablename='';$id='';
				if(empty($_POST['username']))
				$b=false;
				if($b==false)
				{
					header('Location: loginuser.php');
				}
        if($type=='normal')
        {
            $id='uid';
            $tablename='login';
			$incr_query="UPDATE login SET login_count = login_count + 1 WHERE uid = $id";
			// Set secure cookie parameters
			setcookie(
				'PHP_SESSION_ID',
				'user-session',
				[
				'httponly' => true,
				'secure' => true,
				'samesite' => 'Strict'
				]
			);
        }
        else if($type=='builder')
        {
            $id='bid';
            $tablename='login_builder';
			$incr_query="UPDATE login_builder SET login_count = login_count + 1 WHERE bid = $id";
			// Set secure cookie parameters
			setcookie(
				'PHP_SESSION_ID',
				'builder-session',
				[
				'httponly' => true,
				'secure' => true,
				'samesite' => 'Strict'
				]
			);
        }
        if ($responseData->success) 
		{
			
		
			$q="select $id,password_hash, login_count from $tablename where username='$username'";
			
			$result=$conn->query($q);
			if($result==true)
			{
				$row= mysqli_fetch_array($result,MYSQLI_ASSOC);
			}
			else
			{
						header('Location: loginuser.php');
			}
			if($row['password_hash']==$pass_hash)
			{
				
				$_SESSION['login_count'] = 0;
				$_SESSION['username']=$username;
				$_SESSION['type']=$type;
				
				if($id=='uid' && $b==true)
				{
					$_SESSION['id']=$row['uid'];
				   header('Location: normalHomeSale.php');
				}
				if($id=='bid' && $b==true)
				{
					$_SESSION['id']=$row['bid'];
					header('Location: builderHome.php');
				}
				echo $_SESSION['login_count'];
			}
			else
			{
				if ($_SESSION['login_count'] >= 3){
					//$increment = $mysqli->query($incr_query);
					//$increment->bind_param("s", $username);
					//$increment->execute();
					echo "<script>alert('Account Locked out!')</script>";
					$passErr = "Account Locked out!!!!";
				}
				else {
					$_SESSION['login_count']++;
					echo $_SESSION['login_count'];
					$passErr = "Invalid Password!!!!";
					echo "<script>alert('Invalid Password!!!!');</script>";
					header('Location: loginuser.php');
				}
			}
		}
		else {
			echo "<script>alert('Invalid Captcha!')</script>";
		}
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>"Login"</title>
            <meta charset="UTF-8">
            <meta name="description" content="housing-co">
            <meta name="keywords" content="LERAMIZ, unica, creative, html">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <!-- Favicon -->
            <link href="img/favicon.ico" rel="shortcut icon" />

            <!-- Google Fonts -->
            <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro" rel="stylesheet">

            <!-- Stylesheets -->
            <link rel="stylesheet" href="css/bootstrap.min.css" />
            <link rel="stylesheet" href="css/font-awesome.min.css" />
            <link rel="stylesheet" href="css/animate.css" />
            <link rel="stylesheet" href="css/owl.carousel.css" />
            <link rel="stylesheet" href="css/style.css" />
            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>

            <link rel="stylesheet" type="text/css" href="Styles.css">
            <script src="https://www.google.com/recaptcha/api.js" async defer></script>
			

            <!--[if lt IE 9]>
	  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
	  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->

</head>

<body>
    <!-- Page Preloder -->


    <!-- Header section -->
    <header class="header-section">
        <div class="header-top">
            <div class="container">
                <div class="row">

                    <div class="col-lg-6 text-lg-right header-top-right">
                        <div class="top-social">

                            <a href="https://www.facebook.com/"><i class="fa fa-facebook"></i></a>
                            <a href="https://www.twitter.com/"><i class="fa fa-twitter"></i></a>
                            <a href="https://www.instagram.com/"><i class="fa fa-instagram"></i></a>
                            <a href="https://www.pinterest.com/"><i class="fa fa-pinterest"></i></a>
                            <a href="https://www.linkedin.com/"><i class="fa fa-linkedin"></i></a>
                        </div>
						
						<div class="user-panel">
							<a href="register.php"><i class="fa fa-user-circle-o"></i> Register(Normal User)</a>
							<a href="reg_builder.php"><i class="fa fa-user-circle-o"></i> Register(Builder)</a>
						</div>
					</div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="site-navbar">
                        <a href="index.html" class="site-logo"></a>
                        <div class="nav-switch">
                            <i class="fa fa-bars"></i>
                        </div>
                        <ul class="main-menu">
                            <li><a href="index.html">HOME</a></li>

                            <li><a href="about.html">ABOUT US</a></li>
                            <li><a href="contact.html">CONTACT</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!-- Header section end -->

    <style type="text/css">
    body {
        background-repeat: no-repeat;
        background-image: url("img/service-bg.jpg");
        background-size: cover;
        background-attachment: fixed;
        color: white;
    }

    input[type=text],
    input[type=date],
    input[type=password] {
        width: 100%;
        padding: 12px 20px;
        margin: 8px 0;
        box-sizing: border-box;
        background-color: #e0e0d1;
        color: black;
    }

    input[type=submit],
    input[type=reset] {
        background-color: #e0e0d1;
        border: none;
        color: black;
        padding: 16px 32px;
        text-decoration: none;
        margin: 4px 2px;
        cursor: pointer;
        font-weight: bold;
    }

    input[type=radio] {
        height: 15px;
        width: 15px;

    }



    select {
        background-color: #e0e0d1;
        border: none;
        color: black;
        padding: 16px 32px;
        text-decoration: none;
        margin: 4px 2px;
        cursor: pointer;
        font-weight: bold;
    }

    textarea {
        width: 100%;
        padding: 12px 20px;
        margin: 8px 0;
        box-sizing: border-box;
        background-color: #e0e0d1;
        color: black;
    }

    table {
        background-color: black;
        border-collapse: collapse;
        border: 2px solid navy;
    }

    form {
        opacity: 0.7;
    }

    td {
        font-weight: bold;
    }

    span {
        color: red;
    }
    </style>
    </head>


    <br><br><br><br><br><br><br><br><br><br><br>

    <form id="form" method="POST" action="loginuser.php">

        <table cellpadding="7" width="50%" border="0" align="center" cellspacing="2" color="white">

            <!-- I want another button here, center to the tile-->



            <tr>
                <td colspan=2>
                    <center><img src="img/logo1.png"></img></center>
                </td>
            </tr>
            <tr>
                <td colspan=2>
                    <center>
                        <font size=5><b>LOGIN</b></font>
                    </center>
                </td>
            </tr>

            <tr>
                <td><b>USERNAME:</b></td>
                <td><input type="text" name="username" size="30">
                    <span class="error"></span>
                    <br><br>
                </td>
            </tr>


            <tr>
                <td><b>PASSWORD:</b></td>
                <td><input type="password" name="password" size="30">
                    <span class="error"><?php echo $passErr; ?></span>
                    <br><br>
                </td>
            </tr>
			<tr>
				<td>
				<div class="g-recaptcha" data-sitekey="6Le8G5QqAAAAABo02YXdJVx_0pZph0tyVcRRPgl4"></div>
				<span class="error"></span>
				</td>
			</tr>
            <tr>
                <td><b>OPTIONS:</b>
                <td>

                    <select name="type">

                        <option value="normal" selected>NORMAL USER</option>
                        <option value="builder">BUILDER</option>

                    </select>
                    <br><br>
                </td>
            </tr>

            <tr>
 
                <td><input type="submit" value="Login">

                    <div style="font-size:20px; color:#cc0000; margin-top:10px"></div>
                </td>
            </tr>
        </table>
        <br><br><br><br><br><br><br><br><br><br>
    </form>


    <footer class="footer-section set-bg" data-setbg="img/footer-bg.jpg">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6 footer-widget">
                    <img src="img/logo1.png" alt="">
                    <p>We provide you with the best services which is best for your family and which suits your pocket.
                    </p>
                    <div class="social">

                        <a href="https://www.facebook.com/"><i class="fa fa-facebook"></i></a>
                        <a href="https://www.twitter.com/"><i class="fa fa-twitter"></i></a>
                        <a href="https://www.instagram.com/"><i class="fa fa-instagram"></i></a>
                        <a href="https://www.pinterest.com/"><i class="fa fa-pinterest"></i></a>

                    </div>
                </div>
                <div class="col-lg-3 col-md-6 footer-widget">
                    <div class="contact-widget">
                        <h5 class="fw-title">CONTACT US</h5>
                        <p><i class="fa fa-map-marker"></i>You can contact us here...... </p>
                        <p><i class="fa fa-phone"></i>Number</p>
                        <p><i class="fa fa-envelope"></i>info.housing-co@gmail.com</p>
                        <p><i class="fa fa-clock-o"></i>Mon - Sat, 08 AM - 06 PM</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 footer-widget">
                    <div class="double-menu-widget">
                        <h5 class="fw-title">POPULAR PLACES</h5>
                        <ul>
                            <li><a href="">Mumbai</a></li>
                            <li><a href="">Delhi</a></li>
                            <li><a href="">Chennai</a></li>
                            <li><a href="">Kolkata</a></li>
                            <li><a href="">Banglore</a></li>
                        </ul>
                        <ul>
                            <li><a href="">Chandigarh</a></li>
                            <li><a href="">Pune</a></li>
                            <li><a href="">Jaipur</a></li>
                            <li><a href="">Kochi</a></li>
                            <li><a href="">Ooty</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6  footer-widget">
                    <div class="newslatter-widget">
                        <h5 class="fw-title">NEWSLETTER</h5>
                        <p>Subscribe your email to get the latest news and new offer also discount</p>
                        <form class="footer-newslatter-form">
                            <input type="text" placeholder="Email address">
                            <button><i class="fa fa-send"></i></button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </footer>





</body>

</html>
