<?php if (!empty($notification) ) echo '<div class="notification-message"><span>'.$notification.'</span></div>'; ?> 
    <?php if (!empty ($js_files)) foreach ($js_files as $file) echo '<script src="'.$file.'"></script>'; ?>
  </body>
</html>