<style>
    .wx-hide {
        display: none;
    }

    .wx-fl {
        float: left;
    }

    .wx-fr {
        float: right;
    }

    .wx-btn {
        border: none;
        padding: 8px 18px;
        border-radius: 3px;
        color: white;
        font-size: 12px;
        cursor: pointer;
        margin-left: 5px;
        height: 32px;
        line-height: 1;
    }

    .wx-btn:focus {
        outline: none;
    }

    .wx-btn.success {
        background: #1abc9c;
    }

    .wx-btn.success:hover {
        background: #2FB291;
    }

    .wx-btn.success:active {
        background: #16a085;
    }

    .wx-btn.danger {
        background: #e74c3c;
    }

    .wx-btn.danger:hover {
        background: #F45247;
    }

    .wx-btn.danger:active {
        background: #c0392b;
    }

    .wx-btn.info {
        background: #3498db;
    }

    .wx-btn.info:hover {
        background: #2B88D9;
    }

    .wx-btn.info:active {
        background: #2980b9;
    }

    .wx-btn[disabled=disabled] {
        background: #ddd;
        cursor: not-allowed;
    }

    .wx-btn[disabled=disabled]:hover {
        background: #ddd;
        cursor: not-allowed;
    }

    .wx-modal-bg {
        position: fixed;
        background: rgba(0, 0, 0, 0.8);
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 999990;
        font-size: 14px;
        font-family: "Microsoft Yahei";
        color: #555;
    }

    .wx-modal-box {
        position: inherit;
        width: 600px;
        height: 500px;
        background: white;
        overflow: hidden;
        left: 50%;
        top: 10%;
        margin-left: -300px;
        border-radius: 3px;
        z-index: 991;
    }

    .wx-modal-header {
        width: 100%;
        height: 50px;
        background: #eee;
        line-height: 50px;
        text-indent: 20px;
    }

    .wx-modal-section {
        width: 100%;
        height: 400px;
        overflow-y: scroll;
        position: relative;
    }

    .wx-modal-search {
        width: 600px;
        height: 30px;
        background: #ddd;
        position: fixed;
        z-index: 99;
    }

    .wx-modal-search input {
        width: 100%;
        height: 30px;
        font-size: 12px;
        color: #888;
        text-indent: 20px;
        font-weight: 400;
        padding: 0;
        border: 0;
        border-bottom: 1px solid #ddd;
    }

    .wx-modal-search input:focus {
        outline: none;
    }

    .wx-lists {
        padding: 30px 0;
    }

    .wx-lists li {
        float: left;
        list-style: none;
        margin: 10px 15px;
        width: 100px;
        height: 100px;
        cursor: pointer;
        position: relative;
    }

    .wx-progress {
        height: 3px;
        width: 0%;
        background: rgb(63, 210, 115);
        position: absolute;
    }

    #wx-picker {
        background-color: #eee;
    }

    .wx-img-wrap {
        display: table-cell;
        width: 100px;
        height: 100px;
        vertical-align: middle;
        text-align: center;
    }

    .wx-img-wrap img {
        max-width: 100px;
        max-height: 100px;
    }

    .wx-list.selected .wx-list-cover {
        position: absolute;
        width: 100px;
        height: 100px;
        background-color: rgba(0, 0, 0, 0.8);
        background-image: url(http://weazm-cdn.qiniudn.com/selected30.png);
        background-repeat: no-repeat;
        background-position: 50% 50%;
    }

    .wx-list.uploaded .wx-list-cover {
        position: absolute;
        width: 100px;
        height: 100px;
        background-color: rgba(0, 0, 0, 0.8);
        background-image: url(http://weazm-cdn.qiniudn.com/selected30.png);
        background-repeat: no-repeat;
        background-position: 50% 50%;
    }

    .wx-list-uploaded {
        position: absolute;
        width: 100px;
        height: 100px;
        text-align: center;
        line-height: 100px;
        color: white;
        background-color: rgba(0, 0, 0, 0.8);
    }

    .wx-list-delete {
        width: 16px;
        height: 16px;
        background-image: url(http://weazm-cdn.qiniudn.com/close.png);
        position: absolute;
        right: -5px;
        top: -5px;
        display: none;
    }

    .wx-list:hover .wx-list-delete {
        display: block;
    }

    .wx-list.uploaded:hover .wx-list-delete {
        display: none;
    }

    .wx-modal-footer {
        width: 100%;
        height: 50px;
        background: #fff;
        border-top: 1px solid #ddd;
        line-height: 50px;
    }

    .wx-modal-panel {
        margin-right: 20px;
    }

    input.webuploader-element-invisible {
        display: none;
    }

    .wx-dnd {
        position: absolute;
        background: #fff;
        border: 2px dashed #ddd;
        width: 80%;
        left: 10%;
        height: 150px;
        bottom: 20px;
        text-align: center;
        line-height: 150px;
        color: #ddd;
        z-index: 5;
    }

</style>
{!! HTML::script('js/webuploader/webuploader.html5only.min.js') !!}
<script type="x-template" id="vue-gallery-image">
    <li @click="select($index)" class="wx-list" v-bind:class=" {'selected': selected}" >
    <span class="wx-list-cover"></span>
    <div class="wx-img-wrap">
        <img :src="image.url + '?imageView2/2/w/100'" alt=""/>
    </div>
    </li>
</script>
<script type="x-template" id="vue-gallery">
    <div id="qiniu-container" v-if="!closed">
        <div class="wx-modal wx-gallery" v-bind:class="{'wx-hide': location == 'uploader'}">
            <div class="wx-modal-bg">
                <div class="wx-modal-box">
                    <header class="wx-modal-header">选择图片</header>
                    <section class="wx-modal-section">
                        <div class="wx-modal-search">
                            <input type="text" placeholder="搜索图片"/>
                        </div>
                        <div class="wx-lists">
                            <ul>
                                <vue-gallery-image v-for=" image in images" :image="image"></vue-gallery-image>
                            </ul>
                        </div>
                    </section>
                    <footer class="wx-modal-footer">
                        <div class="wx-modal-panel wx-fr">
                            <button class="wx-btn info" @click.prevent="switch('uploader')">本地上传</button>
                        </div>
                        <div class="wx-modal-panel wx-fr">
                            <button ng-disabled="!confirmAble" class="wx-btn success" @click.prevent="submit()">确定
                            </button>
                            <button class="wx-btn danger" @click.prevent="close()">取消</button>
                        </div>
                    </footer>
                </div>
            </div>
        </div>
        <div class="wx-modal wx-uploader" v-bind:class="{'wx-hide': location == 'gallery'}">
            <div class="wx-modal-bg">
                <div class="wx-modal-box">
                    <header class="wx-modal-header">上传图片</header>
                    <section class="wx-modal-section">
                        <span class="wx-progress"></span>
                        <div class="wx-lists">
                            <ul>

                                <li id="wx-picker">
                                    <input type="file" multiple v-on:change="fileChange">
                                    <span class="wx-list-cover"></span>
                                    <span class="wx-list-delete"></span>
                                    <img src="http://weazm-cdn.qiniudn.com/add.png" alt=""/>
                                </li>
                                <li class="wx-list upload-item" v-for="item in queues">
                                    <span class="wx-list-cover"></span>
                                    <span v-if="status == 'uploading' || status == 'stop'" class="wx-list-uploaded">[!item.percentage!]
                                        %</span>
                                    <span class="wx-list-delete" v-if="status == 'queue'"
                                          @click.prevent="removeQueue($index)"></span>
                                    <span class="wx-img-wrap"><img :src="item.src" alt=""/></span>
                                </li>
                            </ul>
                        </div>
                        {{--<div class="wx-dnd">拖拽到这里上传--}}
                        {{--</div>--}}
                    </section>
                    <footer class="wx-modal-footer">
                        <div class="wx-modal-panel wx-fr">
                            <button class="wx-btn info" ng-if="status == 'queue' || status == 'success'"
                                    @click.prevent="switch('gallery')">返回图库
                            </button>
                        </div>
                        <div class="wx-modal-panel wx-fr">
                            <button class="wx-btn success" v-if="status == 'queue' && files.length > 0"
                                    @click.prevent="startUpload()">开始上传
                            </button>
                            <button class="wx-btn danger" v-if="status == 'uploading'" @click.prevent="stopUpload()">
                                暂停上传
                            </button>
                            <button class="wx-btn success" v-if="status == 'stop'" @click.prevent="startUpload()">继续上传
                            </button>
                            <button class="wx-btn danger" v-if="status == 'queue'" @click.prevent="close()">取消</button>
                        </div>
                    </footer>
                </div>
            </div>
        </div>
    </div>
</script>
<script>
    Vue.config.delimiters = ["[!", "!]"];

    Vue.component('vue-gallery-image', {
        template: '#vue-gallery-image',
        props: ['image'],
        data: function () {
            return {
                selected: false
            }
        },
        methods: {
            select: function () {
                if (this.selected) {
                    this.selected = 0;
                    this.$dispatch('unselect', this.image)
                } else {
                    this.selected = 1;
                    this.$dispatch('selected', this.image)
                }
            }
        }
    });

    Vue.component('vue-gallery', {

        template: '#vue-gallery',
        components: ['vue-gallery-image'],
        data: function () {
            return {
                status: 'idle',
                closed: true,
                location: 'gallery',
                callbackFn: false,
                files: [],
                images: [],
                uploader: {},
                queues: [],
                selected: []
            }
        },

        created: function () {

            var self = this;

            this.getImages();

            this.uploader = WebUploader.create({

                formData: {
                    token: app.qiniu_token
                },

                // 文件接收服务端。
                server: 'http://upload.qiniu.com/',

                // 选择文件的按钮。可选。
                // 内部根据当前运行是创建，可能是input元素，也可能是flash.
//                pick: '#wx-picker',

                accept: {
                    title: 'Images',
                    extensions: 'gif,jpg,jpeg,bmp,png',
                    mimeTypes: 'image/*'
                },

                // 不压缩image, 默认如果是jpeg，文件上传前会压缩一把再上传！
                resize: false
            });

            this.uploader.on('fileQueued', function (file) {
                self.uploader.makeThumb(file, function (error, src) {
                    var item = {
                        id: file.id,
                        file: file,
                        src: src,
                        percentage: 0
                    }
                    self.files.push(item);
                    self.queues.push(item);
                    self.status = 'queue'
                }, 100, 100);
            });

            this.uploader.on('uploadProgress', function (file, percentage) {
                self.status = 'uploading'
                _.map(self.files, function (val, key) {
                    if (val.id == file.id) {
                        self.files[key]['percentage'] = (percentage * 100).toFixed(0);
                    }
                });
            });

            this.uploader.on('uploadError', function (file) {

            });

            this.uploader.on('uploadSuccess', function (file, res) {
                _.map(self.files, function (val, key) {
                    if (val.id == file.id) {
                        $(document.getElementsByClassName('upload-item')[key]).addClass('uploaded');
                    }
                })
                self.selected.push(res.data);
                if (self.selected.length == self.queues.length) {
                    self.status = 'success';
                    alert('上传完成!');
                }
            });

        },

        methods: {
            getImages: function () {
                var self = this;
                this.$http.get(app.config.api_url + '/admin/images', function (data) {
                    self.images = data.data
                }).error(function (data) {
                    console.error(data)
                });
            },
            fileChange: function (e) {
                this.uploader.addFiles(e.target.files);
            },
            reset: function () {
                this.uploader.stop()
                this.uploader.reset()
                this.selected = []
                this.files = []
                this.queues = []
                this.callbackFn = false
            },
            close: function () {
                this.reset()
                this.closed = true
            },
            submit: function () {
                var data = {
                    data: this.selected
                }
                if (this.callbackFn) {
                    data.method = this.callbackFn
                }
                this.$dispatch('gallerySubmit', data)
                this.close();
            },
            switch: function (location) {
                if (location == 'gallery') {
                    this.getImages()
                }
                this.reset()
                this.$set('location', location);
            },
            removeQueue: function (index) {
                this.queues.splice(index, 1)
            },
            startUpload: function () {
                this.uploader.upload()
            },
            stopUpload: function () {
                this.status = 'stop'
                this.uploader.stop(true)
            },
            resetUpload: function () {
                this.uploader.reset()
            }
        },
        events: {
            selected: function (image) {
                this.selected.push(image)
                this.$log('selected')
            },
            unselect: function (image) {
                this.selected.$remove(image)
                this.$log('selected')
            },
            galleryOpen: function (fn) {
                if (fn) {
                    this.callbackFn = fn
                }
                this.closed = false;
                this.getImages();
            }
        }
    });
</script>
