window.onload = function() {
$(function(){
    $('#upload').change(function(){
		console.log("Onload filereader");    
        if (!this.files.length) {
            return;
        }
        var file = this.files[0],           //画像１つのみ選択
            image = $('.image-box'),
            fileReader = new FileReader();

        // 読み込みが完了した際のイベントハンドラ。imgのsrcにデータセット
        fileReader.onload = function(event) {
		console.log("Onload filereader");            
// 読み込んだデータをimgに設定
            image.children('img').attr('src', event.target.result);
            // imgLiquid - imgの親要素に指定
		console.log(event.target.result);
            image.imgLiquid();
        };
        // 画像読み込み
        fileReader.readAsDataURL(file);

    });


});
}