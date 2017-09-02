//物品统计功能

function accMul(arg1, arg2) {
    var m = 0, s1 = arg1.toString(), s2 = arg2.toString();
    try {
        m += s1.split(".")[1].length;
    } catch (e) { }
    try {
        m += s2.split(".")[1].length;
    } catch (e) { }
    return (Number(s1.replace(".", "")) * Number(s2.replace(".", "")) / Math.pow(10, m)).toFixed(2);
}
function accAdd(arg1, arg2) {
    var r1, r2, m;
    try {
        r1 = arg1.toString().split(".")[1].length;
    } catch (e) {
        r1 = 0;
    }
    try {
        r2 = arg2.toString().split(".")[1].length;
    } catch (e) {
        r2 = 0;
    }
    m = Math.pow(10, Math.max(r1, r2));
    return ((arg1 * m + arg2 * m) / m).toFixed(2);
}
function minus(num1, num2) {
    var n1, n2, p1, p2;
    try {
        n1 = num1.toString().split(".")[1].length;
    } catch (e) {
        n1 = 0;
    }
    try {
        n2 = num2.toString().split(".")[1].length;
    } catch (e) {
        n2 = 0;
    }
    p1 = Math.pow(10, Math.max(n1, n2));
    p2 = (n1 >= n2) ? n1 : n2;
    return ((num1 * p1 - num2 * p1) / p1).toFixed(2);
}
function accDiv(arg1, arg2) {
    var t1 = 0, t2 = 0, r1, r2;
    try { t1 = arg1.toString().split(".")[1].length } catch (e) { }
    try { t2 = arg2.toString().split(".")[1].length } catch (e) { }
    with (Math) {
        r1 = Number(arg1.toString().replace(".", ""))
        r2 = Number(arg2.toString().replace(".", ""))
        return ((r1 / r2) * pow(10, t2 - t1)).toFixed(2);
    }
}
