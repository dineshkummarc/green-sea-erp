<ul class="menu">
    <li class="title">栏目导航</li>
    <?php foreach ($menuList as $menu): ?>
    <li class="parent <?php if ($controller->id == $menu['active'] || $action->id == $menu['active']) echo 'active'; ?> <?php echo empty($menu["class"]) ? "" : $menu["class"]; ?>">
        <?php if (empty($menu['url'])): ?>
        <span><?php echo $menu['text']; ?></span>
        <?php else: ?>
        <a href="<?php echo $controller->createUrl($menu['url']); ?>"><?php echo $menu['text']; ?></a>
        <?php endif; ?>
        <?php if (isset($menu['child'])) foreach ($menu['child'] as $child): ?>
        <li class="child">
            <?php if (empty($child['url'])): ?>
            <span class="<?php if ($action->id == $child['active']) echo "active"; ?>
                <?php echo empty($child["class"]) ? "" : $child["class"]; ?>"><?php echo $child['text']; ?></span>
            <?php else: ?>
            <a class="<?php if ($action->id == $child['active']) echo "active"; ?> <?php echo empty($child["class"]) ? "" : $child["class"]; ?>"
                href="<?php echo $controller->createUrl($child['url']); ?>"><?php echo $child['text']; ?></a>
            <?php endif; ?>
        </li>
        <?php endforeach; ?>
    </li>
    <?php endforeach; ?>
</ul>