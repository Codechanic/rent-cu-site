var Front = function()
{
    function cover_mainslider(){
        var slider_id = "cover_mainslider";
        
        if($("#" + slider_id).length == 0) {
            return ;
        }
        
//        var _SlideshowTransitions = [
//        //Fade in R
//        {$Duration: 1200, $During: { $Left: [0.3, 0.7] }, $FlyDirection: 2, $Easing: { $Left: $JssorEasing$.$EaseInCubic, $Opacity: $JssorEasing$.$EaseLinear }, $ScaleHorizontal: 0.3, $Opacity: 2 }
//        //Fade out L
//        , { $Duration: 1200, $SlideOut: true, $FlyDirection: 1, $Easing: { $Left: $JssorEasing$.$EaseInCubic, $Opacity: $JssorEasing$.$EaseLinear }, $ScaleHorizontal: 0.3, $Opacity: 2 }
//        ];

        var options = {
            $FillMode: 1,
            $AutoPlay: true,
            $AutoPlayInterval: 4500,
            $PauseOnHover: true,
            $DragOrientation: 3,

            $BulletNavigatorOptions: {
                $Class: $JssorBulletNavigator$,
                $ChanceToShow: 2,
                $SpacingX: 6,
                $SpacingY: 5
            },

            $ThumbnailNavigatorOptions: {
                $Class: $JssorThumbnailNavigator$,
                $ChanceToShow: 2,
                $ActionMode: 0,
                $DisableDrag: true
            }
        };

        var jssor_slider = new $JssorSlider$(slider_id, options);
        //responsive code begin
        //you can remove responsive code if you don't want the slider scales while window resizes
        function ScaleSlider() {
            var parentWidth = jssor_slider.$Elmt.parentNode.clientWidth;
            if (parentWidth)
                jssor_slider.$SetScaleWidth(parentWidth);
            else
                window.setTimeout(ScaleSlider, 30);
        } 
        
//        function ScaleSlider() {
//            var bodyWidth = document.body.clientWidth;
//            var parent = jssor_slider.$Elmt.parentNode.clientWidth;
//            if (bodyWidth)
//                jssor_slider.$SetScaleWidth(Math.min(bodyWidth, parent));
//            else
//                window.setTimeout(ScaleSlider, 30);
//        }

        ScaleSlider();

        if (!navigator.userAgent.match(/(iPhone|iPod|iPad|BlackBerry|IEMobile)/)) {
            $(window).bind('resize', ScaleSlider);
        }
    }

    function cover_priceslider(){
        var slider_id = "cover_priceslider";
        
        if($("#" + slider_id).length == 0) {
            return ;
        }
        
        var options = {
            $ArrowKeyNavigation: true,
            $SlideDuration: 300,
            $MinDragOffsetToSlide: 20,
            $SlideWidth: 255,
            $SlideSpacing: 18,
            $DisplayPieces: 3,

            $ArrowNavigatorOptions: {
                $Class: $JssorArrowNavigator$,
                $ChanceToShow: 2,
                $AutoCenter: 0,
                $Steps: 3
            }
        };

        var jssor_slider = new $JssorSlider$(slider_id, options);

        //responsive code begin
        //you can remove responsive code if you don't want the slider scales while window resizes
        function ScaleSlider() {
            var bodyWidth = document.body.clientWidth;
            var parent = jssor_slider.$Elmt.parentNode.clientWidth;
            if (bodyWidth)
                jssor_slider.$SetScaleWidth(Math.min(bodyWidth, parent));
            else
                window.setTimeout(ScaleSlider, 30);
        }
        
        ScaleSlider();

        if (!navigator.userAgent.match(/(iPhone|iPod|iPad|BlackBerry|IEMobile)/)) {
            $(window).bind('resize', ScaleSlider);
        }
    }

    function cover_visitslider(){
        var slider_id = "cover_visitslider";
        
        if($("#" + slider_id).length == 0) {
            return ;
        }
        
        var options = {
            $AutoPlay: false,
            $AutoPlayInterval: 800,
            $SlideDuration: 300,
            $MinDragOffsetToSlide: 20,
            $SlideWidth: 178,
            $SlideSpacing: 28,
            $DisplayPieces: 4,
            $Loop: 1,
            $AutoPlaySteps: 5,

            $ArrowNavigatorOptions: {
                $Class: $JssorArrowNavigator$,
                $ChanceToShow: 2,
                $AutoCenter: 0,
                $Steps: 4
            }
        };

        var jssor_slider = new $JssorSlider$(slider_id, options);
        jssor_slider.$Play();

        //responsive code begin
        //you can remove responsive code if you don't want the slider scales while window resizes
        function ScaleSlider() {
            var bodyWidth = document.body.clientWidth;
            var parent = jssor_slider.$Elmt.parentNode.clientWidth;
            if (bodyWidth)
                jssor_slider.$SetScaleWidth(Math.min(bodyWidth, parent));
            else
                window.setTimeout(ScaleSlider, 30);
        }
        
        ScaleSlider();

        if (!navigator.userAgent.match(/(iPhone|iPod|iPad|BlackBerry|IEMobile)/)) {
            $(window).bind('resize', ScaleSlider);
        }
    }

    function homestayslider(){
        var slider_id = "homestayslider";
        
        if($("#" + slider_id).length == 0) {
            return ;
        }
        
        var options = {
            $FillMode: 1,
            $AutoPlay: true,
            $AutoPlayInterval: 4500,
            $PauseOnHover: true,
            $DragOrientation: 3,
            
            $ArrowKeyNavigation: true,

            $ArrowNavigatorOptions: {
                $Class: $JssorArrowNavigator$,
                $ChanceToShow: 1
            },
            
            $ThumbnailNavigatorOptions: {                       //[Optional] Options to specify and enable thumbnail navigator or not
                $Class: $JssorThumbnailNavigator$,              //[Required] Class to create thumbnail navigator instance
                $ChanceToShow: 2,                               //[Required] 0 Never, 1 Mouse Over, 2 Always

                $ActionMode: 1,                                 //[Optional] 0 None, 1 act by click, 2 act by mouse hover, 3 both, default value is 1
                $SpacingX: 3,                                   //[Optional] Horizontal space between each thumbnail in pixel, default value is 0
                $DisplayPieces: 7,                             //[Optional] Number of pieces to display, default value is 1
                $ParkingPosition: 236,                             //[Optional] The offset position to park thumbnail
                $AutoCenter: 3
            }
        };

        var jssor_slider = new $JssorSlider$(slider_id, options);
        //responsive code begin
        //you can remove responsive code if you don't want the slider scales while window resizes
        function ScaleSlider() {
            var parentWidth = jssor_slider.$Elmt.parentNode.clientWidth;
            if (parentWidth)
                jssor_slider.$SetScaleWidth(parentWidth);
            else
                window.setTimeout(ScaleSlider, 30);
        }

        ScaleSlider();

        if (!navigator.userAgent.match(/(iPhone|iPod|iPad|BlackBerry|IEMobile)/)) {
            $(window).bind('resize', ScaleSlider);
        }
    }


    return {
        init: function() {
            cover_mainslider();
            cover_priceslider();
            cover_visitslider();
        },
        homestay: function(){
            homestayslider();
        }
    };
}();