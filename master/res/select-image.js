window.onload = function() {
$(function(){
    $('#upload').change(function(){
		console.log("Onload filereader");    
        if (!this.files.length) {
            return;
        }
        var file = this.files[0],           //�摜�P�̂ݑI��
            image = $('.image-box'),
            fileReader = new FileReader();

        // �ǂݍ��݂����������ۂ̃C�x���g�n���h���Bimg��src�Ƀf�[�^�Z�b�g
        fileReader.onload = function(event) {
		console.log("Onload filereader");            
// �ǂݍ��񂾃f�[�^��img�ɐݒ�
            image.children('img').attr('src', event.target.result);
            // imgLiquid - img�̐e�v�f�Ɏw��
		console.log(event.target.result);
            image.imgLiquid();
        };
        // �摜�ǂݍ���
        fileReader.readAsDataURL(file);

    });


});
}