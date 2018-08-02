<div class="card text-white bg-primary o-hidden h-100">
    <div class="card-body">
        <div class="card-body-icon">
            <i class="fas fa-fw fa-comments"></i>
        </div>
        <?php
            $noValues       = empty($card_value);
            $singleValue    = (!empty($card_value)) && ($card_value == 1);
            $valueResult    = ($noValues ? 'No' : $card_value) . " New $name" . (!$singleValue ? 's' : '');
        ?>
        <div class="mr-5">{!! $valueResult !!}</div>
    </div>
    <a class="card-footer text-white clearfix small z-1" href="#">
        <span class="float-left">View Details</span>
        <span class="float-right">
            <i class="fas fa-angle-right"></i>
        </span>
    </a>
</div>

