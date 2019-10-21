<?php
/**
 * @var $totalCount
 * @var $pagesCount
 * @var $data
 * @var $view object of application\core\View
 * @var $pagination object of \yidas\data\Pagination
 */

use application\core\helper\GridHelper;

?>
<?php if (!empty($data)): ?>
    <div class="my-3 p-3 bg-white rounded shadow-sm">
        <h5 class="border-bottom border-gray pb-4 mb-4">Edit task #id <?= $data['id'] ?> </h5>
        <form method="post">
            <input type="text" name="id" value="<?=$data['id'];?>" hidden>
            <div class="form-group row">
                <label for="description" class="col-sm-2 col-form-label">Task Description</label>
                <div class="col-sm-10">
                    <textarea class="form-control <?php if (isset($errors['Task']['description'])): ?> is-invalid<?php endif; ?>" id="description" name='description'><?= $data['description']; ?></textarea>
                    <div class="<?php if (isset($errors['Task']['description'])): ?>invalid-feedback<?php endif; ?>">
                        <?= isset($errors['Task']['description']) ? $errors['Task']['description'] : '' ?>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label for="email" class="col-sm-2 col-form-label">Status</label>
                <div class="col-sm-10">
                    <select id="inputState" name="status" class="form-control">
                        <?php foreach ($statuses as $item) : ?>
                            <option <?= $item == $data['status'] ? 'selected' : ''; ?>><?= $item; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-10 p-3">
                    <button type="submit" class="btn btn-primary">Apply</button>
                </div>
            </div>
        </form>
    </div>
<?php endif; ?>
