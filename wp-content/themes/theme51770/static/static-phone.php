<?php /* Static Name: Phone */ ?>
<?php if (of_get_option("phone") != "") { ?>
	<a href="tel:<?php echo of_get_option("phone"); ?>" class="phone"><i class="icon-phone"></i><?php echo of_get_option("phone"); ?></a>
<?php } ?>