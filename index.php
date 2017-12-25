<?php 
	$conn = mysqli_connect('localhost', 'root', '', 'dislike_like');


	if (!$conn) {
		die("Error connecting to database: " . mysqli_connect_error($conn));
		exit();
	}

	// if user clicks like or dislike button
	if (isset($_POST['action'])) {
		$user_id = $_POST['user_id'];
		$post_id = $_POST['post_id'];
		$action = $_POST['action'];

		switch ($action) {
			case 'like':
				$sql = "INSERT INTO rating_info (user_id, post_id, rating_action) 
						VALUES (".$user_id .", " . $post_id . ",  'like')";
				break;
			case 'dislike':
				$sql = "INSERT INTO rating_info (user_id, post_id, rating_action) 
						VALUES (".$user_id .", " . $post_id . ",  'like')";
				break;
			
			default:
				
				break;
		}


		// execute query to effect changes in the database ...
		mysqli_query($conn, $sql);

		exit(0);
	}

	$sql = "SELECT * FROM posts";

	$result = mysqli_query($conn, $sql);

	// fetch all posts from database
	// return them as an associative array called $posts
	$posts = mysqli_fetch_all($result, MYSQLI_ASSOC);

	function getLikes($id)
	{
		global $conn;

		$sql = "SELECT COUNT(post_id) 
					FROM rating_info 
					WHERE post_id=" . $id . 
					"AND rating_action=1";

		$result = mysqli_query($conn, $sql);
		return mysqli_fetch_array($result)[0];
	}

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Like and Dislike system</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<style>
		.posts-wrapper {
			width: 50%;
			margin: 20px auto;
			border: 1px solid #eee;
		}
		.post {
			width: 90%;
			margin: 20px auto;
			padding: 10px 5px 0px 5px;
			border: 1px solid green;
		}
		.post-info {
			margin: 10px auto 0px;
			padding: 5px;
		}
		.fa {
			font-size: 1.2em;
		}
		.fa-thumbs-down, .fa-thumbs-o-down {
			transform:rotateY(180deg);
		}
		.logged_in_user {
			padding: 10px 30px 0px;
		}
	</style>
</head>
<body>
	<div class="posts-wrapper" style="border: 1px solid red;">
		<div class="logged_in_user">
			<label>Logged in User ID:</label>
			<select onChange="return setLoggedInUser()" id="logged_in_user_id">
				<option selected disabled>Select logged in user</option>
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
			</select>
		</div>

		<?php foreach ($posts as $post): ?>
			<div class="post">
				<?php echo $post['text']; ?>

				<div class="post-info">
					
					<i 
					  class="fa fa-thumbs-o-up like-btn" 
					  data-id="<?php echo $post['id'] ?>"></i>

					<span><?php echo $post['id']; ?></span>

					&nbsp;&nbsp;&nbsp;&nbsp;
					<i class="fa fa-thumbs-o-down dislike-btn" data-id="<?php echo $post['id'] ?>"></i>
					<!-- <i class="fa fa-thumbs-o-down dislike-btn"></i> -->
					<span>2</span>
				</div>
			</div>
		<?php endforeach ?>

	</div>
</body>
</html>
<script>
	var user_id = null;

	function setLoggedInUser() {
		user_id = $('#logged_in_user_id').val();
	}

	$(document).ready(function(){

		// if the user clicks on the like button ...
		$('.like-btn').on('click', function(){
			var post_id = $(this).data('id');
			$action = $(this);
			console.log($action);
			
			$.ajax({
				url: 'index.php',
				type: 'post',
				data: {
					'action': 'like',
					'post_id': post_id,
					'user_id': user_id
				},
				success: function(res){
					$action.removeClass('fa-thumbs-o-up');
					$action.addClass('fa-thumbs-up');
					console.log(res);
				}
			})			

		});


		// if the user clicks on the dislike button ...
		$('.dislike-btn').on('click', function(){
			var post_id = $(this).data('id');
			var action = $(this);
			
			$.ajax({
				url: 'index.php',
				type: 'post',
				data: {
					'action': 'dislike',
					'post_id': post_id,
					'user_id': user_id
				},
				success: function(res){
					alert(res);
				}
			});	

		});



	});

</script>