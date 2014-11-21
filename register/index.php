<?php
session_start();
require_once 'functions.php';
$show = null;
$error = null;

if (isset($_GET['log']) && $_GET['log'] == 0) {
	session_unset($_SESSION['rid']);
	header("Location: index.php");
}

if (isset($_SESSION['rid'])) {
	header("Location: events.php");
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {

	if (isset($_POST['forgotpass'])) {
		$query = mysqli_query($con, "SELECT password FROM users WHERE email = '$_POST[email]'");

		if (mysqli_num_rows($query) != 0) {
			$fetch = mysqli_fetch_assoc($query);

	        $msg = "Hey,\nYour password is".$fetch['password'].".\n\nFor regular updates, follow us on http://www.facebook.com/RhapsodyIITR\n\nCheers,\nTeam Rhapsody";
	        $subject = 'Forgot your password';
	        $from = 'Rhapsody@pravaah.org';
	        $header = 'From: '.$from;
	        mail($email, $subject, $msg, $header);
			$show = "mailed";
		} else{
			$show = "invalid";
		}
	} elseif (isset($_POST['login'])) {
		$rid = clean_input($_POST['rid_login'], $con);
	    $pass = newHash($_POST['pass_login']);
	    $query = mysqli_query($con, "SELECT verified FROM users WHERE rid = '$rid' AND password = '$pass'");

	    if (mysqli_num_rows($query) != 0) {
	    	$fetch = mysqli_fetch_assoc($query);

	    	if ($fetch['verified'] == 0) {
	    		$show = "notverified";
	    	} else if ($fetch['verified'] == 1) {
		    	$_SESSION['rid'] = $rid;
		    	header("Location: events.php");
	    	}
	    } else{
	    	$query = mysqli_query($con, "SELECT verified FROM users WHERE rid = '$rid' AND password = '$_POST[pass_login]'");

	    	if (mysqli_num_rows($query) != 0) {
		    	$fetch = mysqli_fetch_assoc($query);

		    	if ($fetch['verified'] == 0) {
		    		$show = "notverified";
		    	} else if ($fetch['verified'] == 1) {
			    	$_SESSION['rid'] = $rid;
			    	header("Location: events.php");
		    	}
		    }else{
		    	$show = "invalid";
		    }
	    }
	}elseif (isset($_POST['rhapsodyid'])) {
		$email = clean_input($_POST['email'], $con);
		$query = mysqli_query($con, "SELECT rid FROM users WHERE email = '$email'");
		if (mysqli_num_rows($query) != 0) {
			$fetch = mysqli_fetch_assoc($query);

	        $msg = "Hey,\nYour Rhapsody id is ".$fetch['rid'].".\n\nFor regular updates, follow us on http://www.facebook.com/RhapsodyIITR\n\nCheers,\nTeam Rhapsody";
	        $subject = 'Forgot your Rhapsody ID';
	        $from = 'Rhapsody@pravaah.org';
	        $header = 'From: '.$from;
	        mail($email, $subject, $msg, $header);
	        $show = "mailed";
		} else{
			$show = "invalid";
		}
	}else{
	    $name = clean_input($_POST['name'], $con);
	    $pass = newHash($_POST['password']);
	    $college = clean_input($_POST['college'], $con);
	    $email = clean_input($_POST['email'], $con);
	    $contact = clean_input($_POST['contact'], $con);
	    $location = clean_input($_POST['location'], $con);
	    $gender = clean_input($_POST['gender'], $con);

	    $query = mysqli_query($con, "SELECT email FROM users WHERE email = '$email'") or die(mysqli_error());

	    if(mysqli_num_rows($query) == 0) {
	        mysqli_query($con, "INSERT INTO users SET name = '$name', password = '$pass', college = '$college', email='$email', contact = '$contact', location = '$location', gender = '$gender', timestamp = NOW()") or die(mysqli_error());
	        $query = mysqli_query($con, "SELECT uid FROM users WHERE email = '$email'");
	        $fetch = mysqli_fetch_assoc($query);
	        $uid = 1000 + $fetch['uid'];

	        $rid = 'RY-'.$uid;

	        mysqli_query($con, "UPDATE users SET rid = '$rid' WHERE email = '$email'");

	        $verify = newHash($email);
			$link = "http://rhapsody.pravaah.org/register/verify.php?email=".$email."&verify=".$verify;
	        $msg = "Hey,\nYou have been successfully registered for Rhapsody, the Annual Spring Cultural Fest of IIT Roorkee. Your Rhapsody id is ".$rid.".\nClick on the following link to verify your email address:\n".$link."\n\nFor regular updates, follow us on http://www.facebook.com/RhapsodyIITR\n\nRegards,\nTeam Rhapsody";
	        $subject = 'Rhapsody, IIT Roorkee';
	        $from = 'Rhapsody@pravaah.org';
	        $header = 'From: '.$from;
	        mail($email, $subject, $msg, $header);
	        $show = "accepted";
	    }
	    else {
	    	$show = "rejected";
	    }
	}
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Registration - Rhapsody'14</title>
        <link rel="stylesheet" type="text/css" href="css/default.css" />
		<link rel="stylesheet" type="text/css" href="css/component.css" />
		<script type="text/javascript" src="js/jquery.js"></script>
		<script type="text/javascript" src="js/modernizr.custom.js"></script>
    </head>
    <style type="text/css">
    select option{
    	color: #10689a;
    }
    .help {
        color: red;
        font-style: italic;
    }
    td{
    	min-width: 200px;
    	vertical-align: initial;
    }
    .login, .register, .rhapsodyid, .forgotpass{
    	float: left;
    }
    .login, .rhapsodyid, .forgotpass{
    	border: 1px solid;
    	margin-bottom: 10px; 
    }
    .rejected, .accepted, .invalid, .notverified, .mailed { padding: 0.5em; display: none; border: 2px solid rgba(200,0,0,0.5);  background: rgba(200,0,0,0.5); border-radius: 8px; font-size: 12px; margin: auto; width: 370px; text-align: center; }
	.accepted, .mailed { border: 2px solid rgba(0,150,0,0.5);  background: rgba(0,150,0,0.5); }
    </style>
    <script type="text/javascript">
        function validateNonEmpty(inputField, helpText) {
            if(inputField.value.length == 0) {
                if(helpText != null)
                	helpText.innerHTML = "This field is empty";          
                return false;
            }
            
            else {
                if(helpText != null)
                	helpText.innerHTML = "";
                return true;
            }
            
        }
        
        function registerUser(form) {
            if(validateNonEmpty(form['name'], form['name_help']) && validateNonEmpty(form['password'], form['pass_help']) && validatePassword(form['cpassword'], form['cpass_help']) && validateNonEmpty(form['college'], form['college_help']) && validateEmail(form['email'], form['email_help']) && validateNonEmpty(form['contact'], form['contact_help']) && validateNonEmpty(form['location'], form['location_help']))
                form.submit();
            else
            	alert("All fields are compulsory !");
        }
        
        function validateEmail(inputField, helpText) {
            var x=document.forms["myForm"]["email"].value;
            var atpos=x.indexOf("@");
            var dotpos=x.lastIndexOf(".");
            if (atpos<1 || dotpos<atpos+2 || dotpos+2>=x.length){
                helpText.innerHTML = "Not a valid e-mail address";
                return false;
            }
            return true;
      }
      
      	function validatePassword(inputField, helpText) {
            if(inputField.value.length == 0) {
                if(helpText != null)
                	helpText.innerHTML = "This field is empty";
                return false;
            } else {
                if(helpText != null)
                	helpText.innerHTML = "";
                var pass = document.forms['myForm']['password'].value;
                var cpass = inputField.value;
                if(pass != cpass) {
                    helpText.innerHTML = "Passwords do not match";
                    return false;
                }
                return true;
            }
      	}
    </script>
    
    <body>
    <div class="container">
        <header class="clearfix">
			<h1>Rhapsody'14</h1>
		</header>

		<div class="accepted">You have successfully registered for Rhapsody 2014.<br /> Please check your mailbox and verify your email address.</div>
		<div class="rejected">Oops! The email address you registered has already been registered.</div>
		<div class="invalid">Invalid Credentials !</div>
		<div class="notverified">Oops! The email address isn't verified. Please check your mailbox and verify it first.</div>
		<div class="mailed">Your details have been sent to your email address.</div>
        <div class="main">
        	<div class="register">
			<form name="myForm" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="cbp-mc-form" autocomplete="off">
				<div class="cbp-mc-column">
			        <table>
			        	<tr>
			        		<td><label>Name</label></td>
			        		<td><input type="text" name="name" id="name" placeholder="Name" onblur="validateNonEmpty(this, document.getElementById('name_help'))"/></td>
			        		<td><span id="name_help" class="help"></span></td>
			        	</tr>
			        	<tr>
			        		<td><label>Password</label></td>
			        		<td><input type="password" name="password" id="password" placeholder="Password" onblur="validateNonEmpty(this, document.getElementById('pass_help'))"/></td>
			        		<td><span id="pass_help" class="help"></span></td>
			        	</tr>
			        	<tr>
			        		<td><label>Confirm Password</label></td>
			        		<td><input type="password" name="cpassword" id="cpassword" placeholder="Confirm Password" onblur="validatePassword(this, document.getElementById('cpass_help'))"/></td>
			        		<td><span id="cpass_help" class="help"></span></td>
			        	</tr>
			        	<tr>
			        		<td><label>Email Address</label></td>
			        		<td><input type="email" name="email" id="email" placeholder="Email Address" onblur="validateEmail(this, document.getElementById('email_help'))"/></td>
			        		<td><span id="email_help" class="help"></span></td>
			        	</tr>
			        	<tr>
			        		<td><label>Contact no.</label></td>
			        		<td><input type="text" name="contact" id="contact" placeholder="Mobile No." onblur="validateNonEmpty(this, document.getElementById('contact_help'))"/></td>
			        		<td><span id="contact_help" class="help"></span></td>
			        	</tr>
			        	<tr>
			        		<td><label>Gender</label></td>
			        		<td>
			        			<select name="gender" id="gender" placeholder="Gender">
				        			<option value="Male">Male</option>
				        			<option value="Female">Female</option>
			        			</select>
			        		</td>
			        		<td><span id="gender_help" class="help"></span></td>
			        	</tr>
			        	<tr>
			        		<td><label>College</label></td>
			        		<td><input type="text" name="college" id="college" placeholder="College" onblur="validateNonEmpty(this, document.getElementById('college_help'))"/></td>
			        		<td><span id="college_help" class="help"></span></td>
			        	</tr>
			        	<tr>
			        		<td><label>Location</label></td>
			        		<td><input type="text" name="location" id="location" placeholder="Location" onblur="validateNonEmpty(this, document.getElementById('location_help'))"/></td>
			        		<td><span id="location_help" class="help"></span></td>
			        	</tr>
			        </table>
			        <div class="cbp-mc-submit-wrap"><input class="cbp-mc-submit" type="button" name="button" value="Register" onclick="registerUser(this.form)" /></div>
			    </div>
        	</form>
        	</div>

        	 <!--Login form starts here-->
        <div class="login">
        	<form name="loginForm" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="cbp-mc-form" autocomplete="off">
				<div class="cbp-mc-column">
			        <table>
			        	<tr>
			        		<td><label>Rhapsody ID</label></td>
			        		<td><input type="text" name="rid_login" id="rid_login" placeholder="RY-XXXX" required/></td>
			        	</tr>
			        	<tr>
			        		<td><label>Password</label></td>
			        		<td><input type="password" name="pass_login" id="pass" placeholder="Password" required/></td>
			        	</tr>
			        </table>
			        <div class="cbp-mc-submit-wrap"><input class="cbp-mc-submit" type="submit" name="login" value="Login" /></div>
			    </div>
        	</form>
        </div>
        <!--Login form ends here-->

        <!--Forgot Rhapsody ID form starts here-->
        <div class="rhapsodyid">
        	<form name="loginForm" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="cbp-mc-form" autocomplete="off">
				<div class="cbp-mc-column">
			        <table>
			        	<tr>
			        		<td><label>Email Address</label></td>
			        		<td><input type="text" name="email" id="email" placeholder="Email Address" required/></td>
			        	</tr>
			        </table>
			        <div class="cbp-mc-submit-wrap"><input class="cbp-mc-submit" type="submit" name="rhapsodyid" value="Know your Rhapsody ID" /></div>
			    </div>
        	</form>
        </div>
        <!--Forgot Rhapsody ID form ends here-->

        <!--Forgot Password form starts here-->
        <div class="forgotpass">
        	<form name="loginForm" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="cbp-mc-form" autocomplete="off">
				<div class="cbp-mc-column">
			        <table>
			        	<tr>
			        		<td><label>Email Address</label></td>
			        		<td><input type="text" name="email" id="email" placeholder="Email Address" required/></td>
			        	</tr>
			        </table>
			        <div class="cbp-mc-submit-wrap"><input class="cbp-mc-submit" type="submit" name="forgotpass" value="Reset Password" /></div>
			    </div>
        	</form>
        </div>
        <!--Forgot Password form ends here-->
        
        </div>
    </div>
    <?php
    if ($show == "accepted")
		echo "<script type='text/javascript'>$('.accepted').show();</script>";
	elseif ($show == "rejected")
		echo "<script type='text/javascript'>$('.rejected').show();</script>";
	elseif ($show == "invalid")
		echo "<script type='text/javascript'>$('.invalid').show();</script>";
	elseif ($show == "notverified")
		echo "<script type='text/javascript'>$('.notverified').show();</script>";
	elseif ($show == "mailed")
		echo "<script type='text/javascript'>$('.mailed').show();</script>";
	?>
    </body>
</html>