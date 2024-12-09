<?= $this->extend('admin/layout/default') ?>
<?= $this->section('content') ?>
<style>
    .avatar-container {
        width: 150px;
        height: 150px;
        position: relative;
        cursor: pointer;
    }

    .avatar-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 50%;
        transition: opacity 0.3s ease;
    }

    .avatar-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background: rgba(0, 0, 0, 0.6);
        color: #fff;
        display: flex;
        justify-content: center;
        align-items: center;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .avatar-text {
        font-size: 16px;
        font-weight: bold;
    }

    .avatar-container:hover .avatar-overlay {
        opacity: 1;
    }

    .avatar-container:hover .avatar-img {
        opacity: 0.7;
    }
</style>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="mb-4">
                    <h5 class="card-title mb-0"><?= $title; ?></h5>
                </div>



                <div class="container rounded bg-white mt-5 mb-5">
                    <div class="row">
                        <!-- Sección de perfil del usuario -->
                        <div class="col-md-3 border-right">
                            <div class="d-flex flex-column align-items-center text-center p-3 py-5">
                                <div class="avatar-container" style="position: relative;">
                                    <img id="avatarPreview" class="rounded-circle avatar-img" width="150px"
                                        src="<?= base_url('uploads/avatars/' . $user['avatar']); ?>" alt="Avatar">
                                    <div class="avatar-overlay">
                                        <span class="avatar-text">Cambiar</span>
                                    </div>
                                </div>
                                <form id="avatarForm" enctype="multipart/form-data" style="display:none;">
                                    <input type="file" name="avatar" id="avatar" accept="image/*">
                                    <input type="hidden" name="user_id" id="user_id" value="<?= $user['id']; ?>">
                                </form>
                                <br>
                                <span class="font-weight-bold" id="displayUsername">
                                    <?= get_setting('first_name') . ' ' . get_setting('last_name'); ?>
                                </span>
                                <span class="text-black-50" id="displayEmail"><?= get_setting('email'); ?></span>
                            </div>
                        </div>


                        <!-- Sección para editar perfil -->
                        <div class="col-md-7 border-right">
                            <div class="p-3 py-5">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h4 class="text-right">Configuración de Perfil</h4>
                                </div>

                                <?php if (session()->getFlashdata('error')): ?>
                                    <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
                                <?php endif; ?>

                                <?php if (session()->getFlashdata('message')): ?>
                                    <div class="alert alert-success"><?= session()->getFlashdata('message') ?></div>
                                <?php endif; ?>
                                <form id="updateProfileForm">
                                    <?= csrf_field(); ?>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="labels">Nombre</label>
                                            <input type="text" class="form-control" name="first_name" id="first_name"
                                                placeholder="Nombre"
                                                value="<?= old('first_name', esc($user['first_name'])); ?>">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="labels">Apellido</label>
                                            <input type="text" class="form-control" name="last_name" id="last_name"
                                                placeholder="Apellido"
                                                value="<?= old('last_name', esc($user['last_name'])); ?>">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="labels">Correo Electrónico</label>
                                        <input type="email" class="form-control" name="email" id="email"
                                            placeholder="Correo Electrónico"
                                            value="<?= old('email', esc($user['email'])); ?>">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="labels">Teléfono</label>
                                        <input type="text" class="form-control" name="phone" id="phone"
                                            placeholder="Teléfono" value="<?= old('phone', esc($user['phone'])); ?>">
                                        <div class="invalid-feedback"></div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="labels">Dirección</label>
                                        <input type="text" class="form-control" name="address" id="address"
                                            placeholder="Dirección"
                                            value="<?= old('address', esc($user['address'])); ?>">
                                        <div class="invalid-feedback"></div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="labels">Perfil</label>
                                        <textarea class="form-control" name="profile" id="profile" rows="5"
                                            placeholder="Perfil"><?= old('profile', esc($user['profile'])); ?></textarea>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <button class="btn btn-primary" type="submit">Guardar Perfil</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?= $this->endSection() ?>
<?= $this->section('js') ?>
<!-- Toastr CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
    $(document).ready(function() {
        $('#updateProfileForm').on('submit', function(e) {
            e.preventDefault(); // Evitar el envío tradicional del formulario

            const formData = $(this).serialize(); // Serializar los datos del formulario

            $.ajax({
                url: '<?= base_url('admin/profile/update'); ?>', // Ruta del controlador
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        toastr.success(response.message); // Mostrar mensaje de éxito
                    } else if (response.status === 'error') {
                        toastr.error('Error al actualizar el perfil.');
                        // Mostrar errores específicos
                        if (response.errors) {
                            for (const [field, error] of Object.entries(response.errors)) {
                                $(`#${field}`).addClass(
                                    'is-invalid'); // Resaltar campos con errores
                                $(`#${field}`).next('.invalid-feedback').text(
                                    error); // Mostrar mensaje de error
                            }
                        }
                    }
                },
                error: function() {
                    toastr.error('Ocurrió un error al procesar la solicitud.');
                }
            });
        });

        // Eliminar clases de error al modificar campos
        $('input').on('input', function() {
            $(this).removeClass('is-invalid');
            $(this).next('.invalid-feedback').text('');
        });
    });



    $(document).ready(function() {

        $('.avatar-container').on('click', function() {
            $('#avatar').trigger('click');
        });

        $('#avatar').on('change', function() {
            var formData = new FormData($('#avatarForm')[0]);

            $.ajax({
                url: '<?= site_url('admin/profile/updateAvatar'); ?>',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.status === 'success') {

                        $('#avatarPreview').attr('src', response.new_image_url);

                        toastr.success('Avatar actualizado correctamente.');
                    } else {

                        toastr.error(response.message || 'Error al actualizar el avatar.');
                    }
                },
                error: function() {
                    toastr.error('Error al procesar la solicitud. Intenta de nuevo.');
                }
            });
        });
    });
</script>
<?= $this->endSection() ?>