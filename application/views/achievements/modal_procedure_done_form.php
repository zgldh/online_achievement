<?php 
$procedure instanceof ProcedurePeer;
?>
<div class="modal hide" id="procedure-done-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="procedure-done-modal-label">我完成了...</h3>
	</div>
	<div class="modal-body">
		<p><?php echo $procedure->description;?></p>
		<form class="procedure-done-form" action="<?php echo BASEURL;?>achievements/work_add_track" method="post">
			<input type="hidden" name="Form[intent_id]" value="<?php echo $intent_id;?>" />
			<input type="hidden" name="Form[procedure_id]" value="<?php echo $procedure->procedure_id;?>" id="procedure-done-modal-procedure-id" />
			<input type="hidden" name="Form[achievement_id]" value="<?php echo $procedure->achievement_id;?>" id="procedure-done-modal-procedure-id" />
			<div>
				<textarea name="Form[content]" rows="3" placeholder="怎么完成的？"></textarea>
			</div>
		</form>
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal" aria-hidden="true" type="button">取消</button>
		<button class="btn btn-primary procedure-done-form-submit">完成</button>
	</div>
</div>