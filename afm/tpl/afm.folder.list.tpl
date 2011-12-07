<!-- BEGIN: MAIN -->

{FILE "./{PHP.cfg.themes_dir}/{PHP.theme}/warnings.tpl"}

<table>
  <thead>
    <tr>
      <th>File/Folder</th>
      <th>Size</th>
      <th>Description</th>
    </tr>
  </thead>
  <tbody>
  <!-- BEGIN: SUBFOLDERS -->
  <!-- BEGIN: ROW -->
    <tr>
      <td colspan="3">
        <img src="images/icons/default/folder.png" alt=""/>
        <a href="{SUBFOLDER_ID|cot_url('afm', 'm=folder&a=list&id=$this')}">{SUBFOLDER_NAME}</a>
      </td>
    </tr>
  <!-- END: ROW -->
  <!-- END: SUBFOLDERS -->
  
  <!-- BEGIN: FILES -->
  <!-- BEGIN: ROW -->
    <tr>
      <td>{FILE_ICON} <a href="{FILE_ID|cot_url('afm', 'm=file&a=download&id=$this&view=1')}">{FILE_NAME}</a></td>
      <td>{FILE_SIZE|cot_build_filesize($this, 1)}</td>
      <td>{FILE_METADATA_DESCRIPTION}</td>
    </tr>
  <!-- END: ROW -->
  <!-- END: FILES -->
  </tbody>
  <tfoot>
    <tr>
      <th></th>
      <th>{PHP.numfiles} {PHP.L.Files} | {PHP.L.Page} {PHP.page}</th>
      <th></th>
    </tr>
  </tfoot>
</table>

<!-- BEGIN: FORM_UPLOAD -->
<form action="{PHP|cot_url('afm', 'm=file&a=upload')}" method="post" enctype="multipart/form-data">
	<h2>Upload files</h2>
  <!-- FOR {I} IN {PHP|range(1,5)} -->
	<p>
    <label for="title{I}">Title:</label><input type="text" name="title[]" id="title{I}" /><br/>
		<label for="desc{I}">Description:</label><input type="text" name="description[]" id="desc{I}" /><br/>
		<label for="file{I}"><input type="file" name="file[]" id="file{I}" />
	</p>
  <!-- ENDFOR -->
	<button type="submit">Upload</button>
</form>
<!-- END: FORM_UPLOAD -->

<!-- BEGIN: FORM_CREATEFOLDER -->
<form action="{PHP|cot_url('afm', 'm=folder&a=create')}" method="post">
	<h2>Create new folder</h2>
	<p>
		Parent ID:<br/><input type="text" name="parentid">
	</p>
	<p>
		Title:<br/><input type="text" name="name">
	</p>
	<p>
		Description:<br/><input type="text" name="description">
	</p>
	<button type="submit">Create folder</button>
</form>
<!-- END: FORM_CREATEFOLDER -->

<!-- END: MAIN -->