<div class="jokelist">
    <ul class="categories">
        <?php foreach ($categories as $category): ?>
            <li><a href="index.php?route=joke/list&category=<?=$category->id?>"><?=$category->name?></a><li>
        <?php endforeach; ?>
    </ul>
</div>
<div class="jokes">
<p>
    <em><?=$totalJokes;?> jokes have been submitted to IJDB.</em>
</p>
<?php foreach ($jokes as $joke): ?>
    <blockquote>

        <?=(new\Ninja\Markdown($joke->joketext))->toHtml()?><br>
        <p>
            (by <a href="mailto:<?php echo htmlspecialchars($joke->getAuthor()->email, ENT_QUOTES, 'UTF-8'); ?>">
                <?php echo htmlspecialchars($joke->getAuthor()->name, ENT_QUOTES, 'UTF-8');?></a> on
            <?php
            $date = new DateTime($joke->jokedate);
            echo $date -> format('jS F Y');
            ?>)
            <?php //echo var_dump($user->hasPermission('\Ijdb\Entity\Author\::DELETE_JOKES'));?>
            <?php if ($user):?>
                <?php if ($joke->authorid==$user->id || $user->hasPermission(\Ijdb\Entity\Author::EDIT_JOKES)):?>
                <a href="index.php?route=joke/edit&id=<?=$joke->id;?>">
                    Edit
                </a>
                <?php endif;?>
                <?php if ($joke->authorid==$user->id || $user->hasPermission(\Ijdb\Entity\Author::DELETE_JOKES)):?>
                <form action = "index.php?route=joke/delete" method = "post">
                    <input type = "hidden" name = "id" value = "<?=$joke->id?>">
                    <input type = "submit" value = "Delete">
                </form>
                <?php endif;?>
            <?php endif;?>
        </p>

    </blockquote>
<?php endforeach; ?>
    Select page:
    <?php $numPages = ceil($totalJokes/5);
    for($i=1; $i<=$numPages; $i++) :?>
    <?php if($currentPage==$i):?>
            <a class="currentpage" href="index.php?route=joke/list&page=<?=$i?><?=!empty($categoryId) ? '&category='.$categoryId : ''?>"><?=$i?></a>
    <?php else:?>
            <a href="index.php?route=joke/list&page=<?=$i?><?=!empty($categoryId) ? '&category='.$categoryId : ''?>"><?=$i?></a>
    <?php endif;?>
    <?php endfor;?>
<!--</div>-->
</div>
<?//=!empty($category) ? '&category='.$category : ''?>
<!--Select page:-->
<?php
//$numPages = ceil($totalJokes/10);
//for ($i = 1; $i <= $numPages; $i++):
//    if ($i == $currentPage):
//        ?>
<!--        <a class="currentpage"-->
<!--           href="/joke/list?page=--><?//=$i?>
<?//=!empty($categoryId) ?
//               '&category=' . $categoryId : '' ?><!--">-->
<!--            --><?//=$i?><!--</a>-->
<!--    --><?php //else: ?>
<!--        <a href="/joke/list?page=--><?//=$i?>
<?//=!empty($categoryId) ?
//            '&category=' . $categoryId : '' ?><!--">-->
<!--            --><?//=$i?><!--</a>-->
<!--    --><?php //endif; ?>
<?php //endfor; ?>
<!--</div>-->