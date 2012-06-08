<script>
	function deletePost(slug) {
		if(confirm('Are you sure you wish to delete this post? This is irreversable.')) window.location = "/admin/blog/delete/"+slug;
	}
	function editPost(slug) {
		window.location = "/admin/blog/edit/"+slug;
	}
	function newPost() {
		window.location = "/admin/blog/new";
	}
</script>

	<div class="edit-blog-title">
		<div style="height:35px;line-height:35px;"><span class="title">News / Blog</span> </div>
		<input class="save-btn" type="button" value="Create New Post" onClick="newPost();" />
	</div>
	<div class="edit-page-settings" style="height:35px;line-height:35px;">
		<div class="display">
			
			<form action="/admin/blog/search" method="post"><input type="text" name="q" value="<?=post('q');?>" /> <input type="submit" value="Search" /></form>
			
		</div>
	</div>


<?//pages?>




<?=$pages;?>

<?=Session::flash("sysmsg");?>
<div id="bg-white">
<table class="totally-tabular" width="100%" cellspacing="0" cellpadding="3">
	<tr>
		<th>Title</th>
		<th>Category</th>
		
		<th>Status</th>
		<th>Published Date</th>
		<th>Date Created</th>
		<th style="width: 143px;">Actions</th>
	</tr>
	<?foreach($posts as $post){?>
	<?//var_dump($post->getCategory());?>
	<tr>
		<td style="border-left: none;">
			<a style="color: #222; font-weight: bold" href="javascript:editPost('<?=$post->getSlug();?>');"><?=$post->getTitle();?></a>
		</td>
		<td>
			<?=$post->getCategory()->getTitle();?>
		</td>
		
		<td>
			<?=(($post->getPublished())?"Published":"Unpublished");?>
		</td>
		<td>
			<?=$post->getDatePublished();?>
		</td>
		<td>
			<?=$post->getDateCreated();?>
		</td>
		<td style="border-right: none; width: 143px;">
			<a class="btn-edit-blog" href="javascript:editPost('<?=$post->getSlug();?>');">[E]</a>
			<a class="btn-delete" href="javascript:deletePost('<?=$post->getSlug();?>');">[D]</a>
		</td>
	</tr>
	<?}?>
</table>
</div>
<?//pages?>
<?=$pages;?>
