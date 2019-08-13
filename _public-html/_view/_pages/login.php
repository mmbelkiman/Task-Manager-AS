<!-- CSS -->
<!-- JS -->
<script type="text/javascript" src="_view/_js/login.js"></script>
<script type="text/javascript" src="http://www.google.com/recaptcha/api/js/recaptcha_ajax.js"></script>

<div class="container margin-basic">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<img src="_images/logotype_as.png" alt="Alundra System" class="img-responsive logotype-as">
			</div>
		</div>
		<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<div class="page-header">
					<h1 class="text-center">Alundra System
					<br>
					</h1>
				</div>
				<form class="form-horizontal" role="form">
					<div class="form-group">
						<label class="col-sm-2 control-label">Login</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" id="loginUser" placeholder="Your login">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">Password</label>
						<div class="col-sm-10">
							<input type="password" class="form-control" id="passwordUser" placeholder="Your Password">
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-10">
							<div class="checkbox">
								<label>Remember me</label>
								<input type="checkbox" id="chkRememberMe">
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-10">
							<div id="captcha-wrap"></div>
							<button type="button" class="btn btn-default" id="btnLoginSubmit">
								Sign in
							</button>
						</div>
						<div class="col-md-12">
							<a href="http://validator.w3.org/check?uri=http%3A%2F%2Fwww.alundrasystem.byethost17.com%2Flogin.php"
							target="_blank"> <img src="_images/html5_white.png" alt="I ❤ validation" class="img-responsive html5-validator"> </a>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>