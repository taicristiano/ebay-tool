var numberItemInit = 0;
var isResize = false;

var fnInitFIlerImage = function (files) {
    numberItemInit = files.length + 1;
    $('input[name="files"]').fileuploader({
        extensions: ['jpg', 'jpeg', 'png', 'gif', 'bmp'],
        changeInput: ' ',
        theme: 'thumbnails',
        enableApi: true,
        addMore: true,
        files: files,
        thumbnails: {
            box: '<div class="fileuploader-items">' +
                      '<ul class="fileuploader-items-list">' +
                          '<li class="fileuploader-thumbnails-input" style="width:115px"><div class="fileuploader-thumbnails-input-inner">+</div></li>' +
                      '</ul>' +
                  '</div>',
            item: '<li class="fileuploader-item" style="width:115px">' +
                       '<div class="fileuploader-item-inner">' +
                           '<div class="thumbnail-holder">${image}</div>' +
                           '<div class="actions-holder">' +
                               '<a class="fileuploader-action fileuploader-action-remove" title="${captions.remove}"><i class="remove"></i></a>' +
                               '<span class="fileuploader-action-popup"></span>' +
                           '</div>' +
                           '<div class="progress-holder">${progressBar}</div>' +
                       '</div>' +
                   '</li>',
            item2: '<li class="fileuploader-item" style="width:115px">' +
                       '<div class="fileuploader-item-inner">' +
                           '<div class="thumbnail-holder">${image}</div>' +
                           '<div class="actions-holder">' +
                               '<a class="fileuploader-action fileuploader-action-remove" title="${captions.remove}"><i class="remove"></i></a>' +
                               '<span class="fileuploader-action-popup"></span>' +
                           '</div>' +
                       '</div>' +
                   '</li>',

            popup: null,
            startImageRenderer: true,
            canvasImage: false,
            _selectors: {
                list: '.fileuploader-items-list',
                item: '.fileuploader-item',
                start: '.fileuploader-action-start',
                retry: '.fileuploader-action-retry',
                remove: '.fileuploader-action-remove'
            },
            onItemShow: function(item, listEl, parentEl, newInputEl, inputEl) {
                var plusInput = listEl.find('.fileuploader-thumbnails-input'),
                    api = $.fileuploader.getInstance(inputEl.get(0));
                
                plusInput.insertAfter(item.html)[api.getOptions().limit && api.getChoosedFiles().length >= api.getOptions().limit ? 'hide' : 'show']();
                
                if(item.format == 'image') {
                    item.html.find('.fileuploader-item-icon').hide();
                }
                if (isResize) {
                    numberItemInit++;
                    resize();
                }
            }
        },
        afterRender: function(listEl, parentEl, newInputEl, inputEl) {
            var plusInput = listEl.find('.fileuploader-thumbnails-input'),
            api = $.fileuploader.getInstance(inputEl.get(0));
            plusInput.on('click', function() {
                api.open();
            });

            resize();
            isResize = true;
            
        },
        onRemove: function(item, listEl, parentEl, newInputEl, inputEl) {
            var plusInput = listEl.find('.fileuploader-thumbnails-input'),
                api = $.fileuploader.getInstance(inputEl.get(0));
        
            if (api.getOptions().limit && api.getChoosedFiles().length - 1 < api.getOptions().limit)
                plusInput.show();

            numberItemInit--;
            resize();
        },
    });
}

function resize() {
    var widthContent = $('.fileuploader-items-list').width();
    var numberItem = Math.floor(widthContent / 115);
    if (numberItem < numberItemInit) {
        var marginLeft = (widthContent - numberItem * 115) / 2;
        $('.fileuploader-items').css('margin-left', marginLeft + 'px');
    } else {
        $('.fileuploader-items').css('margin-left', '0px');
    }
}