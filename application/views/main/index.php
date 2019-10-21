<?php
/**
 * @var $totalCount
 * @var $pagesCount
 * @var $data
 * @var $view object of application\core\View
 * @var $pagination object of \yidas\data\Pagination
 */

use application\core\helper\GridHelper;

$pagination = new \yidas\data\Pagination([
    'totalCount' => $totalCount,
    'perPage' => \application\models\Task::TASKS_PER_PAGE,
    'perPageParam' => false,
]);
?>
<?php if (!empty($data)): ?>
    <table class="table table-bordered table-md table-dark">
        <thead>
        <tr id="grid-task-header">
            <th>
                <div class="btn-group">
                    <button type="button" class="btn btn-warning btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Name
                    </button>
                    <div class="dropdown-menu" id="grid_user_name" data-column="user_name">
                        <a class="dropdown-item" data-sort="asc" href="<?= GridHelper::renderUrl(['order' => 'name', 'direction' => 'asc']); ?>">A-Z</a>
                        <a class="dropdown-item" data-sort="desc" href="<?= GridHelper::renderUrl(['order' => 'name', 'direction' => 'desc']); ?>">Z-A</a>
                    </div>
                </div>
            </th>
            <th>
                <div class="btn-group">
                    <button type="button" class="btn btn-warning btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        E-mail
                    </button>
                    <div class="dropdown-menu" id="grid_email" data-column="email">
                        <a class="dropdown-item" data-sort="asc" href="<?= GridHelper::renderUrl(['order' => 'email', 'direction' => 'asc']); ?>">A-Z</a>
                        <a class="dropdown-item" data-sort="desc" href="<?= GridHelper::renderUrl(['order' => 'email', 'direction' => 'desc']); ?>">Z-A</a>
                    </div>
                </div>
            </th>
            <th>Task Description</th>
            <th id="grid_task_status">
                <div class="btn-group">
                    <button type="button" class="btn btn-warning btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Status
                    </button>
                    <div class="dropdown-menu" id="grid_status" data-column="status">
                        <a class="dropdown-item" data='' href="<?= GridHelper::renderUrl(['status' => 'new']); ?>">New</a>
                        <a class="dropdown-item" href="<?= GridHelper::renderUrl(['status' => 'done']); ?>">Done</a>
                    </div>
                </div>
            </th>
            <?php if ($authorized): ?>
                <th>Action</th>
            <?php endif; ?>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($data as $row): ?>
            <tr>
                <td><?= $row['name'] ?></td>
                <td><?= $row['email'] ?></td>
                <td><?= $row['description'] ?></td>
                <?php if (!$authorized): ?>
                    <td><?= $row['status'] ?></td>
                <?php else : ?>
                    <td><?= $row['status'] ?> <?php if (!empty($row['edit_by'])): ?><span class="badge badge-secondary">Edited by Admin</span><?php endif; ?></td>
                    <td><a href="<?= GridHelper::renderUrl(['id' => $row['id']], 'admin/edit'); ?>"><span class="badge badge-secondary">edit</span></a></td>
                <?php endif; ?>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <div class="my-3 p-3 pb-4 mb-4">
        <nav aria-label="Page navigation example">
            <?= \yidas\widgets\Pagination::widget([
                'pagination' => $pagination,
            ]) ?>
        </nav>
    </div>
<?php endif; ?>
<div class="my-3 p-3 bg-white rounded shadow-sm">
    <h5 class="border-bottom border-gray pb-4 mb-4">Create new task</h5>
    <form method="post" name="createTask">
        <div class="form-group row">
            <label for="name" class="col-sm-2 col-form-label">User name</label>
            <div class="col-sm-10">
                <input type="text" class="form-control <?php if (isset($errors['Task']['name'])): ?> is-invalid<?php endif; ?>" id="name" name="name" placeholder="Name" required>
                <div class="<?php if (isset($errors['Task']['name'])): ?>invalid-feedback<?php endif; ?>">
                    <?= isset($errors['Task']['name']) ? $errors['Task']['name'] : '' ?>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label for="email" class="col-sm-2 col-form-label">E-mail</label>
            <div class="col-sm-10">
                <input type="email" class="form-control <?php if (isset($errors['Task']['email'])): ?> is-invalid<?php endif; ?>" id="email" name="email" placeholder="e-mail" required>
                <div class="<?php if (isset($errors['Task']['email'])): ?>invalid-feedback<?php endif; ?>">
                    <?= isset($errors['Task']['email']) ? $errors['Task']['email'] : '' ?>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label for="description" class="col-sm-2 col-form-label">Task Description</label>
            <div class="col-sm-10">
                <textarea class="form-control <?php if (isset($errors['Task']['description'])): ?> is-invalid<?php endif; ?>" id="description" name='description' placeholder="Describe the task"></textarea>
                <div class="<?php if (isset($errors['Task']['description'])): ?>invalid-feedback<?php endif; ?>">
                    <?= isset($errors['Task']['description']) ? $errors['Task']['description'] : '' ?>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-10">
                <button type="submit" class="btn btn-primary">Apply</button>
            </div>
        </div>
    </form>
</div>
