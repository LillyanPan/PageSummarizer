for (i = 1; i <= nSlices; i++){
setSlice(i);
run("Find Maxima...", "noise=10 output=[Point Selection]");
run("Measure");
wait(10);
}