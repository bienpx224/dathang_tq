 /*
 图片自动按比例显示
 调用：<img src="/images/trans_img_2.png" border="0" width="0" height="0" onload="AutoResizeImage(190,150,this)" /> 190,150是宽高
 */
    // 不喜欢定义全局变量的同学可以不在这儿定义，我没有使用，用起来的话要好些个人认为
    var MAX_WIDTH = 300; // 最大宽度
    var MAX_HEIGHT = 200; // 最大高度

    // 预览图片
    function previewImage(file,order)  
    {
      var div = document.getElementById("preview");  
      if (file.files && file.files[0])  
      {  
        div.innerHTML = "<img id=\"imghead\">";  
        var img = document.getElementById("imghead");
        var reader = new FileReader();  
        reader.onload = function(evt){
            AutoResizeImage(300,200,img,evt.target.result);
        }  
        reader.readAsDataURL(file.files[0]);  
      }  
      else  
      {
        div.innerHTML = "<img id=\"imghead\">";  
        var img = document.getElementById("imghead");
        AutoResizeImage(300,200,img,file.value);
      }
    }
    
    // 缩放图片，imgSrc用户延迟加载图片url
    function AutoResizeImage(maxWidth,maxHeight,objImg,imgSrc){
        var img = new Image();
        img.src = imgSrc || objImg.src;
        var hRatio;
        var wRatio;
        var Ratio = 1;
        var w = img.width;
        var h = img.height;
        wRatio = maxWidth / w;
        hRatio = maxHeight / h;
        if (maxWidth ==0 && maxHeight==0){
        Ratio = 1;
        }else if (maxWidth==0){
        if (hRatio<1) Ratio = hRatio;
        }else if (maxHeight==0){
        if (wRatio<1) Ratio = wRatio;
        }else if (wRatio<1 || hRatio<1){
        Ratio = (wRatio<=hRatio?wRatio:hRatio);
        }
        if (Ratio<1){
        w = w * Ratio;
        h = h * Ratio;
        }
        objImg.style.height = Math.round(h) + "px";
        objImg.style.width = Math.round(w) + "px";
        
        if(h < maxHeight) { // 纵向有空余空间
            objImg.style.marginTop = Math.round((maxHeight - h) / 2) + "px";
        }
        if(w < maxWidth) { // 横向有空余空间
            objImg.style.marginLeft = Math.round((maxWidth - w) / 2) + "px";
        }
        if(!!!objImg.src)
            objImg.src = imgSrc;
    }
