var start = new Date().getTime();

function getRandomColor() {
    
    //The result of letters will be an array with the values
    var letters = '0123456789ABCDEF'.split('');

    var color = '#';

    for (var i = 0; i < 6; i++ ) {

        //Math.random() returns a random number between 0 (inclusive) and 1 (exclusive)
        //Math.floor() returns the largest integer less than or equal to a given number
        color += letters[Math.floor(Math.random() * 16)];

    }

    return color;

}

function makeShapeAppear() {
    
    var top = Math.random() * 400;
    
    var left = Math.random() * 400;
    
    var width = (Math.random() * 200) + 100;
    
    if (Math.random() > 0.5) {
        
        document.getElementById("shape").style.borderRadius = "50%";
        
    } else {
        
        document.getElementById("shape").style.borderRadius = "0";
        
    }
    
    document.getElementById("shape").style.backgroundColor = getRandomColor();
    
    document.getElementById("shape").style.width = width + "px";
    
    document.getElementById("shape").style.height = width + "px";
    
    document.getElementById("shape").style.top = top + "px";
    
    document.getElementById("shape").style.left = left + "px";

    document.getElementById("shape").style.display = "block";
    
    //Date objects are created with new Date().
    //getTime() returns the number of milliseconds since 1970/01/01
    start = new Date().getTime();

}

function appearAfterDelay() {
    
    //setTimeout() calls a function or evaluates an expression after a specified number of milliseconds.
    setTimeout(makeShapeAppear, Math.random() * 2000);
    
}

appearAfterDelay();

document.getElementById("shape").onclick = function() {
    
    document.getElementById("shape").style.display = "none";
    
    var end = new Date().getTime();
    
    // ms -> s
    var timeTaken = (end - start) / 1000;
    
    document.getElementById("timeTaken").innerHTML = timeTaken + "s";
    
    appearAfterDelay();
    
}