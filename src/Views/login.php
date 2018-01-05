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

                        <div class="form-group">
                            <label for="email">Email address</label>
                            <input type="email" class="form-control <?php if(session('errors.email')) : ?>is-invalid<?php endif ?>"
                                   name="email" aria-describedby="emailHelp" placeholder="Enter email">
                            <div class="invalid-feedback">
                                <?= session('errors.email') ?>
                            </div>
                            <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
                        </div>

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

                    <p><a href="<?= route_to('register') ?>">Need an account?</a></p>

                    <p><a href="<?= route_to('forgot') ?>">Forgot your password?</a></p>
                </div>
            </div>

        </div>
    </div>
</div>

<?= view('Myth\Auth\Views\_footer') ?>
