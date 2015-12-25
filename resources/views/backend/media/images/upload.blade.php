@extends('backend.layouts.master')
@section('after-styles-end')
    <link rel="stylesheet" href="http://jssdk.demo.qiniu.io/main.css">
@endsection
@section('page-header')
    <h1>
        图片管理
        <small>{{ trans('strings.backend.dashboard_title') }}</small>
    </h1>
@endsection

@section('breadcrumbs')
    <li><a href="{!!route('backend.dashboard')!!}"><i class="fa fa-dashboard"></i> {{ trans('menus.dashboard') }}</a>
    </li>
    <li class="active">{{ trans('strings.here') }}</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-1">
            <a href="{{url('admin/images')}}" class="btn btn-primary btn-block margin-bottom">返回</a>
            <!-- /. box -->
        </div>
        <!-- /.col -->
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">上传图片</h3>

                    <div class="box-tools pull-right">
                        <div class="has-feedback">
                            <span class="glyphicon glyphicon-search form-control-feedback"></span>
                        </div>
                    </div>
                    <!-- /.box-tools -->
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="containerr">
                        <div class="text-left col-md-12 ">
                            <input type="hidden" id="uptoken_url" value="/uptoken">
                        </div>
                        <div class="body">
                            <div class="col-md-12">
                                <div id="drop-container">
                                    <a class="btn btn-default btn-lg " id="pickfiles" href="#">
                                        <i class="glyphicon glyphicon-plus"></i>
                                        <span>选择文件</span>
                                    </a>
                                </div>
                            </div>

                            <div style="display:none" id="success" class="col-md-12">
                                <div class="alert-success">
                                    队列全部文件处理完毕
                                </div>
                            </div>
                            <div class="col-md-12 ">
                                <table class="table table-striped table-hover text-left"
                                       style="margin-top:40px;display:none">
                                    <thead>
                                    <tr>
                                        <th class="col-md-4">Filename</th>
                                        <th class="col-md-2">Size</th>
                                        <th class="col-md-6">Detail</th>
                                    </tr>
                                    </thead>
                                    <tbody id="fsUploadProgress">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="modal fade body" id="myModal-code" tabindex="-1" role="dialog"
                             aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal"
                                                aria-hidden="true">&times;</button>
                                        <h4 class="modal-title" id="myModalLabel">查看初始化代码</h4>
                                    </div>
                                    <div class="modal-body">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-primary" data-dismiss="modal">关闭</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal fade body" id="myModal-img" tabindex="-1" role="dialog"
                             aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal"
                                                aria-hidden="true">&times;</button>
                                        <h4 class="modal-title" id="myModalLabel">图片效果查看</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="modal-body-wrapper text-center">
                                            <a href="" target="_blank">
                                                <img src="" alt="" data-key="" data-h="">
                                            </a>
                                        </div>
                                        <div class="modal-body-footer">
                                            <div class="watermark">
                                                <span>水印控制：</span>
                                                <a href="#" data-watermark="NorthWest" class="btn btn-default">
                                                    左上角
                                                </a>
                                                <a href="#" data-watermark="SouthWest" class="btn btn-default">
                                                    左下角
                                                </a>
                                                <a href="#" data-watermark="NorthEast" class="btn btn-default">
                                                    右上角
                                                </a>
                                                <a href="#" data-watermark="SouthEast" class="btn btn-default disabled">
                                                    右下角
                                                </a>
                                                <a href="#" data-watermark="false" class="btn btn-default">
                                                    无水印
                                                </a>
                                            </div>
                                            <div class="imageView2">
                                                <span>缩略控制：</span>
                                                <a href="#" data-imageview="large" class="btn btn-default disabled">
                                                    大缩略图
                                                </a>
                                                <a href="#" data-imageview="middle" class="btn btn-default">
                                                    中缩略图
                                                </a>
                                                <a href="#" data-imageview="small" class="btn btn-default">
                                                    小缩略图
                                                </a>
                                            </div>
                                            <div class="imageMogr2">
                                                <span>高级控制：</span>
                                                <a href="#" data-imagemogr="left"
                                                   class="btn btn-default no-disable-click">
                                                    逆时针
                                                </a>
                                                <a href="#" data-imagemogr="right"
                                                   class="btn btn-default no-disable-click">
                                                    顺时针
                                                </a>
                                                <a href="#" data-imagemogr="no-rotate" class="btn btn-default">
                                                    无旋转
                                                </a>
                                            </div>
                                            <div class="text-warning">
                                                备注：小图片水印效果不明显，建议使用大图片预览水印效果
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <span class="pull-left">本示例仅演示了简单的图片处理效果，了解更多请点击</span>

                                        <a href="https://github.com/SunLn/qiniu-js-sdk" target="_blank"
                                           class="pull-left">本SDK文档</a>
                                        <span class="pull-left">或</span>

                                        <a href="http://developer.qiniu.com/docs/v6/api/reference/fop/image/"
                                           target="_blank" class="pull-left">七牛官方文档</a>

                                        <button type="button" class="btn btn-primary" data-dismiss="modal">关闭</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer clearfix">

                </div>
            </div>
            <!-- /. box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
