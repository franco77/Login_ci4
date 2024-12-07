<form id="form" accept-charset="utf-8">
    <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input type="text" name="username" value="<?= !empty($data_users['username']) ? $data_users['username'] : '' ?>"
            class="form-control" />
    </div>

    <div class="row">
        <div class="col">
            <div class="mb-3">
                <label for="first_name" class="form-label">First Name</label>
                <input type="text" name="first_name"
                    value="<?= !empty($data_users['first_name']) ? $data_users['first_name'] : '' ?>"
                    class="form-control" />
            </div>
        </div>
        <div class="col">
            <div class="mb-3">
                <label for="last_name" class="form-label">Last Name</label>
                <input type="text" name="last_name"
                    value="<?= !empty($data_users['last_name']) ? $data_users['last_name'] : '' ?>"
                    class="form-control" />
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="text" name="email" value="<?= !empty($data_users['email']) ? $data_users['email'] : '' ?>"
                    class="form-control" />
            </div>
        </div>
        <div class="col">
            <div class="mb-3">
                <label for="phone" class="form-label">Phone</label>
                <input type="text" name="phone" value="<?= !empty($data_users['phone']) ? $data_users['phone'] : '' ?>"
                    class="form-control" />
            </div>
        </div>
    </div>


    <div class="mb-3">
        <label for="address" class="form-label">Address</label>
        <input type="text" name="address" class="form-control"
            value="<?= !empty($data_users['address']) ? $data_users['address'] : '' ?>">
    </div>
    <div class="mb-3">
        <label for="avatar" class="form-label">Avatar</label>
        <input name="avatar" class="form-control" type="file" id="avatar">
    </div>

    <div class="mb-3">
        <label for="profile" class="form-label">Profile</label>
        <textarea name="profile" rows="5"
            class="form-control"><?= !empty($data_users['profile']) ? $data_users['profile'] : '' ?></textarea>
    </div>
    <?php if ($actions == 'edit') : ?>
        <hr>
        <div class="alert alert-warning" role="alert">
            Solo si vas a cambiar la contraseña debes ingresarla!
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col">
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" class="form-control" />
            </div>
        </div>
        <div class="col">
            <div class="mb-3">
                <label for="password_confirm" class="form-label">Password Confirm</label>
                <input type="password" name="password_confirm" class="form-control" />
            </div>
        </div>
    </div>

    <div class="mb-3">
        <label for="role_id" class="form-label">Role</label>
        <select name="role_id" class="select2">
            <?php foreach ($data_roles as $roles => $role): ?>
                <option value="<?= $role['id'] ?>"
                    <?= !empty($data_users['role_id']) && $data_users['role_id'] == $role['id'] ? 'selected' : '' ?>>
                    <?= $role['name'] ?></option>
            <?php endforeach ?>
        </select>
    </div>
    <?php if ($actions == 'edit') : ?>
        <input type="hidden" name="id" value="<?= $data_users['id'] ?>">
    <?php endif; ?>
    <div class="form-group">
        <button type="submit" class="btn btn-primary">Guardar</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <label for="error"></label>
    </div>
</form>