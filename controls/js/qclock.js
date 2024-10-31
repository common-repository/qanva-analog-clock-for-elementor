
		function makeSVG(name, attr) {
				var svg = document.createElementNS("http://www.w3.org/2000/svg", name);
			for (var key in attr) {
				svg.setAttributeNS(null, key, attr[key]);
			}
			return svg;
		}
		
			function makeSET(svg, svgs) {
				for (var key in svgs) {
					svg.appendChild(svgs[key]);
				}
				return svg;
			}
			
			function makeqanvaclock(wh,color,secondc,colorf,clocksize,usenum,tdiff,shandc,mhandc,hhandc,fontsize,clockid,useface){
					//	(function(){
						
							var w = wh;
							var h = wh;
							var r1 = 0.45 * w;
							var r2 = 0.42 * w;
							var sr = 0.40 * w;
							var mr = 0.36 * w;
							var hr = 0.28 * w;
							var cr = 0.02 * w;
							var svg = makeSVG("svg", {
								"id": "clockcanvas-" + clockid,
								"width": w,
								"height": h
							});
							var rec = makeSVG("rect", {
								"id": "clockrect-" + clockid,
								"width": w,
								"height": h,
								"fill": "rgba(0,0,0,0)"
							});
							var cir = makeSVG("circle", {
								"id": "clockcircle-" + clockid,
								"cx": w / 2,
								"cy": h / 2,
								"r": r1,
								"fill": color
							});
							var hhand = makeSVG("line", {
								"id": "hhand-" + clockid,
								"x1": (w / 2),
								"y1": (h / 2),
								"x2": 0,
								"y2": 0,
								"style": "opacity:0;"
							});
							var mhand = makeSVG("line", {
								"id": "mhand-" + clockid,
								"x1": (w / 2),
								"y1": (h / 2),
								"x2": 0,
								"y2": 0,
								"style": "opacity:0;"
							});
							var shand = makeSVG("line", {
								"id": "shand-" + clockid,
								"x1": (w / 2),
								"y1": (h / 2),
								"x2": 0,
								"y2": 0,
								"style": "opacity:0;"
							});
							var cap = makeSVG("circle", {
								"id": "clockcap-" + clockid,
								"cx": (w / 2),
								"cy": (h / 2),
								"r": cr,
								"fill": secondc
							});
							var svgset = makeSET(svg, {
								1: rec,
								2: cir,
								3: hhand,
								4: mhand,
								5: shand,
								6: cap
							});
							document.getElementById("qanvaclock-" + clockid).appendChild(svgset);
							
								
							function makedotnotnumber(){
								var rf = Math.PI / 180;
								for (var i = 0; i < 360; i += 6) {
									var radian = (-90 + i) * rf;
									var cx = (w / 2 + r2 * Math.cos(radian)).toFixed(2);
									var cy = (h / 2 + r2 * Math.sin(radian)).toFixed(2);
									var point = document.createElementNS("http://www.w3.org/2000/svg", "circle");
									point.setAttributeNS(null, "cx", cx);
									point.setAttributeNS(null, "cy", cy);
									point.setAttributeNS(null, "fill", colorf);
									if (i % 30 === 0) {
										point.setAttributeNS(null, "r", 0.009*w);
									} else {
										point.setAttributeNS(null, "r", 0.003*w);
									}
									document.getElementById("clockcanvas-" + clockid).appendChild(point);
								}
							}
								
							function makenumber(){
								var rf = Math.PI / 180;
								for (var i = 0; i < 360; i += 30) {
									var radian = (-90 + i) * rf;
									var cx = (w / 2.1 + r2 * Math.cos(radian)).toFixed(2);
									var cy = (h / 2.1 + r2 * Math.sin(radian)).toFixed(2);
									var nums =[12,1,2,3,4,5,6,7,8,9,10,11];
									var point = document.createElementNS("http://www.w3.org/2000/svg", "text");
									point.setAttributeNS(null, "x", (parseInt(cx) + parseInt(fontsize/3)));
									point.setAttributeNS(null, "y", (parseInt(cy) + parseInt(fontsize/2) + clocksize/25));
									point.setAttributeNS(null, "fill", colorf);
									point.setAttributeNS(null, "font-size", fontsize);
									point.setAttributeNS(null, "text-anchor", "middle");
									if (i % 30 === 0) {
										var textNode = document.createTextNode(nums[i/30]);
										point.appendChild(textNode);
									}
									document.getElementById("clockcanvas-" + clockid).appendChild(point);
								}
							}
	
							if("num" == usenum && useface == 'yes'){
								makenumber();	
							}
  					else{
								if(useface == 'yes'){
									makedotnotnumber();
								}
							}					
											
							function updateClock(date,clockidb){
								var rf = Math.PI / 180;
								var sec = date.getSeconds();
								var min = sec/60 + date.getMinutes();
								var hrs = (min/60 + date.getUTCHours()) + parseInt(tdiff);
								var sAng = (-90 + 6 * sec) * rf;
								var mAng = (-90 + 6 * min) * rf;
								var hAng = (-90 + 30 * hrs) * rf;
									if("num" == usenum){
										if(min < 15){
											mAng = (-90 + 6.2 * min) * rf;
										}
										if(min > 30){
											mAng = (-90 + 5.8 * min) * rf;
											hAng = (-90 + 30.1 * hrs) * rf;
										}
										if(min > 45){
											mAng = (-90 + 6 * min) * rf;
										}
									}
				/*					
								var sx2 = ((w / 2) - (fontsize) + sr * Math.cos(sAng)).toFixed(2);
								var sy2 = ((h / 2) - (fontsize) + sr * Math.sin(sAng)).toFixed(2);
								var mx2 = ((w / 2) - (fontsize) + mr * Math.cos(mAng)).toFixed(2);
								var my2 = ((h / 2) - (fontsize) + mr * Math.sin(mAng)).toFixed(2);
								var hx2 = ((w / 2) - (fontsize) + hr * Math.cos(hAng)).toFixed(2);
								var hy2 = ((h / 2) - (fontsize) + hr * Math.sin(hAng)).toFixed(2);
				*/
								var sx2 = (w/2 + sr * Math.cos(sAng)).toFixed(2);
								var sy2 = (h/2 + sr * Math.sin(sAng)).toFixed(2);
								var mx2 = (w/2 + mr * Math.cos(mAng)).toFixed(2);
								var my2 = (h/2 + mr * Math.sin(mAng)).toFixed(2);
								var hx2 = (w/2 + hr * Math.cos(hAng)).toFixed(2);
								var hy2 = (h/2 + hr * Math.sin(hAng)).toFixed(2);
								if(null !== document.getElementById("shand-" + clockidb)){
										document.getElementById("shand-" + clockidb).setAttributeNS(null, "x2", sx2);
										document.getElementById("shand-" + clockidb).setAttributeNS(null, "y2", sy2);
										document.getElementById("shand-" + clockidb).setAttributeNS(null, "style", "stroke:" + shandc + ";stroke-width:" + (0.006 * w) + ";opacity:1;");
										document.getElementById("mhand-" + clockidb).setAttributeNS(null, "x2", mx2);
										document.getElementById("mhand-" + clockidb).setAttributeNS(null, "y2", my2);
										document.getElementById("mhand-" + clockidb).setAttributeNS(null, "style", "stroke:" + mhandc + ";stroke-width:" + (0.012 * w) + ";opacity:1;");
										document.getElementById("hhand-" + clockidb).setAttributeNS(null, "x2", hx2);
										document.getElementById("hhand-" + clockidb).setAttributeNS(null, "y2", hy2);
										document.getElementById("hhand-" + clockidb).setAttributeNS(null, "style", "stroke:" + hhandc + ";stroke-width:" + (0.018 * w) + ";opacity:1;");
								}
							}
							function detectChange(preSec) {
								var date = new Date();
								var curSec = date.getSeconds();
								if (preSec != curSec && clockid != '') {
									updateClock(date,clockid);
								}
							}
							setInterval(
								function() {
								var dat = new Date();
								var sec = dat.getSeconds;
								detectChange(sec);
							},50);
			//			})();
}