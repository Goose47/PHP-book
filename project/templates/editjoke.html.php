<?php if(empty($joke->id) || $user->id == $joke->authorid ||  $user->hasPermission(\Ijdb\Entity\Author::EDIT_JOKES)):?>
<form action="" method="post">
    <input type="hidden" name="joke[id]" value="<?=$joke->id ?? ''?>">
    <label for="joketext">
        Type your joke here:
    </label>
    <textarea id="joketext" name="joke[joketext]" rows="3" cols="40"><?=$joke->joketext ?? ''?></textarea>
    <p>Select categories for this joke:</p>
<?php foreach ($categories as $category): ?>
    <?php if($joke && $joke->hasCategory($category->id)):?>
    <input type="checkbox" checked name="category[]" value="<?=$category->id?>" />
    <label><?=$category->name?></label>
    <?php else: ?>
    <input type="checkbox" name="category[]" value="<?=$category->id?>" />
    <label><?=$category->name?></label>
    <?php endif;?>
<?php endforeach; ?>

    <input type="submit" value="Save">
</form>
<?php else:?>
<p>You can only edit your jokes :(</p>
<?php endif;?>
