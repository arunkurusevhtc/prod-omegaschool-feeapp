<?php
require_once('admnavbar.php');
?>
<!-- Text Editor -->
 <link href="<?php echo BASEURL;?>css/editor.css" type="text/css" rel="stylesheet"/>
 <script type="text/javascript" charset="utf8" src="<?php echo BASEURL;?>js/editor.js"></script>


<div class="container com">
	<div class="row">
		<div class="col-sm-2 col-md-3 "></div>
		<div class="col-sm-8 col-md-6 ">
			<div class=" content1">
				<p class="heading">Add Comments</p>
				<div class="main comments">
					<input name="id" type="hidden" value="<?php echo $res['id'];?>" />
					<form id="comments" method="post" action="adminactions.php">
						<div class="form-group">
								<label for ="pname" class="control-label">Page Name</label>
								<input type="text" id="pname" name="pname" required placeholder="Page Name" class="form-control">
						</div>
                        <div class="form-group ">
                                <label for ="pname" class="control-label">Start Date</label>
                                <input type="text" name="startdate" id = "startdate"
                                class="form-control">
                        </div>
                        <div class="form-group ">
                                <label for ="pname" class="control-label">End Date</label>
                                <input type="text" name="enddate" id = "enddate"
                                class= "form-control">
                        </div>
						<div class="form-group">
								<label for ="txtEditor" class="control-label">Comments</label>
								<textarea id="txtEditor" name="txtEditor" class="form-control"></textarea>
						</div>
						<div class="form-group">
					    	<textarea id="txtEditorContent" name="txtEditorContent" hidden="" ></textarea>
						</div>
						<div class="form-group text-center">
							<button type="submit" value="new" name="addcomments" class="btn btn-primary text-center">Add</button>
							<a href="managecomments.php"><button type="button" value="Go Back" class="btn btn-warning text-center">Back</button></a>
					    </div>
					    
					    
                    </form>
				</div>
			</div>
		</div>
		<div class="col-sm-2 col-md-3"></div>
	</div>
</div>
<div class="row comment">
       
</div>
<script>
$(function(){
	$("#txtEditor").Editor({
        'texteffects':false,
        'aligneffects':false,
        'textformats':false,
        'fonteffects':false,
        'actions' : false,
        'insertoptions' : false,
        'extraeffects' : false,
        'advancedoptions' : false,
        'screeneffects':false,
        'bold': true,
        'italics': true,
        'underline':true,
        'ol':false,
        'ul':false,
        'undo':true,
        'redo':true,
        'l_align':true,
        'r_align':true,
        'c_align':true,
        'justify':true,
        'insert_link':false,
        'unlink':false,
        'insert_img':false,
        'hr_line':false,
        'block_quote':false,
        'source':false,
        'strikeout':false,
        'indent':false,
        'outdent':false,
        'print':false,
        'rm_format':false,
        'status_bar':false,
        'insert_table':false,
        'select_all':false,
        'togglescreen':false

        });
    
});	

$("button:submit").click(function(){
    $('#txtEditorContent').text($('#txtEditor').Editor("getText"));
    });
</script>
<?php






include_once(BASEPATH.'footer.php');
?>
