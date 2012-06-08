<?=Session::flash("sysmsg");?>
<script>
	$(function() {
		$(".add").colorbox({inline:true});
	});
	
	function UpdateIsBold(id) {
		if($("#check_"+id).is(":checked")) {
			$("#is_bold_"+id).val("true");
		} else {
			$("#is_bold_"+id).val("false");
		}
	}
	
	function remove(type, id) {
		$.post("/admin/ajax/delete-"+type, {
			id: id
		}, function(data) {
			if(data.status == "false") {
				alert( data.msg );
			} else {
				window.location = "/admin/settings/map";
			}
		}, "json");
	}
</script>
<form action="/admin/save-settings/map" method="post">
	
	<fieldset>
		<legend>Lot States:</legend>
		<table cellpadding="5">
			<tr>
				<th>Stale</th><th>Hover</th><th>Click</th>
			</tr>
			<tr>
				<td><input type="text" class="color" size="6" name="lot_state_stale" value="<?=$map->lot_state_stale;?>" /><input type="text" size="2" name="lot_state_stale_alpha" value="<?=$map->lot_state_stale_alpha;?>" /></td>
				<td><input type="text" class="color" size="6" name="lot_state_hover" value="<?=$map->lot_state_hover;?>" /><input type="text" size="2" name="lot_state_hover_alpha" value="<?=$map->lot_state_hover_alpha;?>" /></td>
				<td><input type="text" class="color" size="6" name="lot_state_click" value="<?=$map->lot_state_click;?>" /><input type="text" size="2" name="lot_state_click_alpha" value="<?=$map->lot_state_click_alpha;?>" /></td>
			</tr>
		</table>
	</fieldset>
	
	<fieldset>
		<legend>Information Box</legend>
		<table>
			<tr>
				<th>Width</th><th>Height</th><th>In Time</th><th>Out Time</th><th>Background Color</th><th>Box Color</th>
			</tr>
			<tr>
				<td><input type="text" name="info_box_width" value="<?=$map->info_box_width;?>" /></td>
				<td><input type="text" name="info_box_height" value="<?=$map->info_box_height;?>" /></td>
				<td><input type="text" name="info_box_intime" value="<?=$map->info_box_in_time;?>" /></td>
				<td><input type="text" name="info_box_outtime" value="<?=$map->info_box_out_time;?>" /></td>
				<td><input type="text" class="color" size="6" name="info_box_bgcolor" value="<?=$map->info_box_bg_color;?>" /><input type="text" size="2" name="info_box_bgcolor_alpha" value="<?=$map->info_box_bg_color_alpha;?>" /></td>
				<td><input type="text" class="color" size="6" name="info_box_boxcolor" value="<?=$map->info_box_box_color;?>" /><input type="text" size="2" name="info_box_boxcolor_alpha" value="<?=$map->info_box_box_color_alpha;?>" /></td>
			</tr>
		</table>
		<fieldset>
			<legend>Fields</legend>
			<table>
			<tr>
				<th>Type</th><th>Horizontal Offset</th><th>Vertical Offset</th><th>Width</th><th>Height</th><th>Other</th><th>Action</th>
			</tr>
			<?foreach($map->getField() as $field) {?>
			<tr>
				<td><?=Settings::$MapFields[$field->type];?><input type="hidden" name="field_type[]" value="<?=$field->type;?>"</td>
				<td><input name="field_x[]" type="text" size="4" value="<?=$field->x;?>" /></td>
				<td><input name="field_y[]" type="text" size="4" value="<?=$field->y;?>" /></td>
				<td><input name="field_width[]" type="text" size="4" value="<?=$field->width;?>" /></td>
				<td><input name="field_height[]" type="text" size="4" value="<?=$field->height;?>" /></td>
				<td><?
				switch($field->type) {
					case "image":
						?>
					<select name="field_other[]">
						<option value="fade" <?=(($field->transition == "fade")?"selected":null);?>>Fade In</option>
						<option value="creep" <?=(($field->transition == "creep")?"selected":null);?>>Slide Down</option>
					</select>
						<?
						break;
					default: 
						echo "
						<input type='hidden' name='field_other[]' value='0' />";
						break;
				}
				?></td>
				<td><a href="#" onClick="remove('field', '<?=$field->type;?>')">-</a></td>
			</tr>
			<?}?>
			</table>
			<a class="add" href="#add_field">+ Add Field</a>
		</fieldset>
	</fieldset>
	
	<fieldset>
		<legend>Fonts</legend>
		<table>
			<tr>
				<th>Field</th><th>Size</th><th>Color</th><th>Bold?</th><th>Align</th><th>Action</th>
			</tr>
			<?foreach($map->getFont() as $k=>$font) {?>
			<tr>
				<td><?=Settings::$FontFields[$font->field];?> <input type="hidden" name="font_field[]" value="<?=$font->field;?>" /></td>
				<td><input name="font_size[]" type="text" size="4" value="<?=$font->size;?>" /></td>
				<td><input class="color" name="font_color[]" type="text" size="4" value="<?=$font->color;?>" /><input name="font_color_alpha[]" type="text" size="2" value="<?=$font->alpha;?>" /></td>
				<td><input onChange="UpdateIsBold(<?=$k;?>)" id="check_<?=$k;?>" type="checkbox" <?=(($font->bold == "true")?"checked":"");?> /> <input type="hidden" name="font_bold[]" id="is_bold_<?=$k;?>" value="<?=$font->bold;?>" /></td>
				<td><select name="font_align[]">
					<option value="left" <?=(($font->align == "left")?"selected":"");?>>Left</option>
					<option value="right" <?=(($font->align == "right")?"selected":"");?>>Right</option>
					<option value="center" <?=(($font->align == "center")?"selected":"");?>>Center</option>
					<option value="justified" <?=(($font->align == "justified")?"selected":"");?>>Justified</option>
				</select></td>
				<td>
					<a href="#" onClick="remove('font', '<?=$font->field;?>')">-</a>
				</td>
			</tr>
			<?}?>
		</table>
		<a class="add" href="#add_font">+ Add Font</a>
	</fieldset>
	
	<input type="submit" value="submit" />
