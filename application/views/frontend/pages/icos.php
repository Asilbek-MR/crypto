<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php if($header_top_html) : ?>
    <div class="ui basic segment">
        <?php echo $header_top_html; ?>
    </div>
<?php endif; ?>

<div class="ui basic  segment">
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

<div class="ui container">

    <div class="ui large stackable secondary menu">
        <a href="<?php echo "$redirect/finished" ?>" class="item <?php if($type === 'finished') echo 'active'; ?> button">
            <i class="hourglass end icon"></i>
            <?php et('finished'); ?>
        </a>
        <a href="<?php echo "$redirect/live" ?>" class="item <?php if($type === 'live') echo 'active'; ?> button">
            <i class="clock icon"></i>
            <?php et('live'); ?>
        </a>
        <a href="<?php echo "$redirect/upcoming" ?>" class="item <?php if($type === 'upcoming') echo 'active'; ?> button">
            <i class="calendar plus outline icon"></i>
            <?php et('upcoming'); ?>
        </a>
    </div>

    <table id="icos-list" class="ui selectable very basic table">
        <tbody>
        <?php foreach ($pagination->items as $i => $ico) : ?>
            <tr class="<?php if(!empty($ico->featured)) echo 'warning '; ?>">
                <td class="center aligned">
                    <a href="<?php echo $ico->website; ?>" class="ico-image ui small image">
                        <img src="<?php echo $ico->image; ?>">
                    </a>
                </td>
                <td>
                    <h3 class="ui header">
                        <a href="<?php echo $ico->website; ?>"><?php echo $ico->name; ?></a>
                    </h3>
                    <?php echo $ico->description; ?>
                </td>

                <?php if($type === 'live' || $type === 'upcoming') :?>
                    <td class="single line" style="min-width: 200px">
                        <?php if($type === 'live') :?>
                            <div class="ui <?php echo $theme; ?> progress" data-percent="<?php echo $ico->timeline; ?>">
                                <div class="bar">
                                    <div class="progress"></div>
                                </div>
                            </div>
                        <?php elseif($type === 'upcoming') : ?>
                            <div class="ui small two statistics">
                                <div class="statistic">
                                    <div class="value"><?php echo $ico->missing_days; ?></div>
                                    <div class="label"><?php et('days'); ?></div>
                                </div>
                                <div class="statistic">
                                    <div class="value"><?php echo $ico->missing_hours; ?></div>
                                    <div class="label"><?php et('hours'); ?></div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </td>
                <?php endif; ?>

                <td class="single line">
                    <div class="ui vertical two buttons">
                        <a href="<?php echo $ico->website; ?>" class="ui green button">
                            <?php echo $ico->start_date; ?>
                        </a>
                        <a href="<?php echo $ico->website; ?>" class="ui red button">
                            <?php echo $ico->end_date; ?>
                        </a>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
        <?php if($pagination->pages > 1) :?>
        <tfoot>
        <tr>
            <td colspan="<?php echo ($type === 'live' || $type === 'upcoming' ? 4 : 3); ?>">
                <?php paginationMenu($pagination, "$redirect/$type"); ?>
            </td>
        </tr>
        </tfoot>
        <?php endif; ?>
    </table>

</div>

<?php if($after_html) : ?>
    <div class="ui basic segment">
        <?php echo $after_html; ?>
    </div>
<?php endif; ?>


