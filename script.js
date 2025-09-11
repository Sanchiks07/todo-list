function startTime() {
    const now = new Date();
    const time = now.toLocaleTimeString("en-US", { 
        hour: '2-digit', 
        minute: '2-digit', 
        hour12: true 
    });

    document.getElementById('txt').innerHTML =  time;

    setTimeout(startTime, 500);
}
window.onload = startTime;