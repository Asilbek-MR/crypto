<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php if($header_top_html) : ?>
    <div class="ui basic segment">
        <?php echo $header_top_html; ?>
    </div>
<?php endif; ?>

<div class="ui basic segment container">
    <h1 class="ui dividing centered header">
        <?php _et($title); ?>
        <div class="sub header"><?php _et($subtitle); ?></div>
    </h1>
</div>

<?php if($header_bottom_html) : ?>
    <div class="ui basic segment">
        <?php echo $header_bottom_html; ?>
    </div>
<?php endif; ?>

<div class="ui container">

    <div class="ui stackable raised <?php echo $items_per_row; ?> cards">
        <?php foreach ($pagination->items as $service) :?>
            <a target="_blank" <?php if(!empty($service->url)) echo "href=\"$service->url\""; ?> class="card">
                <div class="content">
                    <div class="header"><?php echo $service->name; ?></div>
                    <div class="description"><?php echo $service->description; ?></div>
                </div>
                <?php if(!empty($service->tags)) :?>
                    <div class="extra content">
                        <?php foreach ($service->tags as $tag) :?>
                            <div class="ui basic <?php echo $theme; ?> label">#<?php echo $tag; ?></div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </a>
        <?php endforeach; ?>
    </div>

    <?php if($pagination->pages > 1) :?>
    <div class="ui basic center aligned segment">
        <?php paginationMenu($pagination, $redirect); ?>
    </div>
    <?php endif; ?>

    <?php if($after_html) : ?>
        <div class="ui basic segment">
            <?php echo $after_html; ?>
        </div>
    <?php endif; ?>

</div>
