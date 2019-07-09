<?= view('Myth\Auth\Views\_header') ?>

<div class="container">
	<div class="row">
		<div class="col-sm-6 offset-sm-3">

			<div class="card">
				<h2 class="card-header">Login</h2>
				<div class="card-body">

					<?= view('Myth\Auth\Views\_message_block') ?>

					<form action="<?= route_to('login') ?>" method="post">
						<?= csrf_field() ?>

<?php if ($config->validFields == ['email']): ?>
						<div class="form-group">
							<label for="login">Email address</label>
							<input type="email" class="form-control <?php if(session('errors.login')) : ?>is-invalid<?php endif ?>"
								   name="login" placeholder="Enter email">
							<div class="invalid-feedback">
								<?= session('errors.login') ?>
							</div>
						</div>
<?php else: ?>
						<div class="form-group">
							<label for="login">Email or username</label>
							<input type="text" class="form-control <?php if(session('errors.login')) : ?>is-invalid<?php endif ?>"
								   name="login" placeholder="Enter email or username">
							<div class="invalid-feedback">
								<?= session('errors.login') ?>
							</div>
						</div>
<?php endif; ?>

						<div class="form-group">
							<label for="password">Password</label>
							<input type="password" name="password" class="form-control  <?php if(session('errors.password')) : ?>is-invalid<?php endif ?>" placeholder="Password">
							<div class="invalid-feedback">
								<?= session('errors.password') ?>
							</div>
						</div>

						<div class="form-check">
							<label class="form-check-label">
								<input type="checkbox" name="remember" class="form-check-input" <?php if(old('remember')) : ?> checked <?php endif ?>>
								Remember me
							</label>
						</div>

						<br>

						<button type="submit" class="btn btn-primary btn-block">Login</button>
					</form>

					<hr>

<?php if ($config->allowRegistration) : ?>
					<p><a href="<?= route_to('register') ?>">Need an account?</a></p>
<?php endif; ?>
					<p><a href="<?= route_to('forgot') ?>">Forgot your password?</a></p>
				</div>
			</div>

		</div>
	</div>
</div>

<?= view('Myth\Auth\Views\_footer') ?>
