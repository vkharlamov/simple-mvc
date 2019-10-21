<form method="post" class="form-signin">
    <div class="text-center mb-4">
        <a href="/"><img class="mb-4" src="/img/c64.png" alt="" width="92" height="92"></a>
        <h1 class="h3 mb-3 font-weight-normal">Administrative side</h1>
    </div>
    <div class="form-label-group">
        <input type="text" id="inputLogin" name="name" class="form-control<?php if (isset($errors['Users']['name'])): ?> is-invalid<?php endif; ?>" placeholder="Login" required autofocus>
        <label for="inputLogin">Login</label>
        <div class="<?php if (isset($errors['Users']['name'])): ?>invalid-feedback<?php endif; ?>">
            <?= isset($errors['Users']['name']) ? $errors['Users']['name'] : '' ?>
        </div>
    </div>
    <div class="form-label-group">
        <input type="password" id="inputPassword" name="password" class="form-control<?php if (isset($errors['Users']['password'])): ?> is-invalid<?php endif; ?>" placeholder="Password">
        <label for="inputPassword">Password</label>
        <div class="<?php if (isset($errors['Users']['password'])): ?>invalid-feedback<?php endif; ?>">
            <?= isset($errors['Users']['password']) ? $errors['Users']['password'] : '' ?>
        </div>
    </div>
    <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
</form>
