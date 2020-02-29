<?php
/**
 * @var array $messages
 */
?>

<?php foreach ($messages as $item): ?>
    <hr/>
    <div class="row message-block">
        <div class="col-xs-12 text-justify">
            <div class="form-group text-muted"><?= $item['first_name']  . ' ' . $item['surname'] ?></div>
            <?= $item['message'] ?>
        </div>
    </div>
<?php endforeach;?>
