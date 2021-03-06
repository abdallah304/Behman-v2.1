<?php
	session_start();
	$pageTitle = "Profile" ; 
	$noNavbar = ''; 
	include("initialize.php");

	if(!isset($_SESSION['user_email'])){
		header("location: index.php");
	}else{ 
?>

<head>
	<link rel="stylesheet" href="layout/css/Bootstrap v3.3.7 bootstrap.min.css"/>
</head>
<?php

	$user_id = isset($_GET['u_id']) && is_numeric($_GET['u_id']) ? intval($_GET['u_id']) : 0 ;

	$stmt = $con->prepare("SELECT * FROM users WHERE user_id = ? AND type = '1'");
	$stmt->execute(array($user_id));
	$row = $stmt->fetch();
	$count = $stmt->rowCount();
	if($count > 0 ){
		$id = $row['user_id'];
		$name = $row['user_name'];
		$f_name = $row['f_name'];
		$l_name = $row['l_name'];
		$describe_user = $row['describe_user'];
		$gender = $row['user_gender'];
		$register_date = $row['user_reg_date'];
		$user_country = $row['user_country'];
		$Relationship_status = $row['Relationship'];
		$user_birthday = $row['user_birthday'];
		$user_image = $row['user_image'];
		$user_cover = $row['user_cover'];

		/* Check IF User Is Owner User */
		$user = $_SESSION['user_email'];
		$get_user  = $con->prepare("SELECT * from users where user_email='$user'");
		$get_user  ->execute();
		$row = $get_user  ->fetch();	
		$userown_id = $row['user_id'];
		$user_name = $row['user_name'];

?>

<div class="container">
	<!-- Start Cover And Pic Profile Area -->
	<div class="row">
		<div class="col-sm-1"> </div>
		<div class="col-sm-10">
      		<div>
				<div><img id='cover-img' class='img-rounded' src='includes/images/cover/<?php if(!empty($user_cover)){ echo $user_cover; }else{ echo 'default_cover.png'; } ?>' alt='cover'/></div>
				<?php if($user_id == $userown_id){ ?>
				<form action='doc_profile.php?u_id=<?php echo $user_id ?>' method='post' enctype='multipart/form-data'>
					<ul class='nav pull-left' style='position: absolute;top: 10px;left: 40px;'>
				    	<li class='dropdown'>
				        	<button class='dropdown-toggle btn btn-default' data-toggle='dropdown'>Change Cover</button> 
				        	<div class='dropdown-menu'>
				        		<center>
				        		<p> Click <strong>Select Cover</strong> and then click the <br> <strong>Update Cover</strong></p>
				            	<label class='btn btn-info'> Select Cover
						        <input type='file' name='user_cover' size='60' />
						        </label><br><br>
				                <button name='submit' class='btn btn-info'>Update cover</button>
				            	</center>
				            </div>
				        </li>
				    </ul>
	          	</form>
	          	<?php }?>
			</div>
         	<div id='profile_img'>
	            <img src='includes/images/users/<?php if(!empty($user_image)){ echo $user_image; }else{ echo 'default.png'; } ?>' alt='Profile' class='img-circle' width='180px' height='180px' />
	            <?php if($user_id == $userown_id){ ?>
	            <form action='doc_profile.php?u_id=<?php echo $user_id ?>' method='post' enctype='multipart/form-data'>
					<ul class='nav pull-left' style='position: absolute; bottom: 3px;left: 26px'>
				    	<li class='dropdown'>
				        	<button class='dropdown-toggle btn btn-default' data-toggle='dropdown'>Change Picture</button> 
				        	<div class='dropdown-menu'>
				        		<center>
				        		<p> Click <strong>Select Picture</strong> and then click the <br> <strong>Update Picture</strong></p>
				            	<label class='btn btn-info'> Select Cover
						        <input type='file' name='u_image' size='60' />
						        </label><br><br>
				                <button name='update' class='btn btn-info'>Update Profile</button>
				            	</center>
				            </div>
				        </li>
				    </ul>
	          	</form>
	          	<?php }?>
          	</div><br>
	    </div>
	    <?php 
        if(isset($_POST['submit'])){
	        $user_cover = $_FILES['user_cover']['name'];
	        $image_tmp = $_FILES['user_cover']['tmp_name'];
	        $random_number = rand(1,100);

          	if($user_cover==''){
            	echo "<script>alert('Please Select Cover Image!')</script>";
            	echo "<script>window.open('doc_profile.php?u_id=$user_id','_self')</script>";
            	exit();
          	}else{              
              	move_uploaded_file($image_tmp,"includes/images/cover/$user_cover.$random_number");
			  	$update = $con->prepare("UPDATE users SET user_cover='$user_cover.$random_number' WHERE user_id='$user_id' ");
			  	$update ->execute();                
             	if($update){
              	echo "<script>alert('Your Cover Updated!')</script>";
              	echo "<script>window.open('doc_profile.php?u_id=$user_id','_self')</script>";
              	}
            }
        }
	    if(isset($_POST['update'])){
		    $u_image = $_FILES['u_image']['name'];
		    $image_tmp = $_FILES['u_image']['tmp_name'];
		    $random_number = rand(1,100);
     	    if($u_image==''){
        	    echo "<script>alert('Please Select Profile Image on clicking on the profile image area!')</script>";
            	echo "<script>window.open('doc_profile.php?u_id=$user_id','_self')</script>";
           		exit();
          	}else{  
              	move_uploaded_file($image_tmp,"includes/images/users/$u_image.$random_number");
			  	$update = $con->prepare("UPDATE users SET user_image = '$u_image.$random_number' WHERE user_id = '$user_id' ");
			  	$update ->execute();                
              if($update){
              	echo "<script>alert('Your Profile Updated!')</script>";
              	echo "<script>window.open('doc_profile.php?u_id=$user_id','_self')</script>";
              }
            }
        }
	    ?>
	    <div class="col-sm-1"> </div>
	</div>
	<!-- End Cover And Pic Profile Area -->

	<!-- Start User Information -->
	<div class='row'>
		<div class='col-sm-1'> </div>
	<center>
		<div  class='col-sm-3' style='background-color: #e6e6e6;'>
		<h2>About</h2>   	
		<ul class='list-group'>
			<li class='list-group-item' title='Username'><strong>Name : <?php echo $f_name .' '. $l_name; ?></strong></li>
		  	<li class='list-group-item' title='Bio'>Bio : <?php echo $describe_user ?></li>
		  	<li class='list-group-item' title='Gender'>Gende : <?php echo $gender?></li>
		  	<li class='list-group-item' title='Country'>Country : <?php echo $user_country?></li>
		  	<li class='list-group-item' title='Re_Status'>Relation Status : <?php echo $Relationship_status?></li>
		  	<li class='list-group-item' title='Birth_date'>Birth Date : <?php echo $user_birthday?></li>
		  	<li class='list-group-item' title='Registration Date'>Since : <?php echo $register_date?></li>
		</ul>
	<?php		
		if($user_id == $userown_id){
			echo"<a href='edit_profile.php?u_id=$userown_id' class='btn btn-success'/>Edit Profile</a><br><br><br>";
		}
	?>
		</div>
	</center>
	<!-- End User Information -->

	<!-- Start Displaying Users own Posts-->
	<div class="col-sm-8">
		<?php
			$get_posts = $con->prepare("SELECT * from posts where user_id='$user_id' ORDER by 1 DESC LIMIT 5");
			$get_posts ->execute();
			$posts = $get_posts ->fetchAll();	
			
			foreach ($posts as $key => $row_posts){

				$post_id = $row_posts['post_id'];
				$user_id = $row_posts['user_id'];
				$content = $row_posts['post_content'];
				$post_date = $row_posts['post_date'];
			
				$get_user = $con->prepare("SELECT * from users where user_id='$user_id' AND posts='yes'");
				$get_user ->execute();
				$user = $get_user ->fetch();				
				$user_name = $user['user_name'];
				$user_image = $user['user_image'];	 
		?>
		<div id='own_posts'>
			<div class='row'>
				<div class='col-sm-3'>
					<p><img src='includes/images/users/<?php if(!empty($user_image)){ echo $user_image; }else{ echo 'default.png'; } ?>' class='img-circle' width='100px' height='100px'></p>
				</div>
				<div class='col-sm-8'>
					<h3><a style='text-decoration: none;cursor: pointer;color: #3897f0;margin-left:15px ' href='doc_profile.php?u_id=$user_id'><?php echo $user_name?></a></h3>
					<h4><small style='color:black; margin-left:15px'>Updated a post on <strong><?php echo $post_date?></strong></small></h4>
				</div>
				<div class='col-sm-1'> </div>
			</div>
			<div class='row'>
				<div class='col-sm-1'> </div>
				<div class='col-sm-10'>
					<h3><p><?php echo $content?></p></h3>
				</div>
				<div class='col-sm-1'> </div>
			</div>
			<?php
				if($user_id == $userown_id){
					echo"
					<a href='post.php?do=delete&post_id=$post_id' style='float:right;'><button class='btn btn-danger'>Delete</button></a>
					<a href='post.php?do=edit&post_id=$post_id' style='float:right;margin-right:2px;'><button  class='btn btn-info'>Edit</button></a>
					<a href='postDetails.php?post_id=$post_id' style='float:right;margin-right:2px;'><button class='btn btn-success'>View</button></a>
					";
				} 	
			?>

		</div><br>
		<?php } ?>			
	</div>
</div>
<!--End Displaying Users own Posts-->
<?php }else{
	    echo "<script>alert('There Is No ID Exist !')</script>";
        echo "<script>window.open('home.php','_self')</script>";
} ?>
<?php } ?>

<?php
	include 'includes/templates/footer.php';				
?>