function themax()
	{
var a=document.getElementById("left").offsetHeight;
var b=document.getElementById("center").offsetHeight;
var c=document.getElementById("right").offsetHeight;
var themax=Math.max(Math.max(a,b),c)
document.getElementById("left").style.height=themax+"px";
document.getElementById("center").style.height=themax+"px";
document.getElementById("right").style.height=themax+"px";
	}
	
function killErrors() {
return true;
}
window.onerror = killErrors;