<!-- CSS -->

<!-- JS -->
<script type="text/javascript" src="_view/_js/page-menu.js"></script>

<div class="navbar navbar-default navbar-fixed-top navbar-inverse">
	<div class="container">

		<div id="divUserLogged">
			<p class="whiteColor" id="userLogged">
				<strong>USER LOGGED: </strong>
			</p>
		</div>

		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="index.php">Alundra System</a>
		</div>

		<div class="collapse navbar-collapse navbar-ex1-collapse">
			<ul class="nav nav-pills navbar-right">

				<li id="home">
					<a href="home.php">Home</a>
				</li>

				<li class="dropdown" id="users">
					<a href="#" data-toggle="dropdown" class="dropdown-toggle">Users <b class="caret"></b> </a>
					<ul class="dropdown-menu">
						<li>
							<a href="users-show.php">Show All/Edit</a>
						</li>
						<li>
							<a href="users-registration.php">Registration</a>
						</li>
					</ul>
				</li>

				<li class="dropdown" id="tasks">
					<a href="#" data-toggle="dropdown" class="dropdown-toggle">Tasks <b class="caret"></b> </a>
					<ul class="dropdown-menu">
						<li>
							<a href="tasks-show.php">Show All/Get</a>
						</li>
						<li>
							<a href="tasks-registration.php">Registration</a>
						</li>
					</ul>
				</li>

				<li id="tests">
					<a href="tests.php">Tests</a>
				</li>

				<li id="reports">
					<a href="#">Reports</a>
				</li>

				<li id="logout">
					<a href="#">Log-Out</a>
				</li>

			</ul>
		</div>

	</div>
</div>