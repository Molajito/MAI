mwf.browser=new function(){this.cookieName=mwf.site.cookie.prefix+"browser";var a=window;var b=document;this.getWidth=function(){return a.innerWidth!=null?a.innerWidth:b.documentElement&&b.documentElement.clientWidth?b.documentElement.clientWidth:b.body!=null?b.body.clientWidth:null};this.getHeight=function(){return a.innerHeight!=null?a.innerHeight:b.documentElement&&b.documentElement.clientHeight?b.documentElement.clientHeight:b.body!=null?b.body.clientHeight:null};this.posLeft=function(){return typeof a.pageXOffset!="undefined"?a.pageXOffset:b.documentElement&&b.documentElement.scrollLeft?b.documentElement.scrollLeft:b.body.scrollLeft?b.body.scrollLeft:0};this.posTop=function(){return typeof a.pageYOffset!="undefined"?a.pageYOffset:b.documentElement&&b.documentElement.scrollTop?b.documentElement.scrollTop:b.body.scrollTop?b.body.scrollTop:0};this.posRight=function(){return mwf.browser.posLeft()+mwf.browser.pageWidth()};this.posBottom=function(){return mwf.browser.posTop()+mwf.browser.pageHeight()};this.pageWidth=this.getWidth;this.pageHeight=this.getHeight;this.isQuirksMode=function(){return document.compatMode=="BackCompat"||document.compatMode=="QuirksMode"};this.isStandardsMode=function(){return !this.isQuirksMode()};this.getMode=function(){return this.isQuirksMode()?"quirks":"standards"}};