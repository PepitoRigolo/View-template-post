<?php

use App\Connection;
use App\Table\PostTable;
use App\Auth;

Auth::check();

//$router->layout = "admin/layouts/default";
$router->layout='admin/layouts/default';
$title="Administration";
$pdo = Connection::getPDO();
$link = $router->url('admin_posts');
[$posts, $pagination] = (new PostTable($pdo))->findPaginated();


if (isset($_GET['delete'])) : ?>
<div class="alert alert-danger">
    L'enregistrement a bien été supprimé
</div>
<?php endif ?>

<?php if(isset($_GET['created'])): ?>
<div class="alert alert-success">
    L'article a bien été créer
</div>
<?php endif ?>

<table class="table">
    <thead>
        <th>#</th>
        <th>Titre</th>
        <th>
            <a href="<?=$router->url('admin_post_new') ?>" class="btn btn-success">Créer un article</a>
        </th>
    </thead>
    <tbody>
        <?php foreach($posts as $post): ?>
        <tr>
            <td>
                <?= $post->getID() ?>
            </td>
            <td>
                <a href="<?= $router->url('admin_post', ['id'=>$post->getID()])?>">
                <?= htmlentities($post->getName())?>
                </a>
            </td>
            <td>
                <a href="<?= $router->url('admin_post', ['id'=>$post->getID()])?>" class="btn btn-primary">
                Editer
                </a>
                <form action="<?= $router->url('admin_post_delete', ['id'=>$post->getID()])?>" method="POST"
                    onsubmit="return confirm('Voulez-vous vraiment effectuer cette action ?')" style="display:inline">
                    <button type="submit" class="btn btn-danger" >Supprimer</button>

                </form>
            </td>
        </tr>
        <?php endforeach ?>
    </tbody>
</table>

<div class="d-flex justify-content-between my-4">
    <?= $pagination->previousLink($link)?>
    <?= $pagination->nextLink($link)?>
</div>