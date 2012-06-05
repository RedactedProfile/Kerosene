<?=Session::flash("sysmsg");?>
<div id="bg-white">
	<table class="totally-tabular" width="100%" cellspacing="0" cellpadding="3">
		<tr>
			<th>Region</th><th>Lot #</th><th>Status</th><th>Price</th><th style="width: 62px;">Action</th>
		</tr>
		<?foreach($lots as $lot) {?>
		<tr>
			<td style="border-left: none;"><?=$lot->getRegion();?></td>
			<td><?=$lot->getLot();?></td>
			<td><?=(($lot->getSold() == "true")?"Sold":"Available");?></td>
			<td>$<?=$lot->getPrice();?></td>
			<td style="border-right: none; width: 62px"><a class="btn-edit-blog" href="/admin/map/lot/<?=$lot->getID();?>">[E]</a></td>
		</tr>
		<?}?>
		
	</table>
</div>
