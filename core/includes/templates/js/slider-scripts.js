const CGPTSlider = function(selector){

    // Constructor here
    console.log(selector);
	// this.slider = {
    //     sliderEL: selector,
    //     sliderSlides: selector.querySelectorAll('ul li'),
    //     slideCount: selector.querySelectorAll('ul li').length,
    //     slideWidth: selector.querySelector('ul li').offsetWidth,
    //     slideHeight: selector.querySelector('ul li').offsetHeight,
    //     sliderUlWidth: selector.querySelectorAll('ul li').length * selector.querySelector('ul li').offsetWidth,
	// };

    this.slider = {
        sliderEL: selector,
        slidesCount: selector.querySelectorAll('ul li').length,
        position: 0,
        //slideWidth: selector.querySelector('ul li').offsetWidth,
        //slideHeight: selector.querySelector('ul li').offsetHeight,
        sliderWidth: selector.offsetWidth,
	};

    this.init();
}

CGPTSlider.prototype.init = function(){
	
	var _ = this;
    
    let newWidth = (_.slider.slidesCount * _.slider.sliderWidth) + 'px';
    _.slider.sliderEL.querySelector('ul').style.width = newWidth;
    //_.slider.position
    console.log(_.slider.sliderEL.querySelector('ul'));
    
    
	// // ATTACH CLICK EVENT FOR PREV ARROW
    let prevArrow = _.slider.sliderEL.querySelector('.control_prev');
	prevArrow.addEventListener("click", function(e) {
		_.moveLeft();
	});

    let nextArrow = _.slider.sliderEL.querySelector('.control_next');
	nextArrow.addEventListener("click", function(e) {
		_.moveRight();
	});
	
}	

CGPTSlider.prototype.moveRight = function() {
    var _ = this;
    _.slider.position++;
	if(_.slider.position==_.slider.slidesCount){ _.slider.position = 0; }
    let transitioValue = -(_.slider.sliderWidth * _.slider.position);
    _.slider.sliderEL.querySelector('ul').style.left = transitioValue+'px';
    console.log("right pos ",_.slider.position);
}

CGPTSlider.prototype.moveLeft = function() {
    var _ = this;
    _.slider.position--;
    if(_.slider.position == -1){ _.slider.position = _.slider.slidesCount - 1; }
    let transitioValue = -(_.slider.sliderWidth * _.slider.position);
    _.slider.sliderEL.querySelector('ul').style.left = transitioValue+'px';
    console.log("left pos ",_.slider.position);
}


window.addEventListener('load', (event) => {
	
	var galleryContainers = document.querySelectorAll(".cgpt_slider");
	if(galleryContainers) {
		
		[].forEach.call(galleryContainers, function(galleries) {
			var listingGallery = new CGPTSlider(galleries);

		});	
	}

});
