<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>


<?php if($header_top_html) : ?>
    <div class="ui basic segment">
        <?php echo $header_top_html; ?>
    </div>
<?php endif; ?>

<div class="ui basic segment container">
    <h1 class="ui centered header">
        <?php _et($title); ?>
        <div class="sub header"><?php _et($subtitle); ?></div>
    </h1>
</div>

<?php if($header_bottom_html) : ?>
    <div class="ui basic segment">
        <?php echo $header_bottom_html; ?>
    </div>
<?php endif; ?>

<div id="press" class="ui container">

    <div id="press-items" class="ui divided items">
        <?php foreach ($pagination->items as $item) : ?>
            <div class="item">
                <div class="content">
                    <div class="header"><?php echo $item->title; ?></div>
                    <div class="meta"><?php echo $item->date; ?></div>
                    <div class="description"><?php echo $item->content; ?></div>
                    <div class="extra">
                        <a target="_blank" href="<?php echo $item->link; ?>" class="ui right floated <?php echo $theme; ?> button">
                            <?php et('read_more'); ?>
                            <i class="right chevron icon"></i>
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <?php if($pagination->pages > 1) :?>
    <div class="ui basic segment">
        <?php paginationMenu($pagination, $redirect); ?>
    </div>
    <?php endif; ?>

</div>

<?php if($after_html) : ?>
    <div class="ui basic segment">
        <?php echo $after_html; ?>
    </div>
<?php endif; ?>


