<?= $this->extend("admin/layout/default") ?>
<?= $this->section("content") ?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Configuración</h5>
            </div>
            <div class="card-body">
                <!-- Tabs -->
                <ul class="nav nav-tabs" id="settingsTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="settings-tab" data-bs-toggle="tab"
                            data-bs-target="#settings" type="button" role="tab" aria-controls="settings"
                            aria-selected="true">Ajustes</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="logo-tab" data-bs-toggle="tab" data-bs-target="#logo" type="button"
                            role="tab" aria-controls="logo" aria-selected="false">Logo</button>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content" id="settingsTabsContent">
                    <!-- Ajustes Tab -->
                    <div class="tab-pane fade show active mt-5" id="settings" role="tabpanel"
                        aria-labelledby="settings-tab">
                        <form id="settings-form" enctype="multipart/form-data">
                            <?php foreach ($settings as $setting): ?>
                                <?php if ($setting['key'] === 'logo') continue; ?>
                                <!-- Omitir la fila si la clave es 'logo' -->
                                <div class="mb-3 row">
                                    <label for="<?= esc($setting['key']) ?>" class="col-sm-2 col-form-label">
                                        <?= esc(snake_to_words($setting['key'])) ?>
                                    </label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control setting-input"
                                            name="<?= esc($setting['key']) ?>" value="<?= esc($setting['value']) ?>">
                                    </div>
                                </div>
                            <?php endforeach; ?>

                            <button type="button" id="update-settings-btn" class="btn btn-primary"
                                data-loading-text="Actualizando...">
                                <i class="bi bi-save"></i> Actualizar Todos
                            </button>
                        </form>
                    </div>

                    <!-- Logo Tab -->
                    <div class="tab-pane fade" id="logo" role="tabpanel" aria-labelledby="logo-tab">
                        <div class="mt-3">
                            <form id="logo-form" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label for="logo" class="form-label">Selecciona un nuevo logo</label>
                                    <input class="form-control" type="file" id="logo" name="logo">
                                    <div class="mt-3">
                                        <img id="logo-preview" src="#" alt="Previsualización del logo"
                                            class="img-thumbnail d-none" style="max-width: 200px; max-height: 200px;">
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-upload"></i> Subir Logo
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section("js") ?>
<script>
    $(document).ready(function() {
        $('#update-settings-btn').on('click', function(e) {
            e.preventDefault();

            const $button = $(this);
            const form = $('#settings-form')[0];
            const formData = new FormData(form);

            // Deshabilitar botón y mostrar estado de carga
            $button.prop('disabled', true)
                .html(
                    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Actualizando...'
                );

            $.ajax({
                url: '<?= base_url('admin/settings/updateSettings') ?>',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        toastr.success(response.message, 'Actualización Exitosa');
                    } else {
                        toastr.error(response.message, 'Error');
                    }
                },
                error: function(xhr, status, error) {
                    let errorMessage = 'No se pudo completar la actualización';

                    // Intentar obtener mensaje de error más detallado
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }

                    toastr.error(errorMessage, 'Error de Conexión');
                    console.error('Error en la solicitud:', error);
                },
                complete: function() {
                    // Restaurar botón
                    $button.prop('disabled', false)
                        .html('<i class="bi bi-save"></i> Actualizar Todos');
                }
            });
        });
    });

    $(document).ready(function() {
        // Previsualizar imagen seleccionada
        $('#logo').on('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#logo-preview')
                        .attr('src', e.target.result)
                        .removeClass('d-none');
                };
                reader.readAsDataURL(file);
            } else {
                $('#logo-preview').addClass('d-none'); // Ocultar si no hay archivo
            }
        });

        // Manejar envío del formulario
        $('#logo-form').on('submit', function(e) {
            e.preventDefault();

            const $form = $(this);
            const formData = new FormData($form[0]);

            $.ajax({
                url: '<?= base_url('admin/settings/uploadLogo') ?>',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        toastr.success(response.message, 'Subida Exitosa');
                    } else {
                        toastr.error(response.message, 'Error');
                    }
                },
                error: function(xhr, status, error) {
                    let errorMessage = 'No se pudo completar la subida';

                    // Intentar obtener mensaje de error más detallado
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }

                    toastr.error(errorMessage, 'Error de Conexión');
                    console.error('Error en la solicitud:', error);
                }
            });
        });
    });
</script>

<?= $this->endSection() ?>