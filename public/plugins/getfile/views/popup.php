<!-- Crop window START -->
<div style="display:none">
    <div id="wrapGetFile">
        <h3><?php echo $_POST['cropWindowTitle']?></h3>
        <div id="gfContainer" class="imgs-crop-container"></div>
        <div id="gfPreview">
            <h4 class="title-preview"><?php echo $_POST['previewTitle']?></h4>
            <div class="imgs-preview-container"></div>
        </div>
        <div id="gfFooter">
            <button type="button" id="gfCropButton" class="btngf btngf-primary"><?php echo $_POST['cropButtonText']?></button>
            <button type="button" id="gfCancelButton" class="btngf"><?php echo $_POST['cancelButtonText']?></button>
        </div>
    </div>
</div>
<!-- Crop window END -->