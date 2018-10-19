// +----------------------------------------------------------------------
// | framework
// +----------------------------------------------------------------------
// | 版权所有 2014~2017 广州楚才信息科技有限公司 [ http://www.cuci.cc ]
// +----------------------------------------------------------------------
// | 官方网站: http://think.ctolog.com
// +----------------------------------------------------------------------
// | 开源协议 ( https://mit-license.org )
// +----------------------------------------------------------------------
// | github开源项目：https://github.com/zoujingli/framework
// +----------------------------------------------------------------------

// 当前资源URL目录
let baseRoot = (function () {
    let scripts = document.scripts, src = scripts[scripts.length - 1].src;
    return src.substring(0, src.lastIndexOf("/") + 1);
})();

var form = layui.form, layer = layui.layer, laydate = layui.laydate;
if (typeof jQuery === 'undefined') var $ = jQuery = layui.$;

// require 配置参数
require.config({
    waitSeconds: 60,
    baseUrl: baseRoot,
    map: {'*': {css: baseRoot + 'plugs/require/css.js'}},
    paths: {
        'json': ['plugs/jquery/json2.min'],
        'upload': ['plugs/plupload/build'],
        'base64': ['plugs/jquery/base64.min'],
        'angular': ['plugs/angular/angular.min'],
        'ckeditor': ['plugs/ckeditor/ckeditor'],
        'websocket': ['plugs/socket/websocket'],
        'pcasunzips': ['plugs/jquery/pcasunzips'],
        'plupload': ['plugs/plupload/plupload.min'],
        'jquery.ztree': ['plugs/ztree/ztree.all.min'],
        'jquery.masonry': ['plugs/jquery/masonry.min'],
        'jquery.autocompleter': ['plugs/jquery/autocompleter.min'],
    },
    shim: {
        'plupload': {deps: [baseRoot + 'plugs/plupload/moxie.min.js']},
        'websocket': {deps: [baseRoot + 'plugs/socket/swfobject.min.js']},
        'jquery.ztree': {deps: ['jquery', 'css!' + baseRoot + 'plugs/ztree/zTreeStyle/zTreeStyle.css']},
        'jquery.autocompleter': {deps: ['jquery', 'css!' + baseRoot + 'plugs/jquery/autocompleter.css']},
    }
});

// 注册jquery到require模块
define('jquery', [], function () {
    return layui.$;
});

