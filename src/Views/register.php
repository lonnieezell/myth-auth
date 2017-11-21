<?= view('Myth\Auth\Views\_header') ?>

<div class="container">
    <div class="row">
        <div class="col-sm-6 offset-sm-3">

            <div class="card">
                <h2 class="card-header">Register</h2>
                <div class="card-body">

                    <form action="<?= route_to('register') ?>" method="post">
                        <?= csrf_field() ?>

                        <div class="form-group">
                            <label for="email">Email address</label>
                            <input type="email" class="form-control" name="email" aria-describedby="emailHelp" placeholder="Enter email">
                            <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
                        </div>

                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" class="form-control" name="username" placeholder="Johnny Appleseed">
                        </div>

                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Password">
                        </div>

                        <div class="form-group">
                            <label for="pass_confirm">Password (again)</label>
                            <input type="password" name="pass_confirm" class="form-control" placeholder="Password (again)">
                        </div>

                        <br>

                        <button type="submit" class="btn btn-primary btn-block">Register</button>
                    </form>


                    <hr>

                    <p>Already registered? <a href="<?= route_to('register') ?>">Sign in</a></p>
                </div>
            </div>

        </div>
    </div>
</div>

<?= view('Myth\Auth\Views\_footer') ?>
