<?php if(strpos($_SERVER['REQUEST_URI'], basename(__FILE__)) !== false) exit(); ?>
<hr />
<i>Served by <b><?php echo $_SERVER['SERVER_SOFTWARE'] . '</b> on <b>' . $_SERVER['HTTP_HOST']; ?></b></i>
</body>
</html>
