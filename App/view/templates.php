<?php $this->view('common/header'); ?>

<section class="containet-wrap">
    <?php
    foreach ($templates as $template)
    {
        echo "<img src='" . BASE_URL . "template/" . $template->templateURL . "thumb.png' width='250'/>";
    }
    ?>
</section>