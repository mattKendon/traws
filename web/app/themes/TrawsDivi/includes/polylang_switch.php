<?php

$args = [
    'hide_current' => 1,
    'show_flags' => 1,
    'echo' => 0
];

$links = (new PLL_Switcher())->the_languages(PLL()->links, $args);

?>

<ul style="display: inline-block; padding-left: 1rem;">
    <?php echo $links; ?>
</ul>