$(function () {
    let $body = $('body');
    /*! 消息组件实例 */
    $.msg = new function () {
        let self = this;
        this.shade = [0.02, '#000'];
        this.dialogIndexs = [];
        // 关闭消息框
        this.close = function (index) {
            return layer.close(index);
        };
        // 弹出警告消息框
        this.alert = function (msg, callback) {
            let index = layer.alert(msg, {end: callback, scrollbar: false});
            return this.dialogIndexs.push(index), index;
        };
        // 确认对话框
        this.confirm = function (msg, ok, no) {
            let index = layer.confirm(msg, {title: '操作确认', btn: ['确认', '取消']}, function () {
                typeof ok === 'function' && ok.call(this, index);
            }, function () {
                typeof no === 'function' && no.call(this, index);
                self.close(index);
            });
            return index;
        };
        // 显示成功类型的消息
        this.success = function (msg, time, callback) {
            let index = layer.msg(msg, {icon: 1, shade: this.shade, scrollbar: false, end: callback, time: (time || 2) * 1000, shadeClose: true});
            return this.dialogIndexs.push(index), index;
        };
        // 显示失败类型的消息
        this.error = function (msg, time, callback) {
            let index = layer.msg(msg, {icon: 2, shade: this.shade, scrollbar: false, time: (time || 3) * 1000, end: callback, shadeClose: true});
            return this.dialogIndexs.push(index), index;
        };
        // 状态消息提示
        this.tips = function (msg, time, callback) {
            let index = layer.msg(msg, {time: (time || 3) * 1000, shade: this.shade, end: callback, shadeClose: true});
            return this.dialogIndexs.push(index), index;
        };
        // 显示正在加载中的提示
        this.loading = function (msg, callback) {
            let index = msg ? layer.msg(msg, {icon: 16, scrollbar: false, shade: this.shade, time: 0, end: callback}) : layer.load(2, {time: 0, scrollbar: false, shade: this.shade, end: callback});
            return this.dialogIndexs.push(index), index;
        };
        // 自动处理显示Think返回的Json数据
        this.auto = function (ret, time) {
            let url = ret.url || (typeof ret.data === 'string' ? ret.data : '');
            let msg = ret.msg || (typeof ret.info === 'string' ? ret.info : '');
            return (parseInt(ret.code) === 1) ? this.success(msg, time, function () {
                url ? (window.location.href = url) : $.form.reload();
                for (let i in self.dialogIndexs) layer.close(self.dialogIndexs[i]);
                self.dialogIndexs = [];
            }) : this.error(msg, 3, function () {
                url ? window.location.href = url : '';
            });
        };
    };

    /*! 表单自动化组件 */
    $.form = new function () {
        let self = this;
        // 内容区选择器
        this.targetClass = '.layui-layout-admin>.layui-body';
        // 刷新当前页面
        this.reload = function () {
            window.onhashchange.call(this);
        };
        // 内容区域动态加载后初始化
        this.reInit = function ($dom) {
            $.vali.listen(this);
            $dom = $dom || $(this.targetClass);
            $dom.find('[required]').parent().prevAll('label').addClass('label-required');
            $dom.find('[data-file="btn"]').map(function () {
                let self = this;
                require(['upload'], function (r) {
                    r(self)
                });
            });
        };
        // 在内容区显示视图
        this.show = function (html) {
            $(this.targetClass).html(html);
            this.reInit(), setTimeout(this.reInit, 500), setTimeout(this.reInit, 1000);
        };
        // 以hash打开网页
        this.href = function (url, obj) {
            if (url !== '#') window.location.href = '#' + $.menu.parseUri(url, obj);
            else if (obj && obj.getAttribute('data-menu-node'))
                $('[data-menu-node^="' + obj.getAttribute('data-menu-node') + '-"][data-open!="#"]:first').trigger('click');
        };
        // 异步加载的数据
        this.load = function (url, data, type, callback, loading, tips, time) {
            let index = loading !== false ? $.msg.loading(tips) : 0;
            $.ajax({
                type: type || 'GET', url: $.menu.parseUri(url), data: data || {}, beforeSend: function () {
                    typeof Pace === 'object' && Pace.restart();
                }, error: function (XMLHttpRequest) {
                    if (parseInt(XMLHttpRequest.status) === 200) this.success(XMLHttpRequest.responseText);
                    else $.msg.tips('E' + XMLHttpRequest.status + ' - 服务器繁忙，请稍候再试！');
                }, success: function (res) {
                    if (typeof callback === 'function' && callback.call(self, res) === false) return false;
                    return typeof res === 'object' ? $.msg.auto(res, time || res.wait || undefined) : self.show(res);
                }, complete: function () {
                    $.msg.close(index);
                }
            });
        };
        // 加载HTML到目标位置
        this.open = function (url, data, callback, loading, tips) {
            this.load(url, data, 'get', function (ret) {
                return typeof ret === 'object' ? $.msg.auto(ret) : self.show(ret);
            }, loading, tips);
        };
        // 打开一个iframe窗口
        this.iframe = function (url, title) {
            return layer.open({title: title || '窗口', type: 2, area: ['800px', '550px'], fix: true, maxmin: false, content: url});
        };
        // 加载HTML到弹出层
        this.modal = function (url, data, title, callback, loading, tips) {
            this.load(url, data, 'GET', function (res) {
                if (typeof (res) === 'object') return $.msg.auto(res);
                let index = layer.open({
                    type: 1, btn: false, area: "800px", content: res, title: title || '', success: function (dom, index) {
                        $(dom).find('[data-close]').off('click').on('click', function () {
                            if ($(this).attr('data-confirm')) {
                                let confirmIndex = $.msg.confirm($(this).attr('data-confirm'), function () {
                                    layer.close(confirmIndex), layer.close(index);
                                });
                                return false;
                            }
                            layer.close(index);
                        });
                        $.form.reInit($(dom));
                    }
                });
                $.msg.dialogIndexs.push(index);
                return (typeof callback === 'function') && callback.call(this);
            }, loading, tips);
        };
    };

    /*! 后台菜单辅助插件 */
    $.menu = new function () {
        let self = this;
        // 计算URL地址中有效的URI
        this.getUri = function (uri) {
            uri = uri || window.location.href;
            uri = (uri.indexOf(window.location.host) > -1 ? uri.split(window.location.host)[1] : uri).split('?')[0];
            return (uri.indexOf('#') !== -1 ? uri.split('#')[1] : uri);
        };
        // 通过URI查询最有可能的菜单NODE
        this.queryNode = function (url) {
            let node = location.href.replace(/.*spm=([\d\-m]+).*/ig, '$1');
            if (!/^m-/.test(node)) {
                let $menu = $('[data-menu-node][data-open*="' + url.replace(/\.html$/ig, '') + '"]');
                return $menu.size() ? $menu.get(0).getAttribute('data-menu-node') : '';
            }
            return node;
        };
        // URL转URI
        this.parseUri = function (uri, obj) {
            let params = {};
            uri.indexOf('?') > -1 && uri.split('?')[1].split('&').map(function (item) {
                if (item.indexOf('=') > -1) {
                    let tmp = item.split('=').slice();
                    params[tmp[0]] = decodeURI(tmp[1].replace(/%2B/ig, '%20'));
                }
            });
            delete params[""];
            uri = this.getUri(uri);
            params.spm = obj && obj.getAttribute('data-menu-node') || this.queryNode(uri);
            params.spm === '' && delete params.spm;
            let query = '?' + $.param(params);
            return uri + (query === '?' ? '' : query);
        };
        // 后台菜单动作初始化
        this.listen = function () {
            // 菜单模式切换
            (function ($menu, miniClass) {
                // Mini 菜单模式切换及显示
                (layui.data('menu')['type-min']) && $menu.addClass(miniClass);
                $body.on('click', '[data-target-menu-type]', function () {
                    $menu.toggleClass(miniClass);
                    layui.data('menu', {key: 'type-min', value: $menu.hasClass(miniClass)});
                });
                //  Mini 菜单模式时TIPS文字显示
                $('[data-target-tips]').mouseenter(function () {
                    if ($menu.hasClass(miniClass)) $(this).attr('index', layer.tips($(this).attr('data-target-tips') || '', this));
                }).mouseleave(function () {
                    layer.close($(this).attr('index'));
                });
            })($('.layui-layout-admin'), 'layui-layout-left-mini');
            // 左则二级菜单展示
            $('[data-submenu-layout]>a').on('click', function () {
                self.syncOpenStatus(1);
            });
            // 同步二级菜单展示状态
            this.syncOpenStatus = function (mode) {
                $('[data-submenu-layout]').map(function () {
                    let node = $(this).attr('data-submenu-layout');
                    if (mode === 1) layui.data('menu', {key: node, value: $(this).hasClass('layui-nav-itemed') ? 2 : 1});
                    else if ((layui.data('menu')[node] || 2) === 2) $(this).addClass('layui-nav-itemed');
                });
            };
            window.onhashchange = function () {
                let hash = window.location.hash || '';
                if (hash.length < 1) return $('[data-menu-node][data-open!="#"]:first').trigger('click');
                $.form.load(hash), self.syncOpenStatus(2);
                // 菜单选择切换
                let node = self.queryNode(self.getUri());
                if (/^m-/.test(node)) {
                    let $all = $('a[data-menu-node]').parent(), tmp = node.split('-'), tmpNode = tmp.shift();
                    while (tmp.length > 0) {
                        tmpNode = tmpNode + '-' + tmp.shift();
                        $all = $all.not($('a[data-menu-node="' + tmpNode + '"]').parent().addClass('layui-this'));
                    }
                    $all.removeClass('layui-this');
                    // 菜单模式切换
                    if (node.split('-').length > 2) {
                        let _tmp = node.split('-'), _node = _tmp.shift() + '-' + _tmp.shift();
                        $('[data-menu-layout]').not($('[data-menu-layout="' + _node + '"]').removeClass('layui-hide')).addClass('layui-hide');
                        $('[data-menu-node="' + node + '"]').parent().parent().parent().addClass('layui-nav-itemed');
                        $('.layui-layout-admin').removeClass('layui-layout-left-hide');
                    } else $('.layui-layout-admin').addClass('layui-layout-left-hide');
                    self.syncOpenStatus(1);
                }
            };
            // URI初始化动作
            window.onhashchange.call(this);
        };
    };

    /*! 注册对象到Jq */
    $.vali = function (form, callback, options) {
        return (new function () {
            let self = this;
            // 表单元素
            this.tags = 'input,textarea,select';
            // 检测元素事件
            this.checkEvent = {change: true, blur: true, keyup: false};
            // 去除字符串两头的空格
            this.trim = function (str) {
                return str.replace(/(^\s*)|(\s*$)/g, '');
            };
            // 标签元素是否可见
            this.isVisible = function (ele) {
                return $(ele).is(':visible');
            };
            // 检测属性是否有定义
            this.hasProp = function (ele, prop) {
                if (typeof prop !== "string") return false;
                let attrProp = ele.getAttribute(prop);
                return (typeof attrProp !== 'undefined' && attrProp !== null && attrProp !== false);
            };
            // 判断表单元素是否为空
            this.isEmpty = function (ele, value) {
                let trimValue = this.trim(ele.value);
                value = value || ele.getAttribute('placeholder');
                return (trimValue === "" || trimValue === value);
            };
            // 正则验证表单元素
            this.isRegex = function (ele, regex, params) {
                let inputValue = ele.value, dealValue = this.trim(inputValue);
                regex = regex || ele.getAttribute('pattern');
                if (dealValue === "" || !regex) return true;
                if (dealValue !== inputValue) (ele.tagName.toLowerCase() !== "textarea") ? (ele.value = dealValue) : (ele.innerHTML = dealValue);
                return new RegExp(regex, params || 'i').test(dealValue);
            };
            // 检侧所的表单元素
            this.isAllpass = function (elements, options) {
                if (!elements) return true;
                let allpass = true, self = this, params = options || {};
                if (elements.size && elements.size() === 1 && elements.get(0).tagName.toLowerCase() === "form") elements = $(elements).find(self.tags);
                else if (elements.tagName && elements.tagName.toLowerCase() === "form") elements = $(elements).find(self.tags);
                elements.each(function () {
                    if (self.checkInput(this, params) === false) return $(this).focus(), (allpass = false);
                });
                return allpass;
            };
            // 验证标志
            this.remind = function (input) {
                return this.isVisible(input) ? this.showError(input, input.getAttribute('title') || input.getAttribute('placeholder') || '输入错误') : false;
            };
            // 检测表单单元
            this.checkInput = function (input) {
                let type = (input.getAttribute("type") + "").replace(/\W+$/, "").toLowerCase();
                let tag = input.tagName.toLowerCase(), isRequired = this.hasProp(input, "required");
                if (this.hasProp(input, 'data-auto-none') || input.disabled || type === 'submit' || type === 'reset' || type === 'file' || type === 'image' || !this.isVisible(input)) return;
                let allpass = true;
                if (type === "radio" && isRequired) {
                    let radiopass = false, eleRadios = input.name ? $("input[type='radio'][name='" + input.name + "']") : $(input);
                    eleRadios.each(function () {
                        (radiopass === false && $(this).is("[checked]")) && (radiopass = true);
                    });
                    if (radiopass === false) allpass = this.remind(eleRadios.get(0), type, tag); else this.hideError(input);
                } else if (type === "checkbox" && isRequired && !$(input).is("[checked]")) allpass = this.remind(input, type, tag);
                else if (tag === "select" && isRequired && !input.value) allpass = this.remind(input, type, tag);
                else if ((isRequired && this.isEmpty(input)) || !(allpass = this.isRegex(input))) (allpass ? this.remind(input, type, "empty") : this.remind(input, type, tag)), allpass = false;
                else this.hideError(input);
                return allpass;
            };
            // 错误消息显示
            this.showError = function (ele, content) {
                $(ele).addClass('validate-error'), this.insertError(ele);
                $($(ele).data('input-info')).addClass('layui-anim layui-anim-scale').css({width: 'auto'}).html(content);
            };
            // 错误消息消除
            this.hideError = function (ele) {
                $(ele).removeClass('validate-error'), this.insertError(ele);
                $($(ele).data('input-info')).removeClass('layui-anim-scale').css({width: '30px'}).html('');
            };
            // 错误消息标签插入
            this.insertError = function (ele) {
                let $html = $('<span style="padding-right:12px;color:#a94442;position:absolute;right:0;font-size:12px;z-index:2;display:block;width:34px;text-align:center;pointer-events:none"></span>');
                $html.css({top: $(ele).position().top + 'px', paddingBottom: $(ele).css('paddingBottom'), lineHeight: $(ele).css('height')});
                $(ele).data('input-info') || $(ele).data('input-info', $html.insertAfter(ele));
            };
            // 表单验证入口
            this.check = function (form, callback, options) {
                let params = $.extend({}, options || {});
                $(form).attr("novalidate", "novalidate");
                $(form).find(self.tags).map(function () {
                    for (let i in self.checkEvent) if (self.checkEvent[i] === true) $(this).off(i, func).on(i, func);

                    function func() {
                        self.checkInput(this);
                    }
                });
                $(form).bind("submit", function (event) {
                    if (self.isAllpass($(this).find(self.tags), params) && typeof callback === 'function') {
                        if (typeof CKEDITOR === 'object' && typeof CKEDITOR.instances === 'object')
                            for (let instance in CKEDITOR.instances) CKEDITOR.instances[instance].updateElement();
                        callback.call(this, $(form).serialize());
                    }
                    return event.preventDefault(), false;
                });
                return $(form).data('validate', this);
            };
        }).check(form, callback, options);
    };

    /*! 自动监听规则内表单 */
    $.vali.listen = function () {
        $('form[data-auto]').map(function () {
            if ($(this).attr('data-listen') !== 'true') {
                let callbackname = $(this).attr('data-callback');
                $(this).attr('data-listen', 'true').vali(function (data) {
                    let method = this.getAttribute('method') || 'POST';
                    let tips = this.getAttribute('data-tips') || undefined;
                    let time = this.getAttribute('data-time') || undefined;
                    let url = this.getAttribute('action') || window.location.href;
                    let callback = window[callbackname || '_default_callback'] || undefined;
                    $.form.load(url, data, method, callback, true, tips, time);
                });
                $(this).find('[data-form-loaded]').map(function () {
                    $(this).html(this.getAttribute('data-form-loaded') || this.innerHTML);
                    $(this).removeAttr('data-form-loaded').removeClass('layui-disabled');
                });
            }
        });
    };

    /*! 注册对象到JqFn */
    $.fn.vali = function (callback, options) {
        return $.vali(this, callback, options);
    };

    /*! 上传单个图片 */
    $.fn.uploadOneImage = function () {
        let name = $(this).attr('name') || 'image';
        let type = $(this).data('type') || 'png,jpg';
        let $tpl = $('<a data-file="one" data-field="' + name + '" data-type="' + type + '" class="uploadimage"></a>');
        $(this).attr('name', name).after($tpl).on('change', function () {
            !!this.value && $tpl.css('backgroundImage', 'url(' + this.value + ')');
        }).trigger('change');
    };

    /*! 上传多个图片 */
    $.fn.uploadMultipleImage = function () {
        let type = $(this).data('type') || 'png,jpg';
        let name = $(this).attr('name') || 'umt-image';
        let $tpl = $('<a data-file="mut" data-field="' + name + '" data-type="' + type + '" class="uploadimage"></a>');
        $(this).attr('name', name).after($tpl).on('change', function () {
            let input = this, values = [], srcs = this.value.split('|');
            $(this).prevAll('.uploadimage').map(function () {
                values.push($(this).attr('data-tips-image'));
            }), $(this).prevAll('.uploadimage').remove(), values.reverse();
            for (let i in srcs) srcs[i] && values.push(srcs[i]);
            this.value = values.join('|');
            for (let i in values) {
                let tpl = '<div class="uploadimage uploadimagemtl"><a class="layui-icon">&#x1006;</a></div>';
                let $tpl = $(tpl).attr('data-tips-image', values[i]).css('backgroundImage', 'url(' + values[i] + ')');
                $tpl.data('input', input).data('srcs', values).data('index', i);
                $tpl.on('click', 'a', function (e) {
                    e.stopPropagation();
                    let $cur = $(this).parent();
                    let dialogIndex = $.msg.confirm('确定要移除这张图片吗？', function () {
                        let data = $cur.data('srcs'), tmp = [];
                        for (let i in data) i !== $cur.data('index') && tmp.push(data[i]);
                        $cur.data('input').value = tmp.join('|');
                        $cur.remove(), $.msg.close(dialogIndex);
                    });
                });
                $(this).before($tpl);
            }
        }).trigger('change');
    };

    /*! 注册 data-load 事件行为 */
    $body.on('click', '[data-load]', function () {
        let url = $(this).attr('data-load'), tips = $(this).attr('data-tips');
        if ($(this).attr('data-confirm')) return $.msg.confirm($(this).attr('data-confirm'), function () {
            $.form.load(url, {}, 'get', null, true, tips);
        });
        $.form.load(url, {}, 'get', null, true, tips);
    });

    /*! 注册 data-serach 表单搜索行为 */
    $body.on('submit', 'form.form-search', function () {
        let url = $(this).attr('action').replace(/\&?page\=\d+/g, ''), split = url.indexOf('?') === -1 ? '?' : '&';
        if ((this.method || 'get').toLowerCase() === 'get') {
            return window.location.href = '#' + $.menu.parseUri(url + split + $(this).serialize());
        }
        $.form.load(url, this, 'post');
    });

    /*! 注册 data-modal 事件行为 */
    $body.on('click', '[data-modal]', function () {
        return $.form.modal($(this).attr('data-modal'), 'open_type=modal', $(this).attr('data-title') || $(this).text() || '编辑');
    });

    /*! 注册 data-open 事件行为 */
    $body.on('click', '[data-open]', function () {
        $.form.href($(this).attr('data-open'), this);
    });

    /*! 注册 data-reload 事件行为 */
    $body.on('click', '[data-reload]', function () {
        $.form.reload();
    });

    /*! 注册 data-check 事件行为 */
    $body.on('click', '[data-check-target]', function () {
        let checked = !!this.checked;
        $($(this).attr('data-check-target')).map(function () {
            this.checked = checked;
        });
    });

    /*! 注册 data-action 事件行为 */
    $body.on('click', '[data-action]', function () {
        let data = {}, $this = $(this), action = $this.attr('data-action');
        let rule = $this.attr('data-value') || (function (rule, ids) {
            $($this.attr('data-target') || 'input[type=checkbox].list-check-box').map(function () {
                (this.checked) && ids.push(this.value);
            });
            return ids.length > 0 ? rule.replace('{key}', ids.join(',')) : '';
        }).call(this, $this.attr('data-rule') || '', []) || '';
        if (rule.length < 1) return $.msg.tips('请选择需要更改的数据！');
        for (let o of rule.split(';')) {
            if (o.length < 2) return $.msg.tips('异常的数据操作规则，请修改规则！');
            data[o.split('#')[0]] = o.split('#')[1];
        }
        $.msg.confirm($this.attr('data-confirm') || '确定要更改数据状态吗？', function () {
            $.form.load(action, data, 'post');
        });
    });

    /*! 注册 data-href 事件行为 */
    $body.on('click', '[data-href]', function () {
        let href = $(this).attr('data-href');
        (href && href.indexOf('#') !== 0) && (window.location.href = href);
    });

    /*! 注册 data-file 事件行为 */
    $body.on('click', '[data-file]', function () {
        let mode = $(this).attr('data-file') || 'one';
        let name = $(this).attr('data-name') || 'file';
        let field = $(this).attr('data-field') || 'file';
        let type = $(this).attr('data-type') || 'jpg,png';
        if (mode !== 'btn') {
            let uptype = $(this).attr('data-uptype') || '';
            let param = $.param({name: name, mode: mode, uptype: uptype, type: type, field: field});
            let url = window.ROOT_URL + '?s=admin/plugs/upfile.html&' + param;
            $.form.iframe(url, $(this).attr('data-title') || '文件上传');
        }
    });

    /*! 注册 data-iframe 事件行为 */
    $body.on('click', '[data-iframe]', function () {
        $.form.iframe($(this).attr('data-iframe'), $(this).attr('data-title') || '窗口');
    });

    /*! 注册 data-icon 事件行为 */
    $body.on('click', '[data-icon]', function () {
        let field = $(this).attr('data-icon') || $(this).attr('data-field') || 'icon';
        let url = window.ROOT_URL + '?s=admin/plugs/icon.html&field=' + field;
        $.form.iframe(url, '图标选择');
    });

    /*! 注册 data-tips-image 事件行为 */
    $body.on('click', '[data-tips-image]', function () {
        let img = new Image(), index = $.msg.loading();
        let width = this.getAttribute('data-width') || '480px';
        img.onerror = function () {
            $.msg.close(index);
        };
        img.onload = function () {
            let $content = $(img).appendTo('body').css({background: '#fff', width: width, height: 'auto'});
            layer.open({
                type: 1, area: width, title: false, closeBtn: 1,
                skin: 'layui-layer-nobg', shadeClose: true, content: $content,
                end: function () {
                    $(img).remove();
                },
                success: function () {
                    $.msg.close(index);
                }
            });
        };
        img.src = this.getAttribute('data-tips-image') || this.src;
    });

    /*! 注册 data-tips-text 事件行为 */
    $body.on('mouseenter', '[data-tips-text]', function () {
        let text = $(this).attr('data-tips-text'), type = $(this).attr('data-tips-type');
        $(this).attr('index', layer.tips(text, this, {tips: [type || 3, '#78BA32']}));
    }).on('mouseleave', '[data-tips-text]', function () {
        layer.close($(this).attr('index'));
    });

    /*! 注册 data-phone-view 事件行为 */
    $body.on('click', '[data-phone-view]', function () {
        let $container = $('<div class="mobile-preview pull-left"><div class="mobile-header">公众号</div><div class="mobile-body"><iframe id="phone-preview" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe></div></div>').appendTo('body');
        $container.find('iframe').attr('src', this.getAttribute('data-phone-view') || this.href);
        layer.style(layer.open({
            type: true, scrollbar: false, area: ['335px', '600px'], title: false,
            closeBtn: true, skin: 'layui-layer-nobg', shadeClose: false, content: $container,
            end: function () {
                $container.remove();
            }
        }), {boxShadow: 'none'});
    });

    /*! 初始化 */
    $.menu.listen();
    $.vali.listen();
});