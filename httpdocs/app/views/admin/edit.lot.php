<?=Session::flash("sysmsg");?>
<script>
	$(function() {
		$(".launch").colorbox({inline:true});
	});
</script>
<form action="/admin/map/save/lot/<?=$lot->getID();?>" method="post">
	<h2>Edit Lot #<?=$lot->getID();?></h2>
	<p>Region: <?=$lot->getRegion();?> / Lot: <?=$lot->getLot();?></p>
	<hr />
	<h3>Lot Details</h3>
	Name: <input type="text" name="name" value="<?=$lot->getName();?>" /><br />
	Price: <input type="text" name="price" value="<?=$lot->getPrice(false);?>" /><br />
	Label: <input type="text" name="label" value="<?=$lot->getLabel();?>" /><br />
	Sold: <input type="checkbox" name="sold" <?=(($lot->getSold() == "true")?"checked":"");?> /><br />
	Active: <input type="checkbox" name="active" <?=(($lot->getActive())?"checked":"");?> /><br />
	Description:<br />
	<textarea name="description"><?=$lot->getDescription();?></textarea>
	<br />
	<h3>Map Files</h3>
	<table>
		<tr>
			<td><a class="launch" href="#edit-image">Edit Image</a></td><td><a class="launch" href="#edit-attachment">Edit Attachment</a></td>
		</tr>
		<tr>
			<td><?=(($lot->getImage()->getSrc() != null)?'<img src="/map-files/images/'.$lot->getImage()->getSrc().'" style="max-width: 175px" />':'')?></td>
			<td><?=(($lot->getAttachment()->getSrc() != null)?'<a href="/map-files/attachments/'.$lot->getAttachment()->getSrc().'"><img src="/images/interface/'.$lot->getAttachment()->getType().'.png" /></a>':'');?></td>
		</tr>
	</table>
	<br />
	<h3>Map Element Positioning</h3>
	<h4>Label</h4>
	Horizontal: <input type="text" name="label_x" value="<?=$lot->getMeta()->getLabelPoint()->getX();?>" /> Vertical: <input type="text" name="label_y" value="<?=$lot->getMeta()->getLabelPoint()->getY();?>" />
	<h4>Price</h4>
	Horizontal: <input type="text" name="price_x" value="<?=$lot->getMeta()->getPricePoint()->getX();?>" /> Vertical: <input type="text" name="price_y" value="<?=$lot->getMeta()->getPricePoint()->getY();?>" />

	<br />
	<br />
	<input type="submit" value="Save Lot" />
</form>

<div style="display:none;">
	
	<div id="edit-attachment">
		
		<form action="/admin/map/save/attachment/<?=$lot->getID();?>" method="post" enctype="multipart/form-data">
			<h2>New Attachment</h2>
			New File: <input type="file" name="attachment" /><br /><br />
			<input type="submit" value="Upload Attachment" style="float: right;" />
		</form>
		
	</div>
	
	<div id="edit-image">
		
		<form action="/admin/map/save/image/<?=$lot->getID();?>" method="post" enctype="multipart/form-data">
			<h2>New Image</h2>
			New File: <input type="file" name="image" /><br /><br />
			<input type="submit" value="Upload Image" style="float: right;" />
		</form>
		
	</div>
	
</div>