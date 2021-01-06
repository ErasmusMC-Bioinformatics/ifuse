function msgbox(title, message, exit, width, height, exitjs) {
    exit   = typeof(exit)   != 'undefined' ? exit   : "close window";
    width  = typeof(width)  != 'undefined' ? width  : 700;
    height = typeof(height) != 'undefined' ? height : 500;
    exitjs = typeof(exitjs) != 'undefined' ? exitjs : "javascript: document.getElementById(\"displaybox\").style.display = \"none\";";
    
    if (!document.getElementById('displaybox')) {
        var div = document.createElement("div");
        div.style.display = 'none';
        div.id = 'displaybox';
        div.innerHTML = '<!-- for the messages -->';
        document.body.appendChild(div);
    }
    var thediv = document.getElementById('displaybox');
    thediv.style.display = "";
    thediv.innerHTML = "<div id='underlay'>&nbsp;</div><table width='100%' height='100%' style='position:fixed; z-index: 10005;'><tr><td align='center' valign='middle' width='100%' height='100%'><table style='width:"+width+"px; height:"+height+"px; border-collapse:collapse; border:1px solid #000; background-color:#fff;'><tbody><tr><td style='height:25px; border-bottom:1px solid #000; font-variant:small-caps; background-color:#000; font-weight:bold; padding:3px'>"+title+"</td></tr><tr><td style='text-align:justify; vertical-align:top; color:#000; padding:0px;'><div style='width:100%; height:"+((height>75?height:75)-55)+"px; overflow-y:auto;'><small>"+message+"</small></div></td></tr><tr><td style='height:25px; border-top:1px solid #000; text-align:center; vertical-align:middle; font-variant:small-caps;' onclick='" + exitjs + "'><a href='javascript:void(0);'>" + exit + "</a></td></tr></tbody></table></td></tr></table>";
    return false;
}


function iFrameBox(title,url,exit,width,height,exitjs) {
    msgbox(title,"<iframe src=\""+url+"\" style=\"border:0px solid #000; width:100%; height:540px; margin:0px; padding:0px; overflow-x:hidden; overflow-y:scroll;\">iFrames are not supported in your web browser</iframe>",exit,width,height,exitjs);
}
