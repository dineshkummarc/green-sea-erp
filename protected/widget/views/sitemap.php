<div class="map">
    <a href="<?php echo $controller->createUrl($controller->id . "/" . $action->id) ?>"><?php echo $data[$controller->id]["text"]; ?></a>
    &nbsp;>&nbsp;&nbsp;<?php echo $data[strtolower($controller->id)][strtolower($action->id)]; ?>
</div>