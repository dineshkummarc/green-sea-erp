<form id="pagerForm" action="<?php echo $this->controller->createUrl(""); ?>" method="post" >
    <input type="hidden" name="pageNum" value="1" />
    <input type="hidden" name="numPerPage" value="<?php echo $pages->pageSize; ?>" />
</form>
<div class="panelBar" style="<?php if ($this->style !== null) echo $this->style; ?>">
    <div class="pages">
        <span>显示</span>
            <select class="combox" name="numPerPage" change="navTabPageBreak" param="numPerPage" default="<?php echo $pages->pageSize; ?>">
                <?php foreach ($this->pageSizes as $pageSize): ?>
                <option value="<?php echo $pageSize; ?>"><?php echo $pageSize; ?></option>
                <?php endforeach; ?>
            </select>
        <span>共<?php echo $pages->itemCount; ?>条记录</span>
    </div>
    <div class="pagination" targetType="navTab"
        totalCount="<?php echo $pages->itemCount; ?>"
        numPerPage="<?php echo $pages->pageSize; ?>"
        pageNumShown="3"
        currentPage="<?php echo $pages->currentPage + 1; ?>">
    </div>
</div>