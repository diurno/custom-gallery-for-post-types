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
        
        if(tagName === "IMG") {
            wp_media_uploader = instanceMediaUploader(false);
            wp_media_uploader.on("insert", function(){
                var imageResponse = wp_media_uploader.state().get("selection").first().toJSON();
                var image_url = imageResponse.url;
                console.log(imgElement.target.nextElementSibling);
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
                    var img_box_container = document.querySelector("#img_box_container");
                    var box = document.querySelector("#master_box .gallery_single_row");
                    let cloneBox = box.cloneNode(true);
                    img_box_container.append(cloneBox);
                    var element = document.querySelector("#img_box_container .gallery_single_row:last-child .image_container");
                    const imgEL = document.createElement("img");
                    imgEL.classList.add("gallery_img_img");
                    imgEL.src = image_url;
                    element.prepend(imgEL);
                    var meta_image_url = element.querySelector(".meta_image_url");
                    meta_image_url.value = image_url;
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

});
