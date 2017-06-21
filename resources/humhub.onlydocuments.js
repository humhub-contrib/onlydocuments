humhub.module('onlydocuments', function (module, require, $) {

    var client = require('client');
    var modal = require('ui.modal');
    var object = require('util').object;
    var Widget = require('ui.widget').Widget;
    var event = require('event');
    var loader = require('ui.loader');
    var ooJSLoadRetries = 0;

    var Editor = function (node, options) {
        Widget.call(this, node, options);
    };

    object.inherits(Editor, Widget);

    Editor.prototype.getDefaultOptions = function () {
        return {
            'fileName': 'unnamedFile.docx',
        };
    };

    Editor.prototype.init = function () {
        
        if (this.options.moduleConfigured != 1) {
            module.log.error('No OnlyOffice server configured! - Check OnlyDocuments module configuration!', true);
            return
        }
        
        this.initEditor();
        
        this.modal = modal.get('#onlydocuments-modal');
        
        var that = this;
        this.modal.$.on('hidden.bs.modal', function(evt) {
            that.modal.clear();
        });
        
    };


    Editor.prototype.share = function (evt) {
        m = modal.get('#onlydocuments-share-modal');
        m.load(evt.url);
        m.show();
    }


    Editor.prototype.close = function (evt) {
        var that = this;

        if (this.options.editMode == 'edit') {
            client.post({url: this.options.fileInfoUrl}).then(function (response) {
                event.trigger('humhub:file:modified', [response.file]);
                that.modal.clear();
                that.modal.close();
                evt.finish();
            }).catch(function (e) {
                module.log.error(e);
                that.modal.clear();
                that.modal.close();
                evt.finish();
            });
        } else {
            this.modal.clear();
            this.modal.close();
            evt.finish();
        }
        
        
    }

    Editor.prototype.initEditor = function () {
        if(!window.DocsAPI) {
            ooJSLoadRetries++;
            if(ooJSLoadRetries < 100) {
                setTimeout($.proxy(this.initEditor, this), 100);
                return;
            } else {
                module.log.error('Could not onlyoffice document editor.', true);
                return;
            }
        }
        
        this.docEditor = new DocsAPI.DocEditor('iframeContainer', {
            width: "100%",
            height: "100%",

            type: "desktop", // embedded 
            documentType: this.options.documentType,
            document: {
                title: this.options.fileName,
                url: this.options.backendDownloadUrl,
                fileType: this.options.fileExtension,
                key: this.options.fileKey,
                info: {
                    author: this.options.createdBy,
                    created: this.options.createdAt,
                },
                permissions: {
                    edit: (this.options.editMode == 'edit'),
                    download: true,
                }
            },
            editorConfig: {
                mode: this.options.editMode,
                lang: this.options.userLanguage,
                callbackUrl: this.options.backendTrackUrl,
                user: {
                    id: this.options.userGuid,
                    firstname: this.options.userFirstName,
                    lastname: this.options.userLastName,
                },

                embedded: {
                    toolbarDocked: "top",
                },

                customization: {
                    about: false,
                    feedback: false,
                    autosave: true,
                    forcesave: true,
                },
            },
            events: {
                //'onReady': onReady,
                //'onDocumentStateChange': onDocumentStateChange,
                //'onRequestEditRights': onRequestEditRights,
                //'onError': onError,
            }
        });
    }

    var Share = function (node, options) {
        Widget.call(this, node, options);
    };

    object.inherits(Share, Widget);

    Share.prototype.init = function () {

        var that = this;

        if ($('.editLinkInput').find('input').val() != '') {
            $('.editLinkCheckbox').prop('checked', true);
            $(".editLinkCheckbox").attr('checked', true);
        } else {
            $('.editLinkInput').hide();
        }

        if ($('.viewLinkInput').find('input').val() != '') {
            $('.viewLinkCheckbox').attr('checked', true);
        } else {
            $('.viewLinkCheckbox').attr('checked', false);
            $('.viewLinkInput').hide();
        }

        $('.viewLinkCheckbox').change(function () {
            if ($('.viewLinkCheckbox:checked').length) {
                loader.set(that.$.find('.modal-footer'));
                $.ajax({
                    url: that.options.shareGetLink,
                    cache: false,
                    type: 'POST',
                    data: {'shareMode': 'view'},
                    dataType: 'json',
                    success: function (json) {
                        $('.viewLinkInput').show();
                        $('.viewLinkInput').find('input').val(json.url)
                        loader.reset(that.$.find('.modal-footer'));
                    }
                });
            } else {
                loader.set(that.$.find('.modal-footer'));
                $.ajax({
                    url: that.options.shareRemoveLink,
                    cache: false,
                    type: 'POST',
                    data: {'shareMode': 'view'},
                    dataType: 'json',
                    success: function (jsoin) {
                        $('.viewLinkInput').hide();
                        loader.reset(that.$.find('.modal-footer'));
                    }
                });
            }
        });

        $('.editLinkCheckbox').change(function () {
            if ($('.editLinkCheckbox:checked').length) {
                loader.set(that.$.find('.modal-footer'));
                $.ajax({
                    url: that.options.shareGetLink,
                    cache: false,
                    type: 'POST',
                    data: {'shareMode': 'edit'},
                    dataType: 'json',
                    success: function (json) {
                        $('.editLinkInput').show();
                        $('.editLinkInput').find('input').val(json.url)
                        loader.reset(that.$.find('.modal-footer'));
                    }
                });
            } else {
                loader.set(that.$.find('.modal-footer'));
                $.ajax({
                    url: that.options.shareRemoveLink,
                    cache: false,
                    type: 'POST',
                    data: {'shareMode': 'edit'},
                    dataType: 'json',
                    success: function (jsoin) {
                        $('.editLinkInput').hide();
                        loader.reset(that.$.find('.modal-footer'));
                    }
                });
            }
        });

    };

    Share.prototype.getDefaultOptions = function () {
        return {};
    };

    Share.prototype.clickv = function (evt) {
        var that = this;
        //evt.$trigger
    }



    var init = function (pjax) {};

    var createSubmit = function (evt) {
        client.submit(evt).then(function (response) {
            event.trigger('humhub:file:created', [response.file]);

            m = modal.get('#onlydocuments-modal');
            if (response.openFlag) {
                m.load(response.openUrl);
                m.show();
            } else {
                m.close();
            }

        }).catch(function (e) {
            module.log.error(e, true);
        });
    };

    module.export({
        init: init,
        createSubmit: createSubmit,
        Editor: Editor,
        Share: Share,
    });

});