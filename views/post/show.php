<?php

use App\Connection;
use App\Model\Category;
use App\Model\Post;
use App\Table\CategoryTable;
use App\Table\PostTable;

$id = (int)$params['id'];
$slug = $params['slug'];

$pdo = Connection::getPDO();
$post = (new PostTable($pdo))->find($id);
(new CategoryTable($pdo))->hydratePosts([$post]);

if($post->getSlug()!==$slug){
    $url = $router->url('post', ['slug' => $post->getSlug(), 'id'=>$id]);
    http_response_code(301);
    header('Location: '.$url);
}


?>

<h5><?= htmlentities($post->getName()) ?></h5>
    <p class="text-muted"><?= $post->getCreatedAt()->format('d F Y')?></p>
<?php 
foreach($post->getCategories() as $k => $category):
    if ($k>0):
        echo ', ';
    endif;
    $category_url = $router->url('category', ['id' => $category->getID(), 'slug'=>$category->getSlug()]);
    ?><a href="<?= $category_url ?>"><?=htmlentities($category->getName())?></a><?php 
endforeach ?>
<?php if($post->getImage()):?>
    <img src="<?= $post->getImageURL('large')?>" alt="" style="width:100%">
<?php endif ?>
<p><?= $post->getFormattedContent() ?></p>