</form>



<div style="display: none;">
	<div id="add_field">
		<h2>Add New Field</h2>
		<form action="/admin/settings/add-field" method="post">
			<table>
				<tr>
					<th>Type</th><th>Horizontal Offset</th><th>Vertical Offset</th><th>Width</th><th>Height</th>
				</tr>
				<tr>
					<td>
						<select name="field_type">
							<?foreach(Settings::getAvailableMapFields() as $k=>$field){?>
								<option value="<?=$k;?>"><?=$field;?></option>
							<?}?>
						</select>
					</td>
					<td>
						<input type="text" name="field_x" />
					</td>
					<td>
						<input type="text" name="field_y" />
					</td>
					<td>
						<input type="text" name="field_width" />
					</td>
					<td>
						<input type="text" name="field_height" />
					</td>
				</tr>
			</table>
			<div style="float: right;"><input type="submit" value="Create New Field" /></div>
		</form>
	</div>
	
	
	<div id="add_font">
		<h2>Add New Font</h2>
		<form action="/admin/settings/add-font" method="post">
			<table>
				<tr>
					<th>Field</th><th>Size</th><th>Color</th><th>Bold?</th><th>Align</th>
				</tr>
				<tr>
					<td>
						<select name="font_field">
							<?foreach(Settings::getAvailableFontFields() as $k=>$field){?>
								<option value="<?=$k;?>"><?=$field;?></option>
							<?}?>
						</select>
					</td>
					<td>
						<input type="text" name="font_size" />
					</td>
					<td>
						<input class="color" size="6" type="text" name="font_color" />
						<input size="2" type="text" name="font_alpha" />
					</td>
					<td>
						<input type="checkbox" name="font_bold" />
					</td>
					<td>
						<select name="font_align">
							<option value="left">Left</option>
							<option value="right">Right</option>
							<option value="center">Center</option>
							<option value="justified">Justified</option>
						</select>
					</td>
				</tr>
			</table>
			<div style="float: right;"><input type="submit" value="Create New Font" /></div>
		</form>
	</div>
	
</div>