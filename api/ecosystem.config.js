module.exports = {
    apps: [{
        name: "assembly-order-accepted",
        script: "vendor/bin/laminas slm-queue:start assembly-order-accepted"
    }]
}