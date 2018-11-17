define(['plupload'], function (plupload) {
    window.plupload = plupload;
    return function (element) {
        let $ele = $(element);
        let loader = new plupload.Uploader({
            multi_selection: false,
            multipart_params: {
                safe: $ele.attr('data-safe') || '',
                name: $ele.attr('data-name') || 'file',
                uptype: $ele.attr('data-uptype') || '',
            },
            browse_button: $ele.get(0),
            url: '?s=admin/plugs/plupload',
            runtimes: 'html5,flash,silverlight,html4',
            file_data_name: $ele.attr('data-name') || 'file',
            flash_swf_url: baseRoot + 'plugs/plupload/plupload.flash.swf',
            filters: [{title: 'files', extensions: $ele.attr('data-type') || '*'}],
            silverlight_xap_url: baseRoot + 'plugs/plupload/plupload.silverlight.xap',
        });
        loader.bind('FilesAdded', function () {
            loader.start();
        });
        loader.bind('FileUploaded', function (up, file, res) {
            if (parseInt(res.status) === 200) {
                let ret = JSON.parse(res.response), field = $ele.data('field') || 'file';
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