<?= $this->extend("admin/layout/default") ?>
<?= $this->section("content") ?>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
<style>
    .padding {
        padding: 5rem;
    }



    .add-items {
        margin-bottom: 1.5rem;
        overflow: hidden;
    }

    .add-items input[type="text"] {
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
        width: 100%;
        background: transparent;
    }

    .add-items .btn {
        margin-left: 0.5rem;
    }

    .btn {
        font-size: 0.875rem;
        line-height: 1;
        font-weight: 400;
        padding: 0.7rem 1.5rem;
        border-radius: 0.1275rem;
    }

    .list-wrapper {
        height: 100%;
        max-height: 100%;
    }

    .list-wrapper ul {
        padding: 0;
        text-align: left;
        list-style: none;
        margin-bottom: 0;
    }

    .list-wrapper ul li {
        font-size: 0.9375rem;
        padding: 0.4rem 0;
        border-bottom: 1px solid #f3f3f3;
        display: flex !important;
    }

    .list-wrapper ul li:first-child {
        border-bottom: none;
    }

    .list-wrapper ul li .form-check {
        max-width: 90%;
        margin-top: 0.25rem;
        margin-bottom: 0.25rem;
    }

    .list-wrapper ul li .form-check label:hover {
        cursor: pointer;
    }

    .list-wrapper input[type="checkbox"] {
        margin-right: 15px;
    }

    .list-wrapper .remove {
        cursor: pointer;
        font-size: 1.438rem;
        font-weight: 600;
        width: 1.25rem;
        height: 1.25rem;
        line-height: 20px;
        text-align: center;
        margin-left: auto !important;
    }

    .list-wrapper .completed {
        text-decoration: line-through;
        text-decoration-color: #3da5f4;
    }

    .form-check {
        position: relative;
        display: block;
        margin-top: 10px;
        margin-bottom: 10px;
        padding-left: 0;
    }

    .form-check .form-check-label {
        min-height: 18px;
        display: block;
        margin-left: 1.75rem;
        font-size: 0.875rem;
        line-height: 1.5;
    }

    .form-check-label {
        margin-bottom: 0;
    }

    .form-check .form-check-label input {
        position: absolute;
        top: 0;
        left: 0;
        margin-left: 0;
        margin-top: 0;
        z-index: 1;
        cursor: pointer;
        opacity: 0;
        filter: alpha(opacity=0);
    }

    .form-check .form-check-label input[type="checkbox"]+.input-helper:before,
    .form-check .form-check-label input[type="checkbox"]+.input-helper:after {
        position: absolute;
        top: 0;
        left: 0;
    }

    .form-check .form-check-label input[type="checkbox"]+.input-helper:before {
        content: "";
        width: 18px;
        height: 18px;
        border-radius: 2px;
        border: solid #405189;
        border-width: 2px;
        transition: all 250ms;
    }

    .form-check .form-check-label input[type="checkbox"]+.input-helper:after {
        font-family: FontAwesome;
        content: "\f095";
        display: inline-block;
        padding-right: 3px;
        vertical-align: middle;
        color: #fff;
        opacity: 0;
        transform: scale(0);
        transition: all 250ms;
    }

    .form-check .form-check-label input[type="checkbox"]:checked+.input-helper:before {
        background: #405189;
        border-width: 0;
    }

    .text-primary,
    .list-wrapper .completed .remove {
        color: #405189 !important;
    }

    .mdi:before {
        font-family: FontAwesome;
        content: "\f00d";
        display: inline-block;
        padding-right: 3px;
        vertical-align: middle;
        font-size: 0.756em;
        color: #405189;
    }

    .task-date {
        float: left;
        font-size: 0.8em;
        color: #888;
        margin-left: 23px;
    }
</style>
<div class="padding">
    <div class="row container d-flex justify-content-center">
        <div class="col-md-12">
            <div class="card px-3">
                <div class="card-body mt-5">
                    <div class="add-items d-flex">
                        <input type="text" class="form-control todo-list-input" placeholder="¿Qué necesitas hacer hoy?">
                        <button class="add btn btn-primary font-weight-bold todo-list-add-btn">Nuevo</button>
                    </div>
                    <div class="list-wrapper">
                        <ul class="d-flex flex-column-reverse todo-list">
                            <?php foreach ($todos as $todo): ?>
                                <li class="<?= $todo['is_completed'] ? 'completed' : '' ?>" data-id="<?= $todo['id'] ?>">
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input class="checkbox" type="checkbox"
                                                <?= $todo['is_completed'] ? 'checked' : '' ?>>
                                            <?= esc($todo['task']) ?>
                                            <i class="input-helper"></i>
                                        </label>
                                        <span class="task-date"><?= esc($todo['created_at']) ?></span>
                                        <!-- Fecha añadida -->
                                    </div>
                                    <i class="remove mdi mdi-close-circle-outline"></i>
                                </li>
                            <?php endforeach; ?>

                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section("js") ?>
<script>
    (function($) {


        function getFormattedDate() {
            const now = new Date();
            const year = now.getFullYear();
            const month = String(now.getMonth() + 1).padStart(2, '0');
            const day = String(now.getDate()).padStart(2, '0');
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
        }

        'use strict';
        $(function() {
            var todoListItem = $('.todo-list');
            var todoListInput = $('.todo-list-input');

            // Añadir una tarea
            $('.todo-list-add-btn').on("click", function(event) {
                event.preventDefault();

                var item = todoListInput.val();
                var created_at = getFormattedDate() // Fecha actual en formato YYYY-MM-DD

                if (item) {
                    $.post('<?= base_url('admin/todo/add') ?>', {
                        task: item,
                        date: created_at
                    }, function(response) {
                        if (response.status === 'success') {
                            todoListItem.append(`<li data-id="${response.id}">
                    <div class='form-check'><label class='form-check-label'>
                    <input class='checkbox' type='checkbox'/>${response.task}
                    <i class='input-helper'></i></label>
                    <span class="task-date">${created_at}</span></div> <!-- Fecha añadida -->
                    <i class='remove mdi mdi-close-circle-outline'></i></li>`);
                            todoListInput.val('');
                            toastr.success('Task added successfully!');
                        } else {
                            toastr.error('Failed to add task. Please try again.');
                        }
                    }).fail(function() {
                        toastr.error('Error occurred while adding task.');
                    });
                } else {
                    toastr.warning('Please enter a task before adding.');
                }
            });

            // Alternar estado de completado
            todoListItem.on('change', '.checkbox', function() {
                var listItem = $(this).closest('li');
                var id = listItem.data('id');

                $.post(`<?= base_url('admin/todo/toggle/') ?>${id}`, function(response) {
                    if (response.status === 'success') {
                        listItem.toggleClass('completed');
                        toastr.info('Task status updated.');
                    } else {
                        toastr.error('Failed to update task status.');
                    }
                }).fail(function() {
                    toastr.error('Error occurred while updating task status.');
                });
            });

            // Eliminar tarea
            todoListItem.on('click', '.remove', function() {
                var listItem = $(this).closest('li');
                var id = listItem.data('id');

                $.post(`<?= base_url('admin/todo/delete/') ?>${id}`, function(response) {
                    if (response.status === 'success') {
                        listItem.remove();
                        toastr.success('Task removed successfully!');
                    } else {
                        toastr.error('Failed to remove task.');
                    }
                }).fail(function() {
                    toastr.error('Error occurred while removing task.');
                });
            });
        });
    })(jQuery);
</script>

<?= $this->endSection() ?>