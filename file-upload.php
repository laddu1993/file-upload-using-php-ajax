<?php
$save_path = dirname(__FILE__)."/uploads/";
$actual_url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
$actual_url .= (isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '');
$actual_url = explode("/", $actual_url);
array_pop($actual_url);
$actual_url = implode("/", $actual_url).'/uploads/';
if (!empty($_FILES) && isset($_FILES)) {
    $rand = rand();
    $image_name = $rand.'_'.$_FILES['file_image']['name'];
    $tmp_image_name = $_FILES['file_image']['tmp_name'];
    $image_type = $_FILES['file_image']['type'];
    $image_size = $_FILES['file_image']['size'];
    $status = move_uploaded_file($tmp_image_name, $save_path.$image_name);
    $file_arr = array('web_url' => $actual_url.$image_name, 'status' => 'success', 'message' => 'Image uploaded successfully', 'file_name' => $image_name);
    echo json_encode($file_arr);
    exit();
}
?>
<!doctype html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html">
    <title>FILE UPLOAD USING PHP AJAX</title>
    <meta name="author" content="Vinil Lakkavatri">
</head>

<body>
   <div id="wrapper">
    <h1>FILE UPLOAD USING PHP AJAX</h1>

    <form action="" method="post" id="imageUploadForm" enctype="multipart/form-data">
		    <div class="col-6">
		      <label>
		      Image Upload
		      <input type="file" name="fileToUpload" id="fileToUpload" class="form-control" required>
		      <input type="hidden" name="file_image" id="file_image" >
              <br><br>
              <div id="show_image" style="display: none;">
                  <img src="" style="width: 150px;height: 100px;">
              </div>
		      <br>
		      <img id="loading" src="loading.gif" />
		      <br>
		      <div id="result"></div>
		    </label>
		    </div>
	</form>
   </div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script type="text/javascript">
var MyApp = MyApp || {};
MyApp.Method = {
    file_browse: function(formData, url) {
        var result;
        $.ajax({
            type: 'post',
            dataType: 'json',
            data: formData,
            enctype: 'multipart/form-data',
            contentType: false,
            cache: false,
            processData: false,
            async: false,
            url: url,
            beforeSend: function(xhr) {
                $("#loading").show();
            },
            success: function(data) {
                $("#loading").hide();
                result = data;
            },
            error: function(data) {
                result = data;
            }
        });
        return result;
    }
};

$("#loading").hide();
$('#fileToUpload').on('change', function(e) {
    e.preventDefault();
    var formData = new FormData();
    formData.append('file_image', $('#wrapper input[type=file]')[0].files[0]);
    var image_data = MyApp.Method.file_browse(formData, '<?php echo $_SERVER['PHP_SELF']; ?>');
    if (image_data.status == 'success') {
        $('#result').html(image_data.message);
        $('#file_image').val(image_data.web_url);
        $('#show_image').show();
        $('#show_image img').attr("src", image_data.web_url);
    } else {
        err_d = 'Image is not Uploaded!';
        $('#result').html(err_d);
    }
});
</script>
</body>

</html>