/*------------------------ 
Backend related javascript
------------------------*/

window.addEventListener('load', (event) => {

    // FUNCTION TO BIND AJAX CREATED ELEMENTS
    const on = (element, event, selector, handler) => {
        element.addEventListener(event, e => {
            if (e.target.closest(selector)) {
                handler(e);
            }
        });
    }

    function instanceMediaUploader (multiple) {
        var media_uploader = wp.media({
            frame:    "post",
            state:    "insert",
            multiple: multiple
        });
        return media_uploader;
    }

    const removeImageToCGPT = (selector, e) => {
        galleryBox = e.target.closest(selector).parentNode;
        galleryBox.remove();
    }

    const addImageToCGPT = (e) => {
        var imgElement = e;
        var tagName = imgElement.srcElement.nodeName;
        var imgParent = e.parentNode;
     
        if(tagName === "IMG") {
            wp_media_uploader = instanceMediaUploader(false);
            wp_media_uploader.on("insert", function(){
                var imageResponse = wp_media_uploader.state().get("selection").first().toJSON();
                var image_url = imageResponse.url;
                var sibling = imgElement.target.nextElementSibling;
                imgElement.target.src=image_url;
                sibling.value = image_url;                
            });

        } else {
            wp_media_uploader = instanceMediaUploader(true);
            
            wp_media_uploader.on("insert", function(){

                var length = wp_media_uploader.state().get("selection").length;
                var images = wp_media_uploader.state().get("selection").models

                
                for(var i = 0; i < length; i++){
                    var image_url = images[i].changed.url;
                    var image_id = images[i].id;
                    var img_box_container = document.querySelector("#img_box_container");
                    var addImageBtn = document.querySelector("#add_gallery_single_row");

                    let gallery_single_row = document.createElement('div');
                    gallery_single_row.classList.add('gallery_single_row');
                    
                    let image_container = document.createElement('div');
                    image_container.classList.add('image_container');
                    
                    let input_url = document.createElement('input');
                    input_url.type = 'hidden';
                    input_url.className = 'meta_image_url';
                    input_url.name = 'gallery[image_url][]';

                    let input_id = document.createElement('input');
                    input_id.type = 'hidden';
                    input_id.className = 'meta_image_id';
                    input_id.name = 'gallery[image_id][]';

                    
                    image_container.insertBefore(input_url, null);
                    image_container.insertBefore(input_id, input_url);
                    
                    gallery_single_row.insertBefore(image_container, null);

                    let btn_remove = document.createElement('span');
                    btn_remove.classList.add('button', 'cgpt-remove'); //check how to add another class
                    btn_remove.title = "Remove";

                    let icon_trash = document.createElement('i');
                    icon_trash.classList.add('fas', 'fa-trash-alt');
                    
                    btn_remove.insertBefore(icon_trash, null);

                    gallery_single_row.insertBefore(btn_remove, null);
                    
 
                    img_box_container.insertBefore(gallery_single_row, addImageBtn);
                    var element = document.querySelector("#img_box_container .gallery_single_row:nth-last-child(2) .image_container");
                    const imgEL = document.createElement("img");
                    imgEL.classList.add("gallery_img_img");
                    imgEL.src = image_url;
                    element.prepend(imgEL);
                    var meta_image_url = element.querySelector(".meta_image_url");
                    var meta_image_id = element.querySelector(".meta_image_id");
                    meta_image_url.value = image_url;
                    meta_image_id.value = image_id;
                }
            });
        }   

        wp_media_uploader.open();
    }


    var addImgBtn = document.querySelector(".cgpt-add-image");
    if(addImgBtn){
        addImgBtn.addEventListener("click", (e) => { addImageToCGPT(e) }, false);
    }

    // REPLACE IMAGE 
    on(document, 'click', '.gallery_img_img', e => {
        addImageToCGPT(e)
    });

    // REMOVE IMAGE BUTTONS
    on(document, 'click', '.cgpt-remove', e => {
        removeImageToCGPT('.cgpt-remove', e)
    });


    (()=> {enableDragSort('drag-sort-enable')})();    

    function serializeArray (array, name) {
        var serialized = '';
        for(var i = 0, j = array.length; i < j; i++) {
            if(i>0) serialized += '&';
            serialized += name + '=' + array[i];
        }
        return serialized;
    }

    const saveData = (e) => {

        const request = new XMLHttpRequest();
        let postTypeValue = document.querySelector('.post-type-dropdown');
        var msgContainer = document.querySelector('.msg');
        var post_types_array = [];
        var post_types_checkboxes = document.querySelectorAll('input[type=checkbox]:checked');

        for (var i = 0; i < post_types_checkboxes.length; i++) {
          post_types_array.push(post_types_checkboxes[i].value)
        }
        var data = serializeArray(post_types_array, 'post_type_value[]');
        
        request.open('POST',php_vars.ajax_url, true);
        request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
        request.onload = function () {
            if (this.status >= 200 && this.status < 400) {
                msgContainer.classList.add('success');
                msgContainer.innerHTML = "Post Type Saved !";  
            } else {
                msgContainer.classList.add('error');
                msgContainer.innerHTML = "There was an error saving the Post Type" ; 
            }
        };
        request.onerror = function() {
            // Connection error
        }
        request.send('action=save_post_types&' + data);
    }

    on(document, 'click', '#post-type-submit', e => {
        domain = e.target.id;
        saveData()
    });

    /* DRAG EVENTS */
    var dragged;

    /* events fired on the draggable target */
    document.addEventListener("dragstart", function( event ) {
        dragged = event.target;
        event.target.style.opacity = .6;
        event.target.style.border = "3px dashed #000000";
    }, false);

    document.addEventListener("dragend", function( event ) {
        event.target.style.transition= "all 1s linear";
        event.target.style.border = "none";
        event.target.style.opacity = 1;

    }, false);

    

});
