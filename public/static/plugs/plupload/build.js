define(['plupload'], function (plupload) {
    window.plupload = plupload;
    return function (element) {
        var $ele = $(element);
        var loader = new plupload.Uploader({
            multi_selection: false,
            multipart_params: {
                safe: $ele.attr('data-safe') || '0',
                uptype: $ele.attr('data-uptype') || '',
            },
            drop_element: $ele.get(0),
            browse_button: $ele.get(0),
            url: '?s=admin/api.plugs/plupload',
            runtimes: 'html5,flash,silverlight,html4',
            file_data_name: $ele.attr('data-name') || 'file',
            flash_swf_url: baseRoot + 'plugs/plupload/Moxie.swf',
            silverlight_xap_url: baseRoot + 'plugs/plupload/Moxie.xap',
            filters: [{title: 'files', extensions: $ele.attr('data-type') || '*'}],
        });
        loader.bind('FilesAdded', function () {
            loader.start();
        });
        loader.bind('FileUploaded', function (up, file, res) {
            if (parseInt(res.status) === 200) {
                var ret = JSON.parse(res.response), field = $ele.data('field') || 'file';
                $('[name="' + field + '"]').val(ret.url).trigger('change');
            }
        });
        loader.bind('UploadProgress', function (up, file) {
            $ele.html(parseFloat(file.loaded * 100 / file.total).toFixed(2) + '%');
        });
        loader.bind('UploadComplete', function () {
            $ele.html($ele.data('html'));
        });
        $ele.data('html', $ele.html()), loader.init();
    }
});