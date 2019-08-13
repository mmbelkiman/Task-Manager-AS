<!-- CSS -->
<!-- JS -->
<script type="text/javascript" src="_view/_js/users-registration.js"></script>

<div class="container margin-basic">

	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<h1>User Registration</h1>
				<hr>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<img id="imageUser" src="http://placehold.it/1024x600" class="img-responsive">
				<input name="image" type="file" accept="image/x-png, image/jpeg" class="image">
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label>Name*</label>
					<input class="form-control validate-field" id="firstNameRegistration" placeholder="Enter first name" type="text" data-original-title="First name here!">
					<br>
					<input class="form-control validate-field" id="lastNameRegistration" placeholder="Enter last name" type="text" data-original-title="Last name here!">
				</div>
				<div class="form-group">
					<label>E-mail*</label>
					<input class="form-control validate-field" id="emailRegistration" placeholder="Enter email" type="email">
				</div>
				<div class="form-group">

					<label>Jobs</label>
					<br>
					<div id="chkJobs">
						No jobs registered
					</div>
				</div>
				<div class="form-group">
					<label>Username*</label>
					<input class="form-control validate-field" id="usernameRegistration" placeholder="Enter username" type="text">
				</div>
				<div class="form-group">
					<label>Password*</label>
					<input class="form-control validate-field" id="passwordRegistration" placeholder="Enter password" type="password">
					<br>
					<input class="form-control validate-field" id="passwordAgainRegistration" placeholder="Enter password again" type="password">
				</div>
				<div class="form-group">
					<label>Recover Settings</label>
					<input class="form-control" id="secretQuestionRegistration" placeholder="Enter secret question" type="text" title="Put a question that may be asked in case you have forgotten your credentials">
					<br>
					<input class="form-control" id="secretAnswerRegistration" placeholder="Enter secret answer" type="text" title="Put the correct answer regarding the previous question.">
				</div>
				<button type="submit" id="btnSubmit" class="btn btn-default">
					Submit
				</button>
			</div>
		</div>
	</div>

</div>
