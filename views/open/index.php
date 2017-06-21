<div class="modal-dialog animated fadeIn" style="width:96%">
    <div class="modal-content onlyDocumentsModal" style="background-color:transparent;">
        <?=
        \humhub\modules\onlydocuments\widgets\EditorWidget::widget([
            'file' => $file,
            'mode' => $mode
        ]);
        ?>
    </div>
</div>
<script>
    window.onload = function (evt) {
        setSize();
    }
    window.onresize = function (evt) {
        setSize();
    }
    setSize();

    function setSize() {
        $('.onlyDocumentsModal').css('height', window.innerHeight - 110 + 'px');
    }
</script>