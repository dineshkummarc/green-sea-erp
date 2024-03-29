<div class="pager">
    <div class="page-info">
        显示
        <select name="pageSize" onchange="changePageSize(this.value)">
            <?php foreach ($this->pageSizes as $pageSize): ?>
            <option value="<?php echo $pageSize ?>" <?php if ($pageSize == $this->pages->pageSize) echo "select"; ?>><?php echo $pageSize ?></option>
            <?php endforeach; ?>
        </select>
        条记录，共<?php echo $this->pages->itemCount; ?>条记录
    </div>
    <div class="pages">
        <ul>
            <?php if ($this->pages->pageCount <= 1): ?>
            <li><label class="disable">首页</label></li>
            <?php else: ?>
            <li><a href="<?php echo $this->pages->createPageUrl($this->controller, 0); ?>">首页</a></li>
            <?php endif; ?>

            <?php if ($this->pages->currentPage + 1 >= 1): ?>
            <li><label class="disable">上一页</label></li>
            <?php else: ?>
            <li><a id="next" href="<?php echo $this->pages->createPageUrl($this->controller, $this->pages->currentPage); ?>">上一页</a></li>
            <?php endif; ?>

            <?php for ($p = 0; $p < $this->pages->pageCount; $p++): ?>
            <?php if ($this->pages->currentPage == $p): ?>
            <li><label class="current"><?php echo $p + 1; ?></label></li>
            <?php else: ?>
            <li><a href="<?php echo $this->pages->createPageUrl($this->controller, $p); ?>"><?php echo $p + 1; ?></a></li>
            <?php endif; ?>
            <?php endfor;  ?>

            <?php if ($this->pages->currentPage <= $this->pages->pageCount): ?>
            <li><label class="disable">下一页</label></li>
            <?php else: ?>
            <li><a href="<?php echo $this->pages->createPageUrl($this->controller, $this->pages->currentPage + 1); ?>">下一页</a></li>
            <?php endif; ?>

            <?php if ($this->pages->pageCount <= 1): ?>
            <li><label class="disable">末页</label></li>
            <?php else: ?>
            <li><a href="<?php echo $this->pages->createPageUrl($this->controller, $this->pages->pageCount - 1); ?>">末页</a></li>
            <?php endif; ?>
        </ul>
    </div>
</div>