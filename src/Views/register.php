<?= view('Myth\Auth\Views\_header') ?>

<div class="container">
    <div class="row">
        <div class="col-sm-6 offset-sm-3">

            <div class="card">
                <h2 class="card-header">Register</h2>
                <div class="card-body">

                    <?= view('Myth\Auth\Views\_message_block') ?>

                    <form action="<?= route_to('register') ?>" method="post">
                        <?= csrf_field() ?>

                        <div class="form-group">
                            <label for="email">Email address</label>
                            <input type="email" class="form-control <?php if(session('errors.email')) : ?>is-invalid<?php endif ?>"
                                   name="email" aria-describedby="emailHelp" placeholder="Enter email" value="<?= old('email') ?>">
                            <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
                        </div>

                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" class="form-control <?php if(session('errors.username')) : ?>is-invalid<?php endif ?>" name="username" placeholder="Johnny Appleseed" value="<?= old('username') ?>">
                        </div>

                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" name="password" class="form-control <?php if(session('errors.password')) : ?>is-invalid<?php endif ?>" placeholder="Password" autocomplete="off">
                        </div>

                        <div class="form-group">
                            <label for="pass_confirm">Password (again)</label>
                            <input type="password" name="pass_confirm" class="form-control <?php if(session('errors.pass_confirm')) : ?>is-invalid<?php endif ?>" placeholder="Password (again)" autocomplete="off">
                        </div>

                        <br>

                        <button type="submit" class="btn btn-primary btn-block">Register</button>
                    </form>


                    <hr>

                    <p>Already registered? <a href="<?= route_to('login') ?>">Sign in</a></p>
                </div>
            </div>

        </div>
    </div>
</div>

<?= view('Myth\Auth\Views\_footer') ?>
