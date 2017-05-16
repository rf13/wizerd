<?php
if (strpos($rating, '.')) {
    $parts = explode('.', $rating);
    $class = $parts[0] . '_half';
} else {
    $class = $rating;
}
?>
<div class="rating">
    <i class="star-img stars_<?php echo $class ?>">
    </i>
</div>
