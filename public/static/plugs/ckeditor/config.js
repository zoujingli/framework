// 定义编辑器标准配置
CKEDITOR.editorConfig = function (config) {
    config.language = 'zh-cn';
    config.toolbar = [
        {name: 'document', items: ['Source']},
        {name: 'styles', items: ['Font', 'FontSize']},
        {name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'Strike', 'RemoveFormat', 'TextColor', 'BGColor', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', 'NumberedList', 'BulletedList']},
        {name: 'uimage', items: ['Link', 'Unlink', 'Table', 'UploadImage', 'UploadMusic', 'UploadVideo', 'UploadHtml']},
        {name: 'tools', items: ['Maximize']}
    ];
    config.allowedContent = true;
    config.format_tags = 'p;h1;h2;h3;pre';
    config.extraPlugins = 'uimage,umusic,uhtml,uvideo';
    config.removeButtons = 'Underline,Subscript,Superscript';
    config.removeDialogTabs = 'image:advanced;link:advanced';
    config.font_names = '微软雅黑/Microsoft YaHei;宋体/SimSun;新宋体/NSimSun;仿宋/FangSong;楷体/KaiTi;黑体/SimHei;' + config.font_names;
};

// 自定义图片上传插件
CKEDITOR.plugins.add("uimage", {
    init: function (editor) {
        editor.ui.addButton("UploadImage", {label: "上传本地图片", command: 'uimage', icon: 'image', toolbar: 'insert,10'});
        editor.addCommand('uimage', {
            exec: function (editor) {
                let field = '_editor_upload_' + Math.floor(Math.random() * 100000);
                let url = window.ROOT_URL + '?s=admin/plugs/upfile.html&mode=one&type=png,jpg,gif,jpeg&field=' + field;
                $('<input type="hidden">').attr('name', field).appendTo(editor.element.$).on('change', function () {
                    let element = CKEDITOR.dom.element.createFromHtml('<img style="max-width:100%" src="' + this.value + '" border="0" title="image" />');
                    editor.insertElement(element), $(this).remove();
                });
                $.form.iframe(url, '插入图片');
            }
        });
    }
});

// 自定义视频插入插件
CKEDITOR.plugins.add('umusic', {
    init: function (editor) {
        editor.ui.addButton("UploadMusic", {label: "上传MP3文件", command: 'umusic', icon: 'specialchar', toolbar: 'insert,10'});
        editor.addCommand('umusic', {
            exec: function (editor) {
                let field = '_editor_upload_' + Math.floor(Math.random() * 100000);
                let url = window.ROOT_URL + '?s=admin/plugs/upfile.html&mode=one&type=mp3&field=' + field;
                $('<input type="hidden">').attr('name', field).appendTo(editor.element.$).on('change', function () {
                    let element = CKEDITOR.dom.element.createFromHtml('<audio controls="controls"><source src="' + this.value + '" type="audio/mpeg"></audio>');
                    editor.insertElement(element), $(this).remove();
                });
                $.form.iframe(url, '上传MP3音频');
            }
        });
    }
});

// 自定义视频插入插件
CKEDITOR.plugins.add('uvideo', {
    init: function (editor) {
        editor.ui.addButton("UploadVideo", {label: "上传MP4文件", command: 'uvideo', icon: 'flash', toolbar: 'insert,10'});
        editor.addCommand('uvideo', {
            exec: function (editor) {
                let field = '_editor_upload_' + Math.floor(Math.random() * 100000);
                let url = window.ROOT_URL + '?s=admin/plugs/upfile.html&mode=one&type=mp4&field=' + field;
                $('<input type="hidden">').attr('name', field).appendTo(editor.element.$).on('change', function () {
                    let element = CKEDITOR.dom.element.createFromHtml('<video width="100%" controls="controls"><source src="' + this.value + '" type="audio/mp4"></video>');
                    editor.insertElement(element), $(this).remove();
                });
                $.form.iframe(url, '上传MP4音频');
            }
        });
    }
});

// 自定义视频插入插件
CKEDITOR.plugins.add('uhtml', {
    init: function (editor) {
        editor.ui.addButton("UploadHtml", {label: "插入HTML代码", command: 'uhtml', icon: 'creatediv', toolbar: 'insert,10'});
        editor.addCommand('uhtml', {
            exec: function (editor) {
                layer.prompt({title: '插入HTML代码', formType: 2, area: ['600px', '300px']}, function (html, index) {
                    let element = CKEDITOR.dom.element.createFromHtml('<div data-type="insert-html">' + html + '</div>');
                    editor.insertElement(element), layer.close(index);
                });
            }
        });
    }
});