@endsection

@section('after-scripts-end')
    <script type="text/javascript"
            src="http://jssdk.demo.qiniu.io/js/plupload/plupload.full.min.js"></script>
    <script type="text/javascript" src="http://jssdk.demo.qiniu.io/js/plupload/i18n/zh_CN.js"></script>
    <script type="text/javascript" src="http://jssdk.demo.qiniu.io//js/ui.js"></script>
    <script type="text/javascript" src="http://jssdk.demo.qiniu.io//js/qiniu.js"></script>
    <script type="text/javascript" src="http://jssdk.demo.qiniu.io//js/highlight/highlight.js"></script>
    <script type="text/javascript">
        /*global Qiniu */
        /*global plupload */
        /*global FileProgress */
        /*global hljs */


        $(function () {
            var uploader = Qiniu.uploader({
                runtimes: 'html5,flash,html4',
                browse_button: 'pickfiles',
                container: 'drop-container',
                drop_element: 'drop-container',
                max_file_size: '100mb',
                dragdrop: true,
                chunk_size: '4mb',
                uptoken: app.qiniu_token,
                domain: '{{env('QINIU_DEFAULT_DOMAIN')}}',
                get_new_uptoken: false,
                // downtoken_url: '/downtoken',
                // unique_names: true,
                // save_key: true,
                // x_vars: {
                //     'id': '1234',
                //     'time': function(up, file) {
                //         var time = (new Date()).getTime();
                //         // do something with 'time'
                //         return time;
                //     },
                // },
                auto_start: true,
                init: {
                    'FilesAdded': function (up, files) {
                        $('table').show();
                        $('#success').hide();
                        plupload.each(files, function (file) {
                            var progress = new FileProgress(file, 'fsUploadProgress');
                            progress.setStatus("等待...");
                            progress.bindUploadCancel(up);
                        });
                    },
                    'BeforeUpload': function (up, file) {
                        var progress = new FileProgress(file, 'fsUploadProgress');
                        var chunk_size = plupload.parseSize(this.getOption('chunk_size'));
                        if (up.runtime === 'html5' && chunk_size) {
                            progress.setChunkProgess(chunk_size);
                        }
                    },
                    'UploadProgress': function (up, file) {
                        var progress = new FileProgress(file, 'fsUploadProgress');
                        var chunk_size = plupload.parseSize(this.getOption('chunk_size'));
                        progress.setProgress(file.percent + "%", file.speed, chunk_size);
                    },
                    'UploadComplete': function () {
                        $('#success').show();
                    },
                    'FileUploaded': function (up, file, info) {
                        var progress = new FileProgress(file, 'fsUploadProgress');
                        progress.setComplete(up, info);
                    },
                    'Error': function (up, err, errTip) {
                        $('table').show();
                        var progress = new FileProgress(err.file, 'fsUploadProgress');
                        progress.setError();
                        progress.setStatus(errTip);
                    }
                    // ,
                    // 'Key': function(up, file) {
                    //     var key = "";
                    //     // do something with key
                    //     return key
                    // }
                }
            });

            uploader.bind('FileUploaded', function () {
                console.log('hello man,a file is uploaded');
            });
            $('#drop-container').on(
                'dragenter',
                function (e) {
                    e.preventDefault();
                    $('#drop-container').addClass('draging');
                    e.stopPropagation();
                }
            ).on('drop', function (e) {
                e.preventDefault();
                $('#drop-container').removeClass('draging');
                e.stopPropagation();
            }).on('dragleave', function (e) {
                e.preventDefault();
                $('#drop-container').removeClass('draging');
                e.stopPropagation();
            }).on('dragover', function (e) {
                e.preventDefault();
                $('#drop-container').addClass('draging');
                e.stopPropagation();
            });

            $('#show_code').on('click', function () {
                $('#myModal-code').modal();
                $('pre code').each(function (i, e) {
                    hljs.highlightBlock(e);
                });
            });


            $('body').on('click', 'table button.btn', function () {
                $(this).parents('tr').next().toggle();
            });


            var getRotate = function (url) {
                if (!url) {
                    return 0;
                }
                var arr = url.split('/');
                for (var i = 0, len = arr.length; i < len; i++) {
                    if (arr[i] === 'rotate') {
                        return parseInt(arr[i + 1], 10);
                    }
                }
                return 0;
            };

            $('#myModal-img .modal-body-footer').find('a').on('click', function () {
                var img = $('#myModal-img').find('.modal-body img');
                var key = img.data('key');
                var oldUrl = img.attr('src');
                var originHeight = parseInt(img.data('h'), 10);
                var fopArr = [];
                var rotate = getRotate(oldUrl);
                if (!$(this).hasClass('no-disable-click')) {
                    $(this).addClass('disabled').siblings().removeClass('disabled');
                    if ($(this).data('imagemogr') !== 'no-rotate') {
                        fopArr.push({
                            'fop': 'imageMogr2',
                            'auto-orient': true,
                            'strip': true,
                            'rotate': rotate,
                            'format': 'png'
                        });
                    }
                } else {
                    $(this).siblings().removeClass('disabled');
                    var imageMogr = $(this).data('imagemogr');
                    if (imageMogr === 'left') {
                        rotate = rotate - 90 < 0 ? rotate + 270 : rotate - 90;
                    } else if (imageMogr === 'right') {
                        rotate = rotate + 90 > 360 ? rotate - 270 : rotate + 90;
                    }
                    fopArr.push({
                        'fop': 'imageMogr2',
                        'auto-orient': true,
                        'strip': true,
                        'rotate': rotate,
                        'format': 'png'
                    });
                }

                $('#myModal-img .modal-body-footer').find('a.disabled').each(function () {

                    var watermark = $(this).data('watermark');
                    var imageView = $(this).data('imageview');
                    var imageMogr = $(this).data('imagemogr');

                    if (watermark) {
                        fopArr.push({
                            fop: 'watermark',
                            mode: 1,
                            image: 'http://www.b1.qiniudn.com/images/logo-2.png',
                            dissolve: 100,
                            gravity: watermark,
                            dx: 100,
                            dy: 100
                        });
                    }

                    if (imageView) {
                        var height;
                        switch (imageView) {
                            case 'large':
                                height = originHeight;
                                break;
                            case 'middle':
                                height = originHeight * 0.5;
                                break;
                            case 'small':
                                height = originHeight * 0.1;
                                break;
                            default:
                                height = originHeight;
                                break;
                        }
                        fopArr.push({
                            fop: 'imageView2',
                            mode: 3,
                            h: parseInt(height, 10),
                            q: 100,
                            format: 'png'
                        });
                    }

                    if (imageMogr === 'no-rotate') {
                        fopArr.push({
                            'fop': 'imageMogr2',
                            'auto-orient': true,
                            'strip': true,
                            'rotate': 0,
                            'format': 'png'
                        });
                    }
                });

                var newUrl = Qiniu.pipeline(fopArr, key);

                var newImg = new Image();
                img.attr('src', 'loading.gif');
                newImg.onload = function () {
                    img.attr('src', newUrl);
                    img.parent('a').attr('href', newUrl);
                };
                newImg.src = newUrl;
                return false;
            });

        });
    </script>
@endsection
